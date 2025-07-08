<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();


require '../../vendor/autoload.php'; // Include Composer's autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $_SESSION['email']=$email;  
    
    $sql2 = "SELECT user_id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql2);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stmt = $conn->prepare($sql2);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows <= 0) {
        header("Location: verifyForgotPwd.php?success=1");
        exit();
    }

    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];  // store user ID
    $status = 'pending';
    $change_password_code = rand(100000, 999999); // 6-digit code

    $sql = "UPDATE email_verification_code SET change_password_code = ?, 
    reset_password_status = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $change_password_code, $status, $user_id);  

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
            $mail->setFrom('testing@gearUp.com', 'testing');
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
        
            // Send to primary email
            $mail->addAddress($email);
            $mail->Body = "<p>Below is used for course assignment only, please ignore this email if you are wrongly received it</p>
            <p>Ref: $change_password_code  </p>";
            $mail->send();
             header("Location: verify.php");
             exit();

         
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gearUp</title>
    <link rel="stylesheet" href="verifyForgotPwd.css">
   
</head>
<body>

<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button id="logoName" class='navButton' onclick="window.location.href = '../mainpage/customerMainpage.php';">GearUp</button>
</div>

<form action="verifyForgotPwd.php" method="post">
    <div class="container">
        <div id="title">Email Verification</div>

        <div id="emailContainer">
            <input type="email" placeholder="email address" name="email" required autocomplete="off">
        </div>

        <div id="messageContainer"></div>

        <div id="signUpContainer">
            <input id="signUpBtn" class="button" type="submit" name="submit" value="Next">
        </div>
    </div>
</form>

<script src="verifyForgotPwd.js"></script>
</body>
</html>
   