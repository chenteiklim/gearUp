
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
if (!isset($_SESSION['emailAdmin'])) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;  // Stop further execution of the script
}
$email=$_SESSION['emailAdmin'];
$selectNameQuery = "SELECT * FROM superuser WHERE email = '$email'";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
    $usernames = $row['username'];

}
    // Get the address value from the fetched row

?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="seller.css">

<style>
    body{
    display:flex;
    flex-direction:column;
    align-items:center;
  }
  #container {
    margin-top: 120px;
    margin-left: 100px;
    background-color:white;
      width:300px;
      height:240px;
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
    margin-left: 20px;
    margin-bottom: 0px;
    letter-spacing: 1.5px;
    line-height: 1.6; /* Adds spacing between lines of text */
    text-transform: uppercase;
    transition: all 0.3s ease;
    width: 450px;
  }
  
  .button {
      background-color: darkblue;
      color: white;
      cursor: pointer;
      width:200px;
      margin-left: 20px;
      padding-left: 30px;
      padding-right: 30px;
      padding-top: 10px;
      padding-bottom: 10px;
      font-size: 16px;
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
  .btn {
      background-color: black;
      color: white;
      cursor: pointer;
      width:200px;
      margin-left: 20px;
      padding-left: 30px;
      padding-right: 30px;
      padding-top: 10px;
      padding-bottom: 10px;
      font-size: 16px;
      }
      
      .btn:active {
        transform: scale(0.9);
        background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
      }
      
  
      body{
          background-color: bisque;
          width: 100%;
          height: 1400px;
      }
   
      .approve{
        margin-top: 40px;
      }
  
      .view{
          margin-top:40px;
      }
      
      #product{
          margin-top:40px;
      }
  
      .img{
        width:50px;
        height:50px;
      }
  
      .navContainer{
        display:flex;
        align-items:center;
        justify-content:center;
        background-color:black;
        width:100%;
        height:60px;
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
  
      html, body {
        margin: 0;
        padding: 0;
        width: 100%; /* Ensure full width */
        height: 100%; /* Ensure full height */
    }
    
    #navContainer {
        display: flex;
        background-color: black;
        width: 100%; /* Adjust width as needed */
        height: 80px; /* Adjust height as needed */
        
        /* Ensure it remains visible within the container */
      
      }
  
  
    .button {
        background-color: black;
        color: white;
        cursor: pointer;
        padding-left: 30px;
        padding-right: 30px;
        padding-top: 10px;
        padding-bottom: 10px;
        font-size: 12px;
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
    
    
    
      
</style>
</head>

<div id="navContainer"> 
    <img id="logoImg" src="../../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Pit Stop</button>

    <button class="button" id="name"><?php echo $usernames ?></button>
    <form action="../../login/logout.php" method="POST">
      <button type="submit" id="logout" class="button">Log Out</button>
    </form> 
</div>
<div>

</div>
<div id='content'>
  <div>
  <p id='title'>Manage Superuser </p>
  <img id='gadget' src="../../../assets/customer.jpg" alt="">
  </div>
  <div id="container">
    <div id="messageContainer"></div>
    <div class="add">
    <button id="add" class="btn"><?php echo 'Add Superuser' ?></button>
    </div>

    <div class="view">
    <button id="view" class="btn"><?php echo 'View Superuser' ?></button>
    </div>
</div>

</div>

<script>
   

document.getElementById('add').addEventListener('click', function() {
  window.location.href = 'register.php';
});


document.getElementById('view').addEventListener('click', function() {
  window.location.href = 'viewSuperuser.php';
});



 
document.getElementById('home').addEventListener('click', function() {
  window.location.href = '../../mainpage/mainpage.php';
});

 
</script>
  
   