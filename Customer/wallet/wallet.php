<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

$username = $_SESSION['username'];

// Get user_id for the logged-in user
$stmt = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$user_id = $row['user_id'] ?? 0;
$stmt->close();

// Check if wallet exists
$sql = "SELECT COUNT(*) AS count FROM wallet 
WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);  
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if ($row['count'] == 0) {
    // Wallet doesn't exist, create it
    $sql = "INSERT INTO wallet (user_id, wallet_balance) VALUES (?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// Separate deposit logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deposit'])) {
    include 'deposit.php';
}

// Fetch updated wallet balance
$sql = "SELECT wallet_balance FROM wallet WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$wallet_balance = $row ? $row['wallet_balance'] : 9.00;
$stmt->close();

// Get complete transaction history (both sent & received) with sender and receiver usernames
$sql = "
SELECT 
    t.*,
    sender.usernames AS sender_name,
    receiver.usernames AS receiver_name
FROM 
    transactions t
LEFT JOIN 
    users sender ON t.sender_id = sender.user_id
LEFT JOIN 
    users receiver ON t.receiver_id = receiver.user_id
WHERE 
    t.sender_id = ? OR t.receiver_id = ?
ORDER BY 
    t.timestamp DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$transactions = $stmt->get_result();

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Customer/customerNavbar.php';

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
                <td><?php echo htmlspecialchars($row['sender_name']); ?></td>
                <td><?php echo htmlspecialchars($row['receiver_name']); ?></td>
                <td><?php echo ucfirst(htmlspecialchars($row['type'])); ?></td>
                <td>RM <?php echo number_format($row['amount'], 2); ?></td>
                <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>