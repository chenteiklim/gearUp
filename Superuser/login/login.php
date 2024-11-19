<?php
$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";  

$conn = new mysqli($servername, $Username, $Password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

session_start();
require '../../vendor/autoload.php'; // Include Composer's autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $password = $_POST['passwords'];
  $_SESSION['emailAdmin'] = $email;
  if (!isset($_SESSION['emailAdmin'])) {
    die("Failed to store email in session. Please try again.");
}

  // Select database
  mysqli_select_db($conn, $dbname); 

  $sql = "SELECT * FROM superuser WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Fetch the user's data
    $row = $result->fetch_assoc();
    $hashed_password = $row['passwords'];
    $emailCode = rand(100000, 999999); // 6-digit code for primary email
    $hashedEmailCode = password_hash($emailCode, PASSWORD_BCRYPT);
    // Prepare the SQL statement to update the email_code for the given email
   

    if (!password_verify($password, $hashed_password)) {
      header("Location: login.html?success=1");
      exit(); // Stop further script execution
    } 
   
    else if (password_verify($password, $hashed_password)) {
      $sql = "UPDATE superuser SET emailCode = ? WHERE email = ?";
      $stmt = $conn->prepare($sql);
  
      // Check if the prepared statement was created successfully
      if ($stmt === false) {
          die('Prepare failed: ' . $conn->error);
      }
  
      // Bind parameters to the SQL query
      $stmt->bind_param("ss", $hashedEmailCode, $email);
      if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
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
              $mail->setFrom('testing@computerShop.com', 'testing');
              $mail->isHTML(true);
              $mail->Subject = 'Email Verification';
          
              // Send to primary email
              $mail->addAddress($email);
              $mail->Body = "<p>Below is used for course assignment only, please ignore this email if you are wrongly received it</p>
              <p>Ref: $emailCode</p>";
              $mail->send();
               header("Location: checkLogin.php");
               exit();
    
           
          } catch (Exception $e) {
              echo 'Mailer Error: ' . $mail->ErrorInfo;
          }
        } else {
            echo "No matching email found, or the email code is already up-to-date.";
        }
    } else {
        echo "Error updating data: " . $stmt->error;
    }
     
 
    } 
  }
  else {
    // No user found with the provided email
    header("Location: login.html?success=2");
    exit();
  }
  $stmt->close();
}
$conn->close();
?>