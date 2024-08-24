<?php
$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verify password
if (isset($_POST['passwordConfirmation'])) {
    session_start();
    
    $newPassword = $_POST['password'];
    $newConfirmPassword = $_POST['confirm_password'];
  
    if ($newPassword != $newConfirmPassword) {
        header("Location: reset.html?success=6");
        exit();
    } else {
        $email = $_SESSION['email'];
        $sql = "SELECT password FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['password'];
            $updateSql = "UPDATE users SET password = '$newPassword' WHERE email = '$email'";
            
            if ($conn->query($updateSql) === TRUE) {
                header("Location: homepage.php?success=7");
                exit();
            } else {
                echo "Error updating password: " . $conn->error;
            }
        }
    }
}
?>