<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

session_start();
$email = $_SESSION['email'] ?? null;
if (!$email) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;  // Stop further execution of the script
}


if (isset($_POST['submit'])) {
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

    $passwords = $_POST['password'];
    $passwords2 = $_POST['password2'];
  

    // Check if passwords match
    if ($passwords !== $passwords2) {
        header("Location: resetPwdForm.html?success=1");
    }
    
    // Check minimum length
    else if (strlen($passwords) < 10) {
        header("Location: resetPwdForm.html?success=2");
    }

    // Check for at least 4 special characters
    else if (preg_match_all('/[\W_]/', $passwords) < 4) {
        header("Location: resetPwdForm.html?success=3");
    }

    // Check for at least one uppercase letter
    else if (!preg_match('/[A-Z]/', $passwords)) {
        header("Location: resetPwdForm.html?success=4");
    }

    // Check for at least one lowercase letter
    else if (!preg_match('/[a-z]/', $passwords)) {
        header("Location: resetPwdForm.html?success=5");
    }

    // Check for at least one number
    else if (!preg_match('/[0-9]/', $passwords)) {
        header("Location: resetPwdForm.html?success=6");
    }

    else if (containsCommonSequence($passwords, $commonLowerSequences, $commonUpperSequences)) {
        header("Location: resetPwdForm.html?success=7");
    }
 
    else{
       // Step 1: Get user_id
        $sql = "SELECT user_id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo "User not found.";
            exit;
        }

        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];

        // Step 2: Check if reset_password_status is pending
        
        $sql = "SELECT * FROM email_verification_code WHERE user_id = ? AND reset_password_status = 'pending'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Step 3: Update password
            $hashedPassword = password_hash($passwords, PASSWORD_BCRYPT);
            $sql = "UPDATE users SET passwords = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $hashedPassword, $email);
            $stmt->execute();

            // Step 4: Clear reset status
            $sql = "UPDATE email_verification_code 
            SET reset_password_status = NULL, change_password_code = NULL 
            WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            header("Location: /inti/gearUp/Customer/login/login.php?success=4");
            exit;
        } else {
        header("Location: resetPwdForm.html?success=8");
            exit;
        }
    }
} 

$conn->close();
?>

        
   

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gearUp</title>
    <link rel="stylesheet" href="resetPwdForm.css">
   
</head>
<body>
<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button id="logoName" class="navButton" onclick="window.location.href = '../mainpage/customerMainpage.php';">GearUp</button>
</div>
</div>
    <div id="container">
        <div id="title">
          Reset Password
        </div>
        <form action="resetPwdForm.php" method="post">
         
          <div id="emailContainer">
            <input type="password" id="password" placeholder="Enter new password" name="password" required autocomplete="off">
            <button id="show" type="button">Show</button>
          </div>
        
          <div id="passwordContainer">
              <input type="password" id="password2" placeholder="Enter new password again" name="password2" required autocomplete="off">
              <button id="show2" type="button">Show</button>
            </div>
    
        <div id="errorContainer"></div>
        <div id="signUpContainer">
          <input id="signUpBtn" class="button" type="submit" name="submit" value="Reset Now">
          <hr>
                 
        </div>
      </form>
</div>
<script src="resetPwdForm.js"></script>
  </body>
</html>
   