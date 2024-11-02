<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
mysqli_select_db($conn, $dbname); 

session_start();

// Check if the session variables are set

// If email or backupEmail is not set, display an error message and exit
if (!isset($_SESSION['emailAdmin']) || !$_SESSION['isLoginAdmin']) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;  // Stop further execution of the script
}
else{
?>
<!DOCTYPE html>
<html lang="en">

<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="mainpage.css">


</head>

<body>
    <div id="navContainer"> 
        <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
        <button class="button" id="home">Computer Shop</button>
        <form action="../login/logout.php" method="POST">
      <button type="submit" id="logout" class="button">Log Out</button>
    </form> 
    </div>
    
    <div id="container">
        <div id="messageContainer"></div>
        
        <div class="view">
            <button id="view" class="btn">View User</button>
        </div>
        <div class="approve">
            <button id="approve" class="btn">View Seller</button>
        </div>
    </div>
    
</body>
<script src="mainpage.js"></script>

</html>
<?php

}
?>