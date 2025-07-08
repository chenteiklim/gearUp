<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['adminUsername'])) {
    echo "Access Denied.";
    exit;
}
$username= $_SESSION['adminUsername'];
$sql = "
SELECT 
    t.transaction_id,
    t.sender_id,
    u1.usernames AS sender_name,
    t.receiver_id,
    u2.usernames AS receiver_name,
    t.type,
    t.amount,
    t.timestamp
FROM transactions t
LEFT JOIN users u1 ON t.sender_id = u1.user_id
LEFT JOIN users u2 ON t.receiver_id = u2.user_id
ORDER BY t.timestamp DESC
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Transaction Records</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin: 20px auto; }
         #container {
            margin-left: 300px;
            padding: 20px;
        }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #f2f2f2; }
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; }
    </style>
</head>
<body>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php'; ?>
<div id= 'container'>

<h1>💳 Transaction Records</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Sender</th>
        <th>Receiver</th>
        <th>Type</th>
        <th>Amount (RM)</th>
        <th>Timestamp</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['transaction_id'] ?></td>
            <td><?= htmlspecialchars($row['sender_name'] ?? 'Unknown') ?></td>
            <td><?= htmlspecialchars($row['receiver_name'] ?? 'Unknown') ?></td>
            <td><?= htmlspecialchars($row['type']) ?></td>
            <td><?= number_format($row['amount'], 2) ?></td>
            <td><?= $row['timestamp'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>

</div>
</body>
</html>