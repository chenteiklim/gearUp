<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gearUp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
mysqli_select_db($conn, $dbname); 

session_start();

// Check if the session variables are set
$email = $_SESSION['email'] ?? null;

if (!$email) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;  // Stop further execution of the script
}

// Concatenate primary email verification code input
$primaryCode = implode('', $_POST['primaryCode']);

// Retrieve the hashed codes from the database
$sql = "SELECT ChangePwdEmailCode FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($hashedEmailCode);
$stmt->fetch();

if ($stmt->num_rows > 0) {
    // Verify the primary email code
    if (password_verify($primaryCode, $hashedEmailCode)) {
        $updateSql = "UPDATE users SET ChangePwdEmailCode = 'pending' WHERE email = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("s", $email);
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
    
    
        