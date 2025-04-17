<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>Access Denied</h1>";
    exit;
}
$username = $_SESSION['adminUsername'];

// Fetch approved or rejected refund requests
$stmt = $conn->prepare("SELECT * FROM refundRequest WHERE status != 'pending'");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Refund Request History</title>
    <link rel="stylesheet" href="refund.css"> <!-- Link to your existing CSS -->
</head>
<body>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/adminNavbar.php'; ?>

<div id="content">
    <h1>Refund Request History</h1>

    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>Username</th>
            <th>Product Name</th>
            <th>Reason</th>
            <th>Proof</th>
            <th>Status</th>
            <th>Date</th>
            <th>Rejection Reason</th> <!-- Added column for rejection reason -->
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['orders_id']; ?></td>
            <td><?php echo $row['usernames']; ?></td>
            <td><?php echo $row['productName']; ?></td>
            <td><?php echo $row['reason']; ?></td>
            <td>
                <?php if ($row['proof']): ?>
                    <a href="<?php echo $row['proof']; ?>" target="_blank">View Proof</a>
                <?php else: ?>
                    No proof
                <?php endif; ?>
            </td>
            <td style="text-transform: capitalize;"><?php echo $row['status']; ?></td>
            <td><?php echo $row['date']; ?></td>

            <!-- Conditionally display rejection reason if the refund request was rejected -->
            <td>
                <?php
                if ($row['status'] == 'rejected') {
                    echo $row['rejectReason'] ? $row['rejectReason'] : 'No reason provided';
                } else {
                    echo 'N/A'; // No rejection reason if not rejected
                }
                ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Back Button -->
    <a href="refund.php">
        <button style="margin-top: 20px;">← Back to Pending Requests</button>
    </a>
</div>

</body>
</html>