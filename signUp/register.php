<?php

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gadgetShop";
// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
require '../vendor/autoload.php'; // Include Composer's autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if (isset($_POST['submit'])) {
  $usernames = $_POST['username'];
  $email = $_POST['email'];
  $backupEmail = $_POST['backupEmail'];
  $passwords = $_POST['passwords'];
  $confirm_password = $_POST['confirm_password'];
  $address=$_POST['address'];
  $_SESSION['address'] = $address;
    mysqli_select_db($conn, $dbname); 

    $sql = "SELECT * FROM users WHERE usernames = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usernames);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Redirect with an error message
        header("Location: register.html?success=1");
        exit();
    }

    $sql2 = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql2);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Redirect with an error message
        header("Location: register.html?success=2");
        exit();
    }


    if ($email == $backupEmail) {
        header("Location: register.html?success=3");
        exit();
    } 

    
    // Check if password matches confirm password
    if ($passwords != $confirm_password) {
        header("Location: register.html?success=4");
        exit();
    } 

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.html?success=5");
        exit();
    }
    // Validate email format
    if (!filter_var($backupEmail, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.html?success=6");
        exit();
    }

    // Check minimum length
    if (strlen($passwords) < 10) {
        header("Location: register.html?success=7");
    }

    // Check for at least 2 special characters
    // Check for at least 2 special characters
    if (preg_match_all('/[\W_]/', $passwords) < 2) {
        return false;
    }

    // Check for at least one uppercase letter
    if (!preg_match('/[A-Z]/', $passwords)) {
        return false;
    }

    // Check for at least one lowercase letter
    if (!preg_match('/[a-z]/', $passwords)) {
        return false;
    }

    // Check for at least one number
    if (!preg_match('/[0-9]/', $passwords)) {
        return false;
    }

    // Check against a list of common passwords (add more if needed)
    $commonPasswords = [
        '123456', 'password', '123456789', 'qwerty', 'abc123', '1234567', 'password1', '12345678', '1234567', '123456', '12345', '123123', '1q2w3e4r', 'admin', 'letmein'
    ];

    if (in_array($passwords, $commonPasswords)) {
        return false;
    }
    
    


    // Hash the password
    $hashed_password = password_hash($passwords, PASSWORD_BCRYPT);

    $emailCode = rand(100000, 999999); // 6-digit code for primary email
    $backupEmailCode = rand(100000, 999999); // 6-digit code for backup email
    $hashedEmailCode = password_hash($emailCode, PASSWORD_BCRYPT);
    $hashedBackupEmailCode = password_hash($backupEmailCode, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (email, backupEmail, usernames, address, passwords, emailCode, backupEmailCode) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $email, $backupEmail, $username, $address, $hashed_password, $hashedEmailCode, $hashedBackupEmailCode);
    if ($stmt->execute()) {
        // Send verification email
        $mail = new PHPMailer(true);
        try {
            // Common settings
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;  // You can also use port 25, 465, or 587   
            $mail->Username = 'beb2839877c67c';  // Replace with your Mailtrap username
            $mail->Password = '42343f9bc18416';  // Replace with your Mailtrap password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use TLS
            $mail->setFrom('HappyMart@natural.com', 'Natural');
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
        
            // Send to primary email
            $mail->addAddress($email);
            $mail->Body = "<p>Your verification code for primary email {$email} is: <strong> $emailCode </strong></p>
            <p>Please enter this code on the verification page to verify your account.</p>";
            $mail->send();
            
            // Clear addresses for the next email
            $mail->clearAddresses();
            
            // Send to backup email
            $mail->addAddress($backupEmail);
            $mail->Body = "<p>Your verification code for backup email {$backupEmail} is: <strong> $backupEmailCode </strong></p>
            <p>Please enter this code on the verification page to verify your account.</p>";
            $mail->send();
            // header("Location: checkRegister.php?email=" . urlencode($email) . "&backupEmail=" . urlencode($backupEmail));
        
        } catch (Exception $e) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }

    } else {
        // Handle database insert error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
    }