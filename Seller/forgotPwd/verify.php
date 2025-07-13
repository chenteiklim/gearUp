<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gearUp</title>
    <link rel="stylesheet" href="verify.css">
</head>
<body>


<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button id="logoName" class='navButton'>GearUp</button>
</div>
    
        <div class='container'>
            <div id="title">Reset Password</div>
            
            <?php
            
            
            session_start();
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                
                echo "<p id='text1'>If these email exist, enter the verification codes sent to <span class='red'>$email</span> below to verify your account.</p>";
                
            ?>
              <form action="verifyCode.php" method="POST">
                <!-- Primary Email Verification Code -->
                <div>
                    <label for="primaryCode" id="primaryCode"> Verification Code:</label>
                    <input type="text" id="primaryCode1" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode2" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode3" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode4" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode5" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode6" name="primaryCode[]" maxlength="1" required>
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