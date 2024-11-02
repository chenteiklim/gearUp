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
require '../../vendor/autoload.php'; // Include Composer's autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $_SESSION['email']=$email;  

  
    mysqli_select_db($conn, $dbname); 

    $sql2 = "SELECT * FROM superuser WHERE email = ?";
    $stmt = $conn->prepare($sql2);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows <= 0) {
        // Redirect with an error message
        header("Location: verifyForgotPwd.html?success=1");
        exit();
    }

    $ChangePwdEmailCode = rand(100000, 999999); // 6-digit code for primary email
    $hashedChangePwdEmailCode = password_hash($ChangePwdEmailCode, PASSWORD_BCRYPT);

    $sql = "UPDATE superuser SET ChangePwdEmailCode = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashedChangePwdEmailCode, $email);
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
             $mail->setFrom('Example@computerShop.com', 'Testing');
             $mail->isHTML(true);
             $mail->Subject = 'Email Verification';
             $mail->addAddress($email);
             $mail->Body = "<p>Below is used for course assignment only, please ignore this email if you are wrongly received it</p>
             <p>Ref: $ChangePwdEmailCode </p>";
             $mail->send();
             header("Location: verify.php");
         
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