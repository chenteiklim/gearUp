
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
$username = $_SESSION['username'] ?? null;


if (!$username) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;  // Stop further execution of the script
  }
?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
<style>
    
#container {
    margin-top: 100px;
    margin-left: 100px;
    background-color:#f4f4f4;
    width:300px;
    height:350px;
    display: flex;
    flex-direction: column;
    align-items:center;
    border-radius: 5px;
  }
  #content{
    margin-top: 50px;
    display: flex;
    margin-right: 180px;
  }
  #title {
    font-size: 20px;
    font-family: 'Roboto', sans-serif;
    font-weight: bold;
    color: #333;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    background: linear-gradient(90deg, #8840da, #0c0b4b);
    -webkit-background-clip: text;
    color: transparent;
    padding: 10px;
    margin-left: 100px;
    margin-bottom: 0px;
    letter-spacing: 1.5px;
    line-height: 1.6; /* Adds spacing between lines of text */
    text-transform: uppercase;
    transition: all 0.3s ease;
    width: 450px;
  }
  
      button:active {
        transform: scale(0.9);
        background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
      }
    
      #gadget{
        margin-top: 10px;
        width: 500px;
        height: 400px;
      }
      
      .button:active {
        transform: scale(0.9);
        background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
      }
      
  
    
      .customer{
        margin-top: 30px;
      }
  
      .seller{
          margin-top:30px;
      }
      
      #product{
          margin-top:30px;
      }
  
      .img{
        width:50px;
        height:50px;
      }
      #navContainer {
        display: flex;
        background-color: #BFB9FA;
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
        align-items:center;
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
    
    
      .message-container {
        
        background-color: rgba(0, 0, 0, 0.7);
        position: fixed;
        padding-left: 120px;
        padding-right: 120px;
        padding-top: 90px;
        padding-bottom: 90px;
        color: white;
        font-size: 30px;
        display: flex;
      align-items: center;
      justify-content: center;
      }
  
    
    .session{
      margin-top: 20px;
    }
    
        #home{
            margin-left: 10px;
        }
    #name{
        margin-left: 800px;
    }
    #logout{
      height: 80px;    
    }
    #logoImg{
        margin-top: 25px;
        width: 35px;
        height: 35px;
        border-radius: 5px;
        margin-left: 100px;
    }
    
        button:hover{
            transform: scale(0.9);
            background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
          }
    
          .seller, .product {
            position: relative;
            display: inline-block;
        }
        
        /* Style for the dropdowns */
        .dropdown, .dropdowns {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: white;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            min-width: 150px;
            z-index: 10;
        }
        
        /* Style for dropdown items */
        .dropdown button, .dropdowns button {
            background: none;
            border: none;
            color: black;
            padding: 10px;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }
        
        /* Hover effect for dropdown items */
        .dropdown button:hover, .dropdowns button:hover {
            background-color: #f1f1f1;
        }
  
        .product:hover .dropdowns {
          display: block;
      }
      
      
    /* Show dropdown on hover */
    .seller:hover .dropdown {
      display: block;
  }
    
</style>

</head>

<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Trust Toradora</button>

    <button class="button" id="name"><?php echo 'Guest Browsing' ?></button>
    <form action="../login/logout.php" method="POST">
      <button type="submit" id="logout" class="button">Log Out</button>
    </form> 
</div>
<div>

</div>
<div id='content'>
  <div>
  <p id='title'>Seller Center </p>
  <img id='gadget' src="../../assets/deco.png" alt="">
  </div>
  <div id="container">
    <div id="messageContainer"></div>
    <div class="seller">
        <button id="seller" class="button"><?php echo 'Seller Manual' ?></button>
        <div class="dropdown">
            <button onclick="location.href='sellerManual.php'">How to be a seller</button>
            <button onclick="location.href='sellerRequest.php'">Request to be seller</button>
        </div>
    </div>
</div>
  
</div>

</div>

<script>
    document.getElementById("home").addEventListener("click", function() {
    // Replace 'login.html' with the URL of your login page
    window.location.href = "../homepage/mainpage.php";
}); 
</script>

   