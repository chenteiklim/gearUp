<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();
$username = $_SESSION['username'];
?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/Customer/customerNavbar.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
       
      
.container {
background-color: #e8e8e8;
width:430px;
margin-top:20px;
margin-left:500px;
padding: 16px;
height: 520px;
}

  h1{
    text-align:center;
  }
  h2{
    font-weight:normal;
    text-align:center;
  }
  p{
    text-align: center;
  }
    </style>
</head>
<body>
    <div class="container">     
       
        <h1>Thank you for purchasing from GearUp</h1>
        <br>
        <h2>Your order will be arrived soon!!!</h2>
        <br>
        <h3>Any issue regarding the merchandise or order can contact below:</h4>
        <p>Kelvin</p>
        <p>gearUp@hotmail.com</p>
        <p>012-1234567</p>
        <h2>3 month waranty</h2>
    </div>
</body>
</html>
