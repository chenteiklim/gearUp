<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fanime</title>
    <link rel="icon" href="../assets/icon.png" sizes="32x32" type="image/jpg">
    <link rel="stylesheet" href="checkRegister.css">
</head>
<body>
    <div id="container">
        <div id="bigTitle">
            <img id="logoImg" src="../assets/icon.png" alt="" srcset="">
            <div id="fanime">Natural</div>  
            <div id="help">Get help</div>  
        </div>
    
        <div id="purple_container">
            <div id="title">Email Verification</div>
            
            <?php
            if (isset($_GET['email']) && isset($_GET['backupEmail'])) {
                $email = htmlspecialchars($_GET['email']);
                $backupEmail = htmlspecialchars($_GET['backupEmail']);
                echo "<p id='text1'>Enter the verification codes sent to <span class='red'>$email</span> and <span class='red'>$backupEmail</span> below to verify your account.</p>";
            } else {
                echo "<p id='text1'>Email not provided.</p>";
            }
            
            ?>
            
            <form action="verifyCode.php" method="POST">
    <!-- Primary Email Verification Code -->
    <div>
        <label for="primaryCode">Primary Email Verification Code:</label>
        <input type="text" id="primaryCode1" name="primaryCode[]" maxlength="1" required>
        <input type="text" id="primaryCode2" name="primaryCode[]" maxlength="1" required>
        <input type="text" id="primaryCode3" name="primaryCode[]" maxlength="1" required>
        <input type="text" id="primaryCode4" name="primaryCode[]" maxlength="1" required>
        <input type="text" id="primaryCode5" name="primaryCode[]" maxlength="1" required>
        <input type="text" id="primaryCode6" name="primaryCode[]" maxlength="1" required>
    </div>

    <!-- Backup Email Verification Code -->
    <div>
        <label for="backupCode">Backup Email Verification Code:</label>
        <input type="text" id="backupCode1" name="backupCode[]" maxlength="1" required>
        <input type="text" id="backupCode2" name="backupCode[]" maxlength="1" required>
        <input type="text" id="backupCode3" name="backupCode[]" maxlength="1" required>
        <input type="text" id="backupCode4" name="backupCode[]" maxlength="1" required>
        <input type="text" id="backupCode5" name="backupCode[]" maxlength="1" required>
        <input type="text" id="backupCode6" name="backupCode[]" maxlength="1" required>
    </div>

    <!-- Hidden Fields -->
    <input type="hidden" name="email" value="<?php echo $email; ?>">
    <input type="hidden" name="backupEmail" value="<?php echo $backupEmail; ?>">

    <!-- Submit Button -->
    <button type="submit" class="button">Verify Account</button>
</form>


            <button id="backBtn" class="button">Back to login</button>
        </div>  
    </div>

    <script src="checkRegister.js"></script>
</body>
</html>