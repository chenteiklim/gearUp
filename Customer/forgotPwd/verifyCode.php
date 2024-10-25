<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
mysqli_select_db($conn, $dbname); 

session_start();

// Check if the session variables are set
$email = $_SESSION['email'] ?? null;
$backupEmail = $_SESSION['backupEmail'] ?? null;

// If email or backupEmail is not set, display an error message and exit
if (!$email || !$backupEmail) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;  // Stop further execution of the script
}

// Concatenate primary email verification code input
$primaryCode = implode('', $_POST['primaryCode']);

// Concatenate backup email verification code input
$backupCode = implode('', $_POST['backupCode']);

// Retrieve the hashed codes from the database
$sql = "SELECT ChangePwdEmailCode, ChangePwdbackupEmailCode FROM users WHERE email = ? AND backupEmail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $backupEmail);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($hashedEmailCode, $hashedBackupEmailCode);
$stmt->fetch();

if ($stmt->num_rows > 0) {
    // Verify the primary email code
    if (password_verify($primaryCode, $hashedEmailCode) && password_verify($backupCode, $hashedBackupEmailCode)) {
        // Codes are valid, update the emailCode and backupEmailCode to 1
        $updateSql = "UPDATE users SET ChangePwdEmailCode = 'pending', ChangePwdbackupEmailCode = 'pending' WHERE email = ? AND backupEmail = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ss", $email, $backupEmail);
        if ($updateStmt->execute()) {
            header("Location: resetPwdForm.html");
            exit;  // Ensure script stops executing after redirect
        } else {
            echo "Failed to update verification status. Please try again.";
        }
    } else {
        header("Location: verify.php?success=1");
    }
} else {
    // User not found
    echo "User not found.";
}
?>
    
    
        