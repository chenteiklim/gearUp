<?php
if (!isset($username)) {
    die("Unauthorized access.");
}

// Check seller status
$stmt = $conn->prepare("SELECT s.status FROM users u JOIN seller s ON u.user_id = s.user_id WHERE u.usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    die("Unauthorized access. Seller not found.");
}

$status = $result->fetch_assoc()['status'];

if ($status !== 'approved') {
    die("Unauthorized access. Seller approval required.");
}

$sql = "SELECT user_id FROM users WHERE usernames = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
    // Now you can use $user_id for further processing
} else {
    // User not found (handle accordingly)
    echo "User not found.";
}

$amount = 50; // Fixed deposit amount

// Update wallet balance
$sql = "UPDATE wallet SET wallet_balance = wallet_balance + ? WHERE user_id = ?";
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
