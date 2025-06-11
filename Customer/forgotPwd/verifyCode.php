<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

// Check if the session variable is set
$email = $_SESSION['email'] ?? null;

if (!$email) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;
}

// Step 1: Get user_id from users table
$sql = "SELECT user_id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "User not found.";
    exit;
}

$row = $result->fetch_assoc();
$user_id = $row['user_id'];

// Step 2: Get submitted code
$primaryCode = implode('', $_POST['primaryCode'] ?? []);

// Step 3: Get email code from DB
$sql = "SELECT changePasswordCode FROM email_verification_code WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // use "i" for integer
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "Verification code not found.";
    exit;
}

$stmt->bind_result($changePasswordCode);
$stmt->fetch();

// Step 4: Verify
if ($primaryCode === $changePasswordCode) {
    header("Location: resetPwdForm.html");
    exit;
} else {
    header("Location: verify.php?success=1");
    exit;
}
?>