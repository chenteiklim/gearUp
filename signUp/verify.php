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
    <div id="container">
        <div id="bigTitle">
            <img id="logoImg" src="../assets/icon.png" alt="" srcset="">
            <div id="fanime">Natural</div>  
            <div id="help">Get help</div>  
        </div>
    
        <div id="purple_container">
            <div id="title">Register</div>
            
            <?php
            if (isset($_GET['email'])) {
                $email = htmlspecialchars($_GET['email']);
                echo "<p id='text1'>Your email <span id='email'> $email </span> has been verified successfully</p>";
            } 
            else if (isset($_GET['backupEmail'])) {
                $email = htmlspecialchars($_GET['backupEmail']);
                $backupEmail = htmlspecialchars($_GET['backupEmail']);
                echo "<p id='text1'>Your email <span id='email'> $backupEmail </span> has been verified successfully</p>";
            } else {
                echo "<p id='text1'>Email verification failed.</p>";
            }
            ?>
        </div>  
    </div>

    <script src="checkRegister.js"></script>
</body>
</html>