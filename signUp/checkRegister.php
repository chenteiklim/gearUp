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
            <div id="title">Register</div>
            
            <?php
            if (isset($_GET['email']) && isset($_GET['backupEmail'])) {
                $email = htmlspecialchars($_GET['email']);
                $backupEmail = htmlspecialchars($_GET['backupEmail']);
                echo "<p id='text1'>We've sent a verification email to <span class='red'>$email</span> and <span class='red'>$backupEmail</span>. Click the link in the email and backupEmail to verify your account.<br>
                        If you don't see the email and backupEmail, check other places it might be, like your junk, spam, social, or other folders.</p>";
            } else {
                echo "<p id='text1'>Email not provided.</p>";
            }
            ?>
            <button id="backBtn" class="button">Back to login</button>
        </div>  
    </div>

    <script src="checkRegister.js"></script>
</body>
</html>