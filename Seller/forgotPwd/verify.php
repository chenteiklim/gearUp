<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanime</title>
    <link rel="icon" href="../assets/icon.png" sizes="32x32" type="image/jpg">
    <link rel="stylesheet" href="verify.css">
</head>
<body>
<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Computer Shop</button>
    <button id="login" class="button">Log in</button>
    <button class="button" id="register">Sign Up</button>

</div>
    
        <div id="purple_container">
            <div id="title">Reset Password</div>
            
            <?php
            
            
            session_start();
            if (isset($_SESSION['email']) && isset($_SESSION['backupEmail'])) {
                $email = $_SESSION['email'];
                $backupEmail = $_SESSION['backupEmail'];
                echo "<p id='text1'>If these email exist, enter the verification codes sent to <span class='red'>$email</span> and <span class='red'>$backupEmail</span> below to verify your account.</p>";
                
            ?>
              <form action="verifyCode.php" method="POST">
                <!-- Primary Email Verification Code -->
                <div>
                    <label for="primaryCode" id="primaryCode"> <?php echo $email; ?> Verification Code:</label>
                    <input type="text" id="primaryCode1" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode2" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode3" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode4" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode5" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode6" name="primaryCode[]" maxlength="1" required>
                </div>

                <!-- Backup Email Verification Code -->
                <div>
                    <label for="backupCode" id='backupCode'><?php echo $backupEmail; ?> Verification Code:</label>
                    <input type="text" id="backupCode1" name="backupCode[]" maxlength="1" required>
                    <input type="text" id="backupCode2" name="backupCode[]" maxlength="1" required>
                    <input type="text" id="backupCode3" name="backupCode[]" maxlength="1" required>
                    <input type="text" id="backupCode4" name="backupCode[]" maxlength="1" required>
                    <input type="text" id="backupCode5" name="backupCode[]" maxlength="1" required>
                    <input type="text" id="backupCode6" name="backupCode[]" maxlength="1" required>
                </div>
                <div id="errorContainer"></div>

                <!-- Submit Button -->
                <button type="submit" class="button" id='verifyBtn'>Verify Account</button>
            </form>
            <?php
            }else {
                echo "<p id='text1'>Email not provided.</p>";
            }
            ?>
          
        </div>  

    <script src="verify.js"></script>
</body>
</html>