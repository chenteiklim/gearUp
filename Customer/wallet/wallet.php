<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

$username = $_SESSION['username'];

// Check if wallet exists
$sql = "SELECT COUNT(*) AS count FROM wallet WHERE usernames = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    // Wallet doesn't exist, create it
    $sql = "INSERT INTO wallet (usernames, wallet_balance) VALUES (?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
}

// Separate deposit logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deposit'])) {
    include 'deposit.php';
}

// Fetch updated wallet balance
$sql = "SELECT wallet_balance FROM wallet WHERE usernames = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$wallet_balance = $row ? $row['wallet_balance'] : 0.00;

// Get complete transaction history (both sent & received)
$sql = "SELECT * FROM transactions WHERE sender_name = ? OR receiver_name = ? ORDER BY timestamp DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $username);
$stmt->execute();
$transactions = $stmt->get_result();

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/customerNavbar.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Page</title>
    <link rel="stylesheet" type="text/css" href="wallet.css">
</head>
<body>

<div class="container">
    <h2>Wallet Balance: RM <?php echo number_format($wallet_balance, 2); ?></h2>
    <form method="POST">
        <input type="hidden" name="deposit" value="1">
        <button class="btn">Add RM 50</button>
    </form>
    <h3>Transaction History</h3>
    <table>
        <tr><th>Sender Name</th><th>Receiver Name</th><th>Type</th><th>Amount</th><th>Date</th></tr>
        <?php while ($row = $transactions->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['sender_name']; ?></td>
                <td><?php echo $row['receiver_name']; ?></td>
                <td><?php echo ucfirst($row['type']); ?></td>
                <td>RM <?php echo number_format($row['amount'], 2); ?></td>
                <td><?php echo $row['timestamp']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>