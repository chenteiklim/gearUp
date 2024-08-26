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
  $username = $_POST['username'];
  $email = $_POST['email'];
  $backupEmail = $_POST['backupEmail'];
  $passwords = $_POST['passwords'];
  $confirm_password = $_POST['confirm_password'];
  $address=$_POST['address'];
  $_SESSION['address'] = $address;


    mysqli_select_db($conn, $dbname); 

    
    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        header("Location: register.html?success=1");
        exit();
    }
     // Check if backup email already exists
     $checkEmail2 = "SELECT * FROM users WHERE backupEmail = '$backupEmail'";
     $result = $conn->query($checkEmail2);
     if ($result->num_rows > 0) {
        header("Location: register.html?success=2");
         exit();
     }

   


    // Check if password matches confirm password
    if ($passwords != $confirm_password) {
        header("Location: register.html?success=3");
        exit();
    } 

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.html?success=4");
        exit();
    }
    // Validate email format
    if (!filter_var($backupEmail, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.html?success=5");
        exit();
    }


    // Hash the password
    $hashed_password = password_hash($passwords, PASSWORD_BCRYPT);

    $emailCode = rand(100000, 999999); // 6-digit code for primary email
    $backupEmailCode = rand(100000, 999999); // 6-digit code for backup email
    $hashedEmailCode = password_hash($emailCode, PASSWORD_BCRYPT);
    $hashedBackupEmailCode = password_hash($backupEmailCode, PASSWORD_BCRYPT);


    // Insert user into the database with the generated token
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
            header("Location: checkRegister.php?email=" . urlencode($email) . "&backupEmail=" . urlencode($backupEmail));
        
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

