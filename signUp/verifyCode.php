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




// Concatenate primary email verification code input
$primaryCode = implode('', $_POST['primaryCode']);

// Concatenate backup email verification code input
$backupCode = implode('', $_POST['backupCode']);

// Retrieve the hashed codes from the database
$sql = "SELECT emailCode, backupEmailCode FROM users WHERE email = ? AND backupEmail = ?";
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
        $updateSql = "UPDATE users SET emailCode = '1', backupEmailCode = '1' WHERE email = ? AND backupEmail = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ss", $email, $backupEmail);
        if ($updateStmt->execute()) {
            header("Location: ../homepage/mainpage.php");
        } else {
            echo "Failed to update verification status. Please try again.";
        }
    } else {
        // Codes are invalid
        echo "Invalid verification codes. Please try again.";
    }
} else {
    // User not found
    echo "User not found.";
}
?>