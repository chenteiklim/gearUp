
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
    <link rel="stylesheet" href="mainpage.css">


</head>

<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Pit Stop</button>

    <button class="button" id="name"><?php echo $usernames ?></button>
    <form action="../login/logout.php" method="POST">
      <button type="submit" id="logout" class="button">Log Out</button>
    </form> 
</div>
<div>

</div>
<div id='content'>
  <div>
  <p id='title'>Enhance Experiences, Streamline Operations – All in One Place </p>
  <img id='gadget' src="../../assets/deco.png" alt="">
  </div>
  <div id="container">
    <div id="messageContainer"></div>

    <div class="session">
    <button id="session" class="btn"><?php echo 'Manage Superuser ' ?></button>
    </div>

    <div class="customer">
    <button id="customer" class="btn"><?php echo 'Manage Customer' ?></button>
    </div>
    <div class="seller">
    <button id="seller" class="btn"><?php echo 'Manage Seller' ?></button>
    </div>
    <div class="product">
    <button id="product" class="btn"><?php echo 'Manage Product' ?></button>
    </div>
</div>

</div>

<script src="mainpage.js"></script>

   