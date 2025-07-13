<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

// Validate session
$email = $_SESSION['email'] ?? null;
$username = $_SESSION['username'] ?? null;

if (!$email || !$username) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;
}

// Get 6-digit code from input fields
$primaryCode = implode('', $_POST['primaryCode']);

// Step 1: Get the user_id
$stmt = $conn->prepare("
    SELECT user_id 
    FROM users 
    WHERE email = ? AND usernames = ? AND status = 'pending'
    ORDER BY created_at DESC 
    LIMIT 1
");
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found.";
    exit;
}

$user_id = $user['user_id'];

// Step 2: Get the latest pending email verification code
$stmt2 = $conn->prepare("
    SELECT code, created_at 
    FROM email_verification_code 
    WHERE user_id = ? AND registration_status = 'pending' 
    ORDER BY created_at DESC 
    LIMIT 1
");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$row = $result2->fetch_assoc();
$stmt2->close();

if ($row) {
    $codeCreatedAt = strtotime($row['created_at']);
    $now = time();
    $expirySeconds = 600; // 10 minutes

    if (($now - $codeCreatedAt) > $expirySeconds) {
        // Code expired
        $expireStmt = $conn->prepare("UPDATE email_verification_code SET registration_status = 'expired' WHERE user_id = ? AND code = ?");
        $expireStmt->bind_param("is", $user_id, $row['code']);
        $expireStmt->execute();
        $expireStmt->close();

        header("Location: checkRegister.php?success=3"); // Code expired
        exit;
    }
}

if ($row && $row['code'] === $primaryCode) {
    // Step 3: Mark the code as used
    $stmt3 = $conn->prepare("UPDATE email_verification_code SET registration_status = 'used', code = NULL WHERE user_id = ? AND code = ?");
    $stmt3->bind_param("is", $user_id, $primaryCode);
    $stmt3->execute();
    $stmt3->close();

    // Step 4: Check if the email is already registered by another user
    $stmt4 = $conn->prepare("
        SELECT 1 
        FROM users 
        WHERE email = ? AND status = 'registered' AND user_id != ?
    ");
    $stmt4->bind_param("si", $email, $user_id);
    $stmt4->execute();
    $result4 = $stmt4->get_result();
    if ($result4->num_rows > 0) {
        $stmt4->close();
        // Someone else already registered with this email — block current registration
        header("Location: ../login/login.php?success=2");
        exit;
    }
    $stmt4->close();

    // No conflict – mark user as registered
    $updateStmt = $conn->prepare("UPDATE users SET status = 'registered' WHERE user_id = ?");
    $updateStmt->bind_param("i", $user_id);
    $updateStmt->execute();
    $updateStmt->close();

    // Optional cleanup: remove other pending users with same email
    $cleanupStmt = $conn->prepare("DELETE FROM users WHERE email = ? AND status = 'pending' AND user_id != ?");
    $cleanupStmt->bind_param("si", $email, $user_id);
    $cleanupStmt->execute();
    $cleanupStmt->close();

    header("Location: ../mainpage/customerMainpage.php");
    exit;
} else {
    // Invalid code
    header("Location: checkRegister.php?success=1");
    exit;
}
?>