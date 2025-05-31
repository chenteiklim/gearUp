<?php

session_start();

// Check if admin is logged in
if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>Access Denied</h1>";
    exit;
}
$username = $_SESSION['adminUsername'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminNavbar.php'; 

// Fetch pending refund requests
$stmt = $conn->prepare("SELECT * FROM refundRequest WHERE status = 'pending'");
$stmt->execute();
$result = $stmt->get_result();

// Get wallet balance (wallet table uses username)

    if (isset($_POST['approveRefund'])) {
        $refund_id = $_POST['refund_id'];

        // Fetch the usernames (customer) for the refund request
        $stmt = $conn->prepare("SELECT usernames FROM refundRequest WHERE request_id = ?");
        $stmt->bind_param("i", $refund_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $customerUsername = $row['usernames']; // The customer who requested the refund
        } else {
            die("Refund request not found.");
        }
        
        $stmtCustomer = $conn->prepare("SELECT * FROM wallet WHERE usernames = ?");
        $stmtCustomer->bind_param("s", $customerUsername);
        $stmtCustomer->execute();
        $customerWallet_result = $stmtCustomer->get_result();

        $customerWallet = $customerWallet_result->fetch_assoc();
        $customerWallet_balance = (float) $customerWallet['wallet_balance'];


        // Get wallet balance (wallet table uses username)
        $stmtplatform = $conn->prepare("SELECT * FROM wallet WHERE usernames = ?");
        $stmtplatform->bind_param("s", $platformName);
        $stmtplatform->execute();
        $platformWallet_result = $stmtplatform->get_result();

        $platformWallet = $platformWallet_result->fetch_assoc();
        $platformWallet_balance = isset($platformWallet['wallet_balance']) ? (float)$platformWallet['wallet_balance'] : 99.00;

        if (isset($_POST['refund_id'])) {
            $refund_id = $_POST['refund_id'];
            echo $refund_id;
            // Fetch the order_id corresponding to the refund request
            $stmtOrder = $conn->prepare("SELECT orders_id FROM refundRequest WHERE request_id = ?");
            $stmtOrder->bind_param("i", $refund_id);
            $stmtOrder->execute();
            $orderResult = $stmtOrder->get_result();
        
            if ($orderResult->num_rows > 0) {
                $orderRow = $orderResult->fetch_assoc();
                $orders_id = $orderRow['orders_id']; // Assign the orders_id from the refundRequest table
            } else {
                die("Order not found.");
            }
        }
        else{
            echo 'refund_id not submitted';
        }
        // Fetch the total price from the orders table for the refund request
        $stmtOrder = $conn->prepare("SELECT total_price FROM orders WHERE orders_id = ?");
        $stmtOrder->bind_param("i", $orders_id); // Make sure $orders_id is passed correctly
        $stmtOrder->execute();
        $orderResult = $stmtOrder->get_result();

        if ($orderResult->num_rows > 0) {
            $orderRow = $orderResult->fetch_assoc();
            $total_price = $orderRow['total_price']; // Store the total price
        } else {
            echo $orders_id;

            die("Order not found.");
        }

        // Approve the refund request
        $stmt = $conn->prepare("UPDATE refundRequest SET status = 'approved' WHERE request_id = ?");
        $stmt->bind_param("i", $refund_id);
        $stmt->execute();

        // Fetch the current customer wallet balance using the customer's username
        $stmt = $conn->prepare("SELECT wallet_balance FROM wallet WHERE usernames = ?");
        $stmt->bind_param("s", $customerUsername); // Use the customerUsername here
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();
        $customerWallet_balance = $customer['wallet_balance'];

        // Refund logic: Add the refunded amount back to customer's wallet
        $customerNew_balance = $customerWallet_balance + $total_price;
        $stmtRefund = $conn->prepare("UPDATE wallet SET wallet_balance = ? WHERE usernames = ?");
        $stmtRefund->bind_param("ds", $customerNew_balance, $customerUsername);
        if (!$stmtRefund->execute()) {
            $conn->rollback();
            die("Error refunding wallet balance.");
        }

        $platformName= 'Trust Toradora';

        // Record refund transaction in history
        $param2 = 'refund'; // Type of transaction
        $transactionRefund = "INSERT INTO transactions (sender_name, receiver_name, amount, type, timestamp) 
                              VALUES (?, ?, ?, ?, NOW())";
        $stmtTransaction = $conn->prepare($transactionRefund);
        $stmtTransaction->bind_param("ssds", $platformName, $customerUsername, $total_price, $param2);
        if (!$stmtTransaction->execute()) {
            $conn->rollback();
            die("Error inserting refund transaction record.");
        }

        // Update platform's wallet (deduct the refunded amount from the platform's wallet)
        $stmtPlatform = $conn->prepare("SELECT wallet_balance FROM wallet WHERE usernames = ?");
        $stmtPlatform->bind_param("s", $platformName);
        $stmtPlatform->execute();
        $result = $stmtPlatform->get_result();
        $platform = $result->fetch_assoc();
        $platformWallet_balance = $platform['wallet_balance'];

        // Subtract refund amount from platform's wallet balance
        $platformNew_balance = $platformWallet_balance - $total_price;
        $stmtPlatformRefund = $conn->prepare("UPDATE wallet SET wallet_balance = ? WHERE usernames = ?");
        $stmtPlatformRefund->bind_param("ds", $platformNew_balance, $platformName);
        if (!$stmtPlatformRefund->execute()) {
            $conn->rollback();
            die("Error updating platform wallet balance.");
        }

        echo "Refund processed successfully.";
    }

    if (isset($_POST['rejectRefund'])) {
        $refund_id = $_POST['refund_id'];
        $reason = $_POST['rejectionReason']; // Get the rejection reason from the form
        // Save the rejection reason in the database
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

    <!-- Pending Refund Requests Section -->
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
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['orders_id']; ?></td>
                <td><?php echo $row['usernames']; ?></td>
                <td><?php echo $row['productName']; ?></td>
                <td><?php echo $row['reason']; ?></td>
                <td><a href="<?php echo $row['proof']; ?>" target="_blank">View Proof</a></td>
                <td><?php echo $row['status']; ?></td>
                <td><?php echo $row['date']; ?></td>
                <td>
                    <!-- Approve Button Form -->
                    <form action="refund.php" method="POST">
                        <input type="hidden" name="refund_id" value="<?php echo $row['request_id']; ?>">
                        <button type="submit" name="approveRefund">Approve</button>
                    </form>

                    <!-- Reject Button Form -->
                    <form action="refund.php" method="POST">
                        <input type="hidden" name="refund_id" value="<?php echo $row['request_id']; ?>">
                        <button type="button" onclick="showRejectionForm(<?php echo $row['request_id']; ?>)">Reject</button>

                        <!-- Rejection reason form (hidden by default) -->
                        <div id="rejectionForm-<?php echo $row['request_id']; ?>" style="display:none;">
                            <textarea name="rejectionReason" placeholder="Enter rejection reason here..." required></textarea>
                            <button type="submit" name="rejectRefund">Submit Rejection</button>
                        </div>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Link to Refund History Page -->
    <a href="refundHistory.php">
        <button style="margin-top: 20px; margin-left:800px;">View Refund History</button>
    </a>
</div>

<script>
// Show the rejection form when "Reject" button is clicked
function showRejectionForm(refundId) {
    const form = document.getElementById('rejectionForm-' + refundId);
    form.style.display = 'block';  // Display the form
}
</script>
</body>
</html>