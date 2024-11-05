
<?php

$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

session_start();
mysqli_select_db($conn, $dbname);
$username = $_SESSION['username'] ?? null;

if (!$username) {
  echo "<h1>This Website is Not Accessible</h1>";
  echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
  exit;  // Stop further execution of the script
}

$sql = "SELECT * FROM users WHERE usernames = '$username'";
$result = $conn->query($sql);
    
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $role = $row['role'];
  }


?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="seller.css">


</head>

<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Pit Stop</button>

    <button class="button" id="name"><?php echo $username ?></button>
    <form action="../login/logout.php" method="POST">
      <button type="submit" id="logout" class="button">Log Out</button>
    </form> 
</div>
<div>

</div>
<div id='content'>
  <div>
  <p id='title'>Want to be a seller? Boost your income by providing best quality product and service. Request from admin now!</p>
  <img id='gadget' src="../../assets/deco.png" alt="">
  </div>
  <div id="container">
    <div id="messageContainer"></div>
    <div class="faq">
    <button id="manual" class="btn"><?php echo 'How to be a seller' ?></button>
    </div>
    <div class="request">
    <button id="request" class="btn"><?php echo 'Request to be a seller' ?></button>
    </div>
   
</div>

</div>

<script>

var manual = document.getElementById("manual");

manual.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "sellerManual.php";
});
  
document.getElementById("home").addEventListener("click", function() {
    // Replace 'login.html' with the URL of your login page
    window.location.href = "../homepage/mainpage.php";
}); 

document.getElementById("request").addEventListener("click", function() {
    
    window.location.href = "../homepage/sellerRequest.php"; // Replace with your URL

});


</script>
  
   