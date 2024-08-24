<?php
$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "natural";

require '../vendor/autoload.php'; // Include Composer's autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $backupEmail = $_POST['backupEmail'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $address=$_POST['address'];
  $_SESSION['address'] = $address;
  $contact=$_POST['contact'];


    mysqli_select_db($conn, $dbname); 

    
    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        header("Location: signUp.html?success=1");
        exit();
    }
     // Check if backup email already exists
     $checkEmail2 = "SELECT * FROM users WHERE backupEmail = '$backupEmail'";
     $result = $conn->query($checkEmail2);
     if ($result->num_rows > 0) {
         header("Location: signUp.html?success=2");
         exit();
     }

    // Check if email already exists
    $checkContact = "SELECT * FROM users WHERE contact = '$contact'";
    $result = $conn->query($checkContact);
    if ($result->num_rows > 0) {
        header("Location: signUp.html?success=3");
        exit();
    }


    // Check if password matches confirm password
    if ($passwords != $confirm_password) {
        header("Location: signUp.html?success=4");
        exit();
    } 

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: signUp.html?success=5");
        exit();
    }
    // Validate email format
    if (!filter_var($backupEmail, FILTER_VALIDATE_EMAIL)) {
        header("Location: signUp.html?success=6");
        exit();
    }


    // Hash the password
    $hashed_password = password_hash($passwords, PASSWORD_BCRYPT);

    // Generate a unique token for email verification
    $tokenEmail = bin2hex(random_bytes(50));
    $tokenBackupEmail = bin2hex(random_bytes(50));


    // Insert user into the database with the generated token
    $sql = "INSERT INTO users (email, backupEmail, contact, name, address, passwords, tokenEmail, tokenBackupEmail) VALUES (?, ?, ?, ?, ?, ?, ?, ?,)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $email, $backupEmail, $contact, $username, $address, $hashed_password, $tokenEmail, $tokenBackupEmail);
    if ($stmt->execute()) {
        // Send verification email
        $mail = new PHPMailer(true);
        try {
            // Common settings
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;  // You can also use port 25, 465, or 587   
            $mail->Username = '712b0751efb910';  // Replace with your Mailtrap username
            $mail->Password = '5edaa772730c5a';  // Replace with your Mailtrap password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Use TLS
            $mail->setFrom('HappyMart@natural.com', 'Natural');
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
        
            // Send to primary email
            $mail->addAddress($email);
            $mail->Body = '<p>Click the link below to verify your account:</p>
                           <p><a href="http://localhost/Natural/signUp/verifyAccount.php?tokenEmail=' . $tokenEmail . '">Verify email</a></p>';
            $mail->send();
            
            // Clear addresses for the next email
            $mail->clearAddresses();
            
            // Send to backup email
            $mail->addAddress($backupEmail);
            $mail->Body = '<p>Click the link below to verify your account:</p>
                           <p><a href="http://localhost/Natural/signUp/verify_account.php?tokenBackupEmail=' . $tokenBackupEmail . '">Verify backup email</a></p>';
            $mail->send();
        
        } catch (Exception $e) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        header("Location: checkRegister.php?email=" . urlencode($email) . "&backupEmail=" . urlencode($backupEmail));

    } else {
        // Handle database insert error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

