<?php
if (!isset($username)) {
    die("Unauthorized access.");
}

$amount = 50; // Fixed deposit amount

// Update wallet balance
$sql = "UPDATE wallet SET wallet_balance =
 wallet_balance + ? WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ds", $amount, $user_id);
$stmt->execute();

// Record deposit transaction
$sql = "INSERT INTO transactions (sender_id, receiver_id, type, amount, timestamp) 
        VALUES (?, ?, 'deposit', ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssd", $user_id, $user_id, $amount);
$stmt->execute();

// Redirect to prevent form resubmission
header("Location: wallet.php");
exit;
