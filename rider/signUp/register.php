
<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

require '../../vendor/autoload.php'; // Include Composer's autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
session_start();

if (isset($_POST['submit'])) {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'); // Sanitize username
    $haveNotEncryptEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // Sanitize email

    // Include encryption helper
    include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/encryption_helper.php';

    // Encrypt email
    $email = openssl_encrypt($haveNotEncryptEmail, 'AES-256-CBC', $encryption_key, 0, $encryption_iv);
    $_SESSION['email'] = $email;
    $_SESSION['riderUsername'] = $username;
    $_SESSION['haveNotEncrypt'] = $haveNotEncryptEmail;
   
    $passwords = $_POST['passwords']; // Validate and hash passwords, don't output directly
    
    $confirm_password = $_POST['confirm_password']; // Same as above
    $_SESSION['username'] = $username;
    
    $district = htmlspecialchars($_POST['district'], ENT_QUOTES, 'UTF-8'); // Sanitize address


   
        
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
    // Convert the password to lowercase for checking against lower sequences
    
    
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
    
    // Check if password matches confirm password
    else if ($passwords != $confirm_password) {
        header("Location: register.html?success=4");
        exit();
    } 

    // Validate email format
    else if (!filter_var($haveNotEncryptEmail, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.html?success=5");
        exit();
    }
  

    // Check minimum length
    else if (strlen($passwords) < 10) {
        header("Location: register.html?success=7");
        exit();
    }

    // Check for at least 1 special characters
    else if (preg_match_all('/[\W_]/', $passwords) < 4) {
        header("Location: register.html?success=8");
        exit();

    }

    // Check for at least one uppercase letter
    else if (!preg_match('/[A-Z]/', $passwords)) {
        header("Location: register.html?success=9");
        exit();

    }

    // Check for at least one lowercase letter
    else if (!preg_match('/[a-z]/', $passwords)) {
        header("Location: register.html?success=10");
        exit();

    }

    // Check for at least one number
    else if (!preg_match('/[0-9]/', $passwords)) {
        header("Location: register.html?success=11");
        exit();

    }

    else if (containsCommonSequence($passwords, $commonLowerSequences, $commonUpperSequences)) {
        header("Location: register.html?success=12");
        exit(); // Ensure no further script execution

    }
    


    $hashed_password = password_hash($passwords, PASSWORD_BCRYPT);
    $emailCode = rand(100000, 999999); // 6-digit code for primary email
    $hashedEmailCode = password_hash($emailCode, PASSWORD_BCRYPT);
    $param1 = 0;
    $param2= 1;
   
    $sql = "INSERT INTO rider (username, email, passwords, state, emailCode, ChangePwdEmailCode, available) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $username, $email, $hashed_password, $district, $hashedEmailCode, $param1, $param2);
      echo $_SESSION['email']; 
      if (isset($_SESSION['email'])) {
        echo 'hello world';
      }

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
            $mail->setFrom('testing@TrustToradora.com', 'testing');
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
        
            // Send to primary email
            $mail->addAddress($haveNotEncryptEmail);
            $mail->Body = "<p>Below is used for course assignment only, please ignore this email if you are wrongly received it</p>
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
    <title>Document</title>
    <link rel="stylesheet" href="register.css">
    <link rel="icon" href="logo.jpg" type="image/jpg">
   
</head>
<body>

  
<div id="navContainer">
        <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
        <button class="button" id="home">Trust Toradora</button>
        <button class="button" id="register">Sign Up</button>

      </div>    
<form id="registerForm" action="register.php" method="post" enctype="multipart/form-data">
  <div class="container">
    <p id="title">Apply as a Rider</p>
    
    <input type="text" placeholder="Enter Full Name" name="username" required>

    <input type="email" placeholder="Enter Email" name="email" required>


    <label for="district">State:</label>
    <select id="district" name="district" required onchange="fillPostcode()">
        <option value="">Select State</option>
        <option value="Johor">Johor</option>
        <option value="Kedah">Kedah</option>
        <option value="Kelantan">Kelantan</option>
        <option value="Melaka">Melaka</option>
        <option value="Negeri Sembilan">Negeri Sembilan</option>
        <option value="Pahang">Pahang</option>
        <option value="Perak">Perak</option>
        <option value="Perlis">Perlis</option>
        <option value="Pulau Pinang">Pulau Pinang</option>
        <option value="Sabah">Sabah</option>
        <option value="Sarawak">Sarawak</option>
        <option value="Selangor">Selangor</option>
        <option value="Terengganu">Terengganu</option>
    </select>
    <div class="password">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<input type="password" id="password" placeholder="Enter Password" name="passwords" required>
<i class="fa fa-eye" onclick="togglePassword('password', this)"></i>
    </div> 
    <div class="password"> 
      <input type="password" id="password2" placeholder="Enter Password Again" name="confirm_password" required>
<i class="fa fa-eye" onclick="togglePassword('password2', this)"></i>
     </div> 

    <div id="errorContainer"></div>

    <div id="privacyContainer">
      <label for="privacyCheck" id="privacytext">
        I have read and agree to the 
        <a href="privacy-policy.html" target="_blank">Privacy Policy</a> 
        and <a href="termAndService.html" target="_blank">Terms of Service</a>.
      </label>
      <input type="checkbox" id="privacyCheck" name="privacyCheck" required>
    </div>

    <input id="register" class="button" type="submit" name="submit" value="Apply as Rider">
  </div>
</form>
    

  
    </body>
    <script src="register.js"></script>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="register.css">
    <link rel="icon" href="logo.jpg" type="image/jpg">
   
</head>
<body>
  

  <div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Trust Toradora</button>
    <button id="login" class="button">Log in</button>
</div>
    
    <form id="registerForm" action="register.php" method="post">
      <div class="container">
        <p id="title">
          Register
        </p>
        
        <input type="text" placeholder="Enter Nickname" name="username" required>

        <input type="text" placeholder="Enter Full Address. Eg: 1890, Jalan6, Taman lexus" name="address" required>

        <label for="state">State:</label>
        <select id="state" name="state" required >
            <option value="">Select State</option>
            <option value="Johor">Johor</option>
            <option value="Kedah">Kedah</option>
            <option value="Kelantan">Kelantan</option>
            <option value="Melaka">Melaka</option>
            <option value="Negeri Sembilan">Negeri Sembilan</option>
            <option value="Pahang">Pahang</option>
            <option value="Perak">Perak</option>
            <option value="Perlis">Perlis</option>
            <option value="Pulau Pinang">Pulau Pinang</option>
            <option value="Sabah">Sabah</option>
            <option value="Sarawak">Sarawak</option>
            <option value="Selangor">Selangor</option>
            <option value="Terengganu">Terengganu</option>
        </select>
      
     
        <input type="email" placeholder="Enter Email" name="email" required>
  
        <input type="password" id="password" placeholder="Enter Password" name="passwords" required>
        <button id="show">Show</button>
 
  
        <input type="password" id="password2" placeholder="Enter Password Again" name="confirm_password" required>
        <button id="show2">Show</button>
          <div id="errorContainer"></div>
          <div id="privacyContainer">
            <label for="privacyCheck" id = 'privacytext'>
                I have read and agree to the 
                <a href="privacy-policy.html" target="_blank">Privacy Policy</a> 
                and <a href="termAndService.html" target="_blank">Terms of Service</a>.
            </label>
            <input type="checkbox" id="privacyCheck" name="privacyCheck" required>
  
        </div>
  
        <input id="register" class="button" type="submit" name="submit" value="Register">
      </div>

    </form>
    

  
    </body>
    <script src="register.js"></script>
</html>
   