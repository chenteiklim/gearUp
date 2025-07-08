<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>Access Denied</h1>";
    exit;
}

$username = $_SESSION['adminUsername'];

// Get superuser ID
$stmt = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $superuser_user_id = $result->fetch_assoc()['user_id'];
} else {
    die("Admin not found.");
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php';

// Fetch pending refund requests
$stmt = $conn->prepare("SELECT * FROM refundRequest WHERE status = 'pending'");
$stmt->execute();
$refunds_result = $stmt->get_result();

// Handle Approve Refund
if (isset($_POST['approveRefund']) && isset($_POST['refund_id'])) {
    $refund_id = $_POST['refund_id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Get customer info from refundRequest
        $stmt = $conn->prepare("
            SELECT r.user_id, u.usernames, r.order_item_id 
            FROM refundRequest r
            JOIN users u ON r.user_id = u.user_id
            WHERE r.request_id = ?
        ");
        $stmt->bind_param("i", $refund_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) throw new Exception("Refund request not found.");
        $row = $result->fetch_assoc();
        $customer_user_id = $row['user_id'];
        $customer_username = $row['usernames'];
        $order_item_id = $row['order_item_id'];

        // Get price from order_items
        $stmt = $conn->prepare("SELECT price FROM order_items WHERE order_item_id = ?");
        $stmt->bind_param("i", $order_item_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) throw new Exception("Order not found.");
        $price = (float)$result->fetch_assoc()['price'];

        // Update refund request to approved
        $stmt = $conn->prepare("UPDATE refundRequest SET status = 'approved' WHERE request_id = ?");
        $stmt->bind_param("i", $refund_id);
        $stmt->execute();

        // Update customer's wallet balance
        $stmt = $conn->prepare("SELECT wallet_balance FROM wallet WHERE user_id = ?");
        $stmt->bind_param("s", $customer_user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer_balance = (float)$result->fetch_assoc()['wallet_balance'];

        $new_customer_balance = $customer_balance + $price;
        $stmt = $conn->prepare("UPDATE wallet SET wallet_balance = ? WHERE user_id = ?");
        $stmt->bind_param("ds", $new_customer_balance, $customer_user_id);
        $stmt->execute();

        // Update platform wallet balance
        $stmt = $conn->prepare("SELECT wallet_balance FROM wallet WHERE user_id = ?");
        $stmt->bind_param("s", $superuser_user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $platform_balance = (float)$result->fetch_assoc()['wallet_balance'];

        $new_platform_balance = $platform_balance - $price;
        $stmt = $conn->prepare("UPDATE wallet SET wallet_balance = ? WHERE user_id = ?");
        $stmt->bind_param("ds", $new_platform_balance, $superuser_user_id);
        $stmt->execute();

        // Insert transaction record
        $param = 'refund';
        $stmt = $conn->prepare("
            INSERT INTO transactions (sender_id, receiver_id, amount, type, timestamp)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("ssds", $superuser_user_id, $customer_user_id, $price, $param);
        $stmt->execute();

        $conn->commit();
        echo "<p>Refund processed successfully.</p>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}

// Handle Reject Refund
if (isset($_POST['rejectRefund']) && isset($_POST['refund_id']) && isset($_POST['rejectionReason'])) {
    $refund_id = $_POST['refund_id'];
    $reason = $_POST['rejectionReason'];

    $stmt = $conn->prepare("UPDATE refundRequest SET status = 'rejected', rejectReason = ? WHERE request_id = ?");
    $stmt->bind_param("si", $reason, $refund_id);
    $stmt->execute();

    echo "<p>Refund request rejected. Reason: " . htmlspecialchars($reason) . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Refund Requests</title>
    <link rel="stylesheet" href="refund.css">
</head>
<body>
<div id="content">
    <h1>Manage Refund Requests</h1>
    <h2>Pending Refund Requests</h2>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Username</th>
            <th>Product Name</th>
            <th>Reason</th>
            <th>Proof</th>
            <th>Status</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $refunds_result->fetch_assoc()): ?>
            <?php
                // Get username for this row
                $stmtUser = $conn->prepare("SELECT usernames FROM users WHERE user_id = ?");
                $stmtUser->bind_param("i", $row['user_id']);
                $stmtUser->execute();
                $userResult = $stmtUser->get_result();
                $customer_username_row = $userResult->fetch_assoc()['usernames'];
            ?>
            <tr>
                <td><?php echo $row['order_item_id']; ?></td>
                <td><?php echo htmlspecialchars($customer_username_row); ?></td>
                <td><?php echo htmlspecialchars($row['productName']); ?></td>
                <td><?php echo htmlspecialchars($row['rejectReason']); ?></td>
                <td>
                    <?php if (!empty($row['proof'])): ?>
                        <a href="<?php echo htmlspecialchars($row['proof']); ?>" target="_blank">View Proof</a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td>
                    <!-- Approve Form -->
                    <form method="POST" action="">
                        <input type="hidden" name="refund_id" value="<?php echo $row['request_id']; ?>">
                        <button type="submit" name="approveRefund">Approve</button>
                    </form>

                    <!-- Reject Form -->
                    <form method="POST" action="">
                        <input type="hidden" name="refund_id" value="<?php echo $row['request_id']; ?>">
                        <button type="button" onclick="showRejectionForm(<?php echo $row['request_id']; ?>)">Reject</button>
                        <div id="rejectionForm-<?php echo $row['request_id']; ?>" style="display:none;">
                            <textarea name="rejectionReason" required placeholder="Enter rejection reason"></textarea>
                            <button type="submit" name="rejectRefund">Submit</button>
                        </div>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="refundHistory.php">
        <button style="margin-top: 20px; margin-left: 800px;">View Refund History</button>
    </a>
</div>

<script>
function showRejectionForm(refundId) {
    document.getElementById('rejectionForm-' + refundId).style.display = 'block';
}
</script>
</body>
</html>