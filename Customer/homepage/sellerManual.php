
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
$selectNameQuery = "SELECT * FROM users";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
}
    // Get the address value from the fetched row
    $name = $row['usernames'];

  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How to Become a Seller</title>
    <link rel="stylesheet" href="sellerManual.css">
    
</head>
<body>
    <div id="navContainer"> 
        <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
        <button class="button" id="home">Computer Shop</button>
        <button class="button" id="cart" onclick="window.location.href = '../product/cart.php';"><?php echo 'Shopping Cart'; ?></button>
        <button class="button" id="name"><?php echo $name ?></button>
        <form action="../login/logout.php" method="POST">
          <button type="submit" id="logOut" class="button">Log Out</button>
        </form>    
    </div>
    <div class="container">
        <h1>How to Become a Seller on Our E-Commerce Platform</h1>
        <div class="steps">
            <h2>Follow These Simple Steps:</h2>
            <div class="step">
                <h3>1. Fill Out the Seller Application</h3>
                <p>Complete the application form with details about your businessID, including your business name, description, and Contact Number</p>
            </div>
           
            <div class="step">
                <h3>2. Set Up Your Seller Profile</h3>
                <p>Once approved, set up your seller profile with information about your store, logo, and product listings.</p>
            </div>
            <div class="step">
                <h3>3. Start Selling!</h3>
                <p>After your profile is set up, you can start listing your products and selling to customers on the platform.</p>
            </div>
        </div>
    </div>
</body>
</html>

<script>
   document.getElementById("home").addEventListener("click", function() {
    // Replace 'login.html' with the URL of your login page
    window.location.href = "mainpage.php";
});  
</script>