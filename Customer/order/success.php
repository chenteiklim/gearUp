<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();
$username = $_SESSION['username'];
?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/customerNavbar.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
       
      
.container {
background-color: white;
width:430px;
margin-top:20px;
margin-left:500px;
padding: 16px;
height: 520px;
}

#logoImg{
  width: 35px;
  height: 35px;
  border-radius: 5px;
  margin-left: 100px;
}
 .button {
  background-color: #BFB9FA;
  color: black;
  cursor: pointer;
  padding-left: 30px;
  padding-right: 30px;
  padding-top: 10px;
  padding-bottom: 10px;
  font-size: 14px;
  border: none;
  }

    
    button:active {
      transform: scale(0.9);
      background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
    }
    
#navContainer {
    display: flex;
    background-color: #BFB9FA;
    align-items: center;
    width: 100%; /* Adjust width as needed */
    height: 80px; /* Adjust height as needed */   
  }
  
  html, body {
    background-color: #add8e6;
    margin: 0;
    padding: 0;
    width: 100%; /* Ensure full width */
    height: 100%; /* Ensure full height */
    display:flex;
    flex-direction:column;
  }
  .button {
   background-color: #BFB9FA;
   width: 150px;
   color: black;
   cursor: pointer;
   padding-left: 30px;
   padding-right: 30px;
   padding-top: 10px;
   padding-bottom: 10px;
   font-size: 14px;
   border: none;
   }
   #name{
      margin-left: 400px;
  }
  #logout{
    margin-top:20px;
  }
  h1{
    text-align:center;
  }
  h2{
    font-weight:normal;
    text-align:center;
  }
    </style>
</head>
<body>
    <div class="container">     
       
        <h1>Thank you for purchasing from Trust Toradora</h1>
        <br>
        <h2>Your order will be arrived soon!!!</h2>
        <br>
        <h3>Any issue regarding the merchandise or order can contact below:</h4>
        <p>Lim Chen Teik</p>
        <p>chenteik_99@hotmail.com</p>
        <p>012-4820649</p>
        <h2>3 month waranty</h2>
    </div>
</body>
</html>
