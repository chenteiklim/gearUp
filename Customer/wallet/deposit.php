<?php
if (!isset($username)) {
    die("Unauthorized access.");
}

$amount = 50; // Fixed deposit amount

// Update wallet balance
$sql = "UPDATE wallet SET wallet_balance = wallet_balance + ? WHERE usernames = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ds", $amount, $username);
$stmt->execute();

// Record deposit transaction
$sql = "INSERT INTO transactions (sender_name, receiver_name, type, amount, timestamp) 
        VALUES (?, ?, 'deposit', ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssd", $username, $username, $amount);
$stmt->execute();

// Redirect to prevent form resubmission
header("Location: wallet.php");
exit;
