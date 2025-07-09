<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>gearUp</title>
    <link rel="icon" href="../assets/icon.png" sizes="32x32" type="image/jpg">
    <link rel="stylesheet" href="checkRegister.css">
</head>
<body>
<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button id="logoName" class='navButton'>GearUp</button>

</div>
    
        <div id="container">
            <div id="title">Email Verification</div>
            
            <?php
            
            
            session_start();
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $username=$_SESSION['username'];
                echo "<p id='text1'>If these email exist, enter the verification codes sent to <span class='red'>$email</span> below to verify your account.</p>";
                
            ?>
              <form action="verifyCode.php" method="POST">
                <!-- Primary Email Verification Code -->
                <div id ='code'>
                    <label for="primaryCode" id="primaryCode">  Verification Code:</label>
                    <input type="text" id="primaryCode1" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode2" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode3" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode4" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode5" name="primaryCode[]" maxlength="1" required>
                    <input type="text" id="primaryCode6" name="primaryCode[]" maxlength="1" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="button" id='verifyBtn'>Verify Account</button>
            </form>
            <div id="messageContainer"></div>

            <?php
            }else {
                echo "<p id='text1'>Email not provided. Please Register first.</p>";
            }
            ?>
          
        </div>  

    <script src="checkRegister.js"></script>
</body>
</html>