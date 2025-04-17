<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();
if (!isset($_SESSION['riderUsername'])) {
  echo "<h1>This Website is Not Accessible</h1>";
  echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
  exit;  // Stop further execution of the script
}
    
$username=$_SESSION['riderUsername'];
?>
            <head>
            <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Product</title>
                <link rel="stylesheet" href="mainpage.css">
            </head>
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/riderNavbar.php';?>

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

   

   