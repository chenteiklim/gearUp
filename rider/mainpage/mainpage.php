
<?php
$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



mysqli_select_db($conn, $dbname); 

session_start();
if (!isset($_SESSION['username'])) {
  echo "<h1>This Website is Not Accessible</h1>";
  echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
  exit;  // Stop further execution of the script
}
    

mysqli_select_db($conn, $dbname);
$username=$_SESSION['username'];
?>
            <head>
            <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Product</title>
                <link rel="stylesheet" href="mainpage.css">
            </head>

            <div id="navContainer"> 
                <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
                <button class="button" id="home">Trust Toradora</button>
                <form action="../login/logout.php" method="POST">
                <button type="submit" id="logout" class="button">Log Out</button>
                </form> 
            </div>
            <div>

            </div>
            <div id='content'>
            <div>
                <p id='title'>Rider Mainpage </p>
                <img id='gadget' src="../../assets/deco.png" alt="">
            </div>
            <div id="container">
                <div id="messageContainer"></div>                  
            
                <div class='product'>
                    <button id="product" class="button"><?php echo 'Order' ?></button>
                        <div class="dropdowns">
                            <button onclick="location.href='../order/order.php'">Order Queue</button>
                        </div>
                    </div>
            </div>

   

   