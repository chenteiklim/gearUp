<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

require '../../vendor/autoload.php'; // Include Composer's autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if (isset($_POST['submit'])) {
    $usernames = trim($_POST['username']);    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize email
    // Encrypt email
    


    $passwords = $_POST['passwords']; // Validate and hash passwords, don't output directly
    
    $confirm_password = $_POST['confirm_password']; // Same as above

    $storeName = $_POST['storeName'];
    $description = $_POST['description'];
    $contact = $_POST['contactInfo'];
        
 // Define separate arrays for common sequences
 $commonLowerSequences = [
    '1234', '2345', '3456', '4567', '5678', '6789', '7890', '0123',
    '1111', '2222', '3333', '4444', '5555', '6666', '7777', '8888', '9999', '0000',
    'bbbb', 'cccc', 'dddd', 'eeee', 'ffff', 'gggg', 'hhhh', 'iiii', 'jjjj',
    'kkkk', 'llll', 'mmmm', 'nnnn', 'oooo', 'pppp', 'qqqq', 'aaaa',
    'rrrr', 'ssss', 'tttt', 'uuuu', 'vvvv', 'wwww', 'xxxx', 'yyyy', 'zzzz',
    'abcd', 'bcde', 'cdef', 'defg', 'efgh', 'fghi',
    'ghij', 'hijk', 'ijkl', 'jklm', 'klmn',
    'lmno', 'mnop', 'nopq', 'qrst', 'rstu',
    'stuv', 'tuvw', 'uvwx', 'vwxy', 'wxyz'
];

$commonUpperSequences = [
    'ABCD', 'BCDE', 'CDEF', 'DEFG', 'EFGH', 'FGHI', 'GHIJ', 'HIJK',
    'IJKL', 'JKLM', 'JKLMN', 'KLMO', 'LMNOP', 'MNOPQ', 'NOPQR', 'OPQRS', 'PQRST',
    'QRSTU', 'RSTUV', 'STUVW', 'TUVWX', 'UVWXY', 'VWXYZ','BBBB', 'CCCC', 'DDDD', 'EEEE', 'FFFF', 'GGGG', 'HHHH', 'IIII', 'JJJJ',
'KKKK', 'LLLL', 'MMMM', 'NNNN', 'OOOO', 'PPPP', 'QQQQ', 'AAAA'
];

// Function to check if password contains any common sequence
function containsCommonSequence($passwords, $lowerSequences, $upperSequences) {
    
    // Check against lowercase common sequences
    foreach ($lowerSequences as $sequence) {
        if (strpos($passwords, $sequence) !== false) {
            return true; // Match found in lower sequences
        }
    }

    // Check against uppercase common sequences
    foreach ($upperSequences as $sequence) {
        if (strpos($passwords, $sequence) !== false) {
            return true; // Match found in upper sequences
        }
    }

    return false; // No matches found in either array
}
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    
    $sql = "SELECT * FROM users WHERE usernames = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usernames);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Redirect with an error message
        header("Location: register.php?success=1");
        exit();
    }

    // Check if storeName already exists in seller table
    $sql = "SELECT * FROM seller WHERE storeName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $storeName);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Store name already taken
        header("Location: register.php?success=10");
        exit();
    }
        
    // Check if password matches confirm password
    else if ($passwords != $confirm_password) {
        header("Location: register.php?success=2");
        exit();
    } 

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?success=3");
        exit();
    }

    
    // Check minimum length
    else if (strlen($passwords) < 10) {
        header("Location: register.php?success=4");
        exit();
    }

    // Check for at least 1 special characters
    else if (preg_match_all('/[\W_]/', $passwords) < 1) {
        header("Location: register.php?success=5");
        exit();
    }

    // Check for at least one uppercase letter
    else if (!preg_match('/[A-Z]/', $passwords)) {
        header("Location: register.php?success=6");
        exit();
    }

    // Check for at least one lowercase letter
    else if (!preg_match('/[a-z]/', $passwords)) {
        header("Location: register.php?success=7");
        exit();
    }

    // Check for at least one number
    else if (!preg_match('/[0-9]/', $passwords)) {
        header("Location: register.php?success=8");
        exit();

    }

    else if (containsCommonSequence($passwords, $commonLowerSequences, $commonUpperSequences)) {
        header("Location: register.php?success=9");
        exit(); // Ensure no further script execution
    }
    
 
$role='seller';
$status='pending';
$hashed_password = password_hash($passwords, PASSWORD_BCRYPT);

// Step 1: Insert into user table
$stmt1 = $conn->prepare("INSERT INTO users (email,
 usernames, passwords, role, status) 
                        VALUES (?, ?, ?, ?, ?)");
$stmt1->bind_param("sssss", $email, $usernames, $hashed_password, $role, $status);
$success1 = $stmt1->execute();
$userId = $stmt1->insert_id;

// Step 2: Insert into seller table
$stmt2 = $conn->prepare("INSERT INTO seller (storeName, 
user_id, sellerName, description, contact, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt2->bind_param("ssssss", $storeName, $userId, $usernames,
 $description, $contact, $status);
$success2 = $stmt2->execute();


// Step 3: Insert into email_verification_code table
$emailCode = rand(100000, 999999);
$stmt3 = $conn->prepare("INSERT INTO email_verification_code (user_id, code,
 change_password_code, reset_password_status, registration_status ) 
                         VALUES (?, ?, ?, ?, ?)");
$stmt3->bind_param("iiiss", $userId, $emailCode, $change_passwword_code, 
$reset_password_status, $status);
$success3 = $stmt3->execute();
// Execute 
if ($success1 && $success2 && $success3) {
      $_SESSION['email'] = $email;
        $_SESSION['sellerUsername'] = $usernames;
        session_write_close(); // Force session to save now
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
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
            $mail->setFrom('testing@gearUp.com', 'testing');
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
        
            // Send to primary email
            $mail->addAddress($email);
            $mail->Body = "<p>Below is used for course assignment only, 
            please ignore this email if you are wrongly received it</p>
            <p>Ref: $emailCode</p>";
            $mail->send();
             header("Location: checkRegister.php");
             exit();
         
        } catch (Exception $e) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }

    } else {
        // Handle database insert error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }  
 
    $stmt1->close();
    $stmt2->close();
    $stmt3->close();
    $conn->close(); 
    } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Request Form</title>
  <link rel="stylesheet" href="register.css">
  
</head>
<body>

<div id="navContainer"> 
  <img id="logoImg" src="/inti/gearUp/assets/logo.jpg" alt="" srcset="">
  <button class="navButton" id="home">GearUp</button>
  <button class="navButton" id="login">Login</button>
</div>
<h1>Seller Registration</h1>

<form id="sellerRequestForm" action="register.php" method="POST" enctype="multipart/form-data">
  <label for="username">Username:</label>
<input type="text" name="username" required>

<label for="email">Email:</label>
<input type="email" name="email" required>

    <div class="password">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <input type="password" id="password" placeholder="Enter Password" name="passwords" required>
        <i class="fa fa-eye" onclick="togglePassword('password', this)"></i>
            </div> 
            <div class="password"> 
            <input type="password" id="password2" placeholder="Enter Password Again" name="confirm_password" required>
        <i class="fa fa-eye" onclick="togglePassword('password2', this)"></i>
            </div> 
      </div>
  <label for="storeName">Store Name:</label>
  <input type="text" id="storeName" name="storeName" required>

  <label for="description">Store Description:</label>
  <textarea id="description" name="description" rows="4" required></textarea>

  <label for="contactInfo">Contact Information (0xx-1234567):</label>
  <input type="text" id="contactInfo" name="contactInfo" required>
          <div id="errorContainer"></div>

<div id="privacyContainer">
            <label for="privacyCheck" id = 'privacytext'>
                I have read and agree to the 
                <a href="/inti/gearUp/Customer/signUp/privacy-policy.html" target="_blank">Privacy Policy</a> 
                and <a href="/inti/gearUp/Customer/signUp/termAndService.html" target="_blank">Terms of Service</a>.
            </label>
            <input type="checkbox" id="privacyCheck" name="privacyCheck" required>
  
        </div>
        <input id="register" class="button" type="submit" name="submit" value="Register">
  
</form>

</body>
</html>
<script src="register.js"></script>