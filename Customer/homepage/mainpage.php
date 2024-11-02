
<?php

$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
session_start();
$username = $_SESSION['username'] ?? null;

if (!$username) {
  echo "<h1>This Website is Not Accessible</h1>";
  echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
  exit;  // Stop further execution of the script
}
if (isset($_SESSION['orders_id'])) {
  $order_id = $_SESSION['orders_id'];
  echo $order_id;
}

mysqli_select_db($conn, $dbname);
    $maxIdQuery = "SELECT MAX(product_id) AS max_id FROM products";
    $maxIdResult = $conn->query($maxIdQuery);
    
    if ($maxIdResult && $maxIdResult->num_rows > 0) {
        $row = $maxIdResult->fetch_assoc();
        $maxId = $row['max_id'];
    }
    
    // Query to retrieve all rows in ascending order
    $selectRowsQuery = "SELECT * FROM products ORDER BY product_id ASC";
    $selectRowsResult = $conn->query($selectRowsQuery);
    
    $rows = []; // Initialize an empty array to store the rows
    
    if ($selectRowsResult && $selectRowsResult->num_rows > 0) {
        while ($row = $selectRowsResult->fetch_assoc()) {
            $rows[] = $row; // Add each row to the array
        }
    }
    
  
  ?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
   <link rel="stylesheet" href="mainpage.css">
</head>
<body>

<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Computer Shop</button>
    <button class="button" id="cart" onclick="window.location.href = '../product/cart.php';"><?php echo 'Shopping Cart'; ?></button>
    <button class="button" id="tracking"><?php echo 'Tracking' ?></button>
    <button class="button" id="refund" type="submit" name="refund" value="">refund</button>
    <button class="button" id="name"><?php echo $username ?></button>
    <form action="../login/logout.php" method="POST">
      <button type="submit" id="logOut" class="button">Log Out</button>
    </form>    
</div>
<div id="messageContainer"></div>

</div>
<div id="container">

  <?php
   
$productHTML = '';

  // Loop through the array of rows
  foreach ($rows as $index => $row) {
    $product_id = $row['product_id'];
    $product_name = $row['product_name'];
    $price = $row['price'];
    $image = $row['image'];
    $stock=$row['stock'];
    $status=$row['status'];
    $imageUrl = "/inti/gadgetShop/assets/" . $image;

    $newProduct2 = '
    <div class="product">
      <div class="imageContainer">
        <img class="item" src="' . $imageUrl . '" alt="">
      </div>
      <div class="productDetails">
        <div class="product_name">' . $product_name . '</div>
        <div class="price">
          <div class="unit">RM</div>
          <div>' . $price . '</div>
        </div>
        <div class="stock">' . ($stock > 0 ? $stock . ' stock available' : 'Out of stock') . '</div>
        <div class="status">' . $status . ' sold</div>
        <form action="" method="post">
          <button class="button" type="submit" name="view" value="' . $product_id . '">View</button>
        </form>
      </div>
    </div>
    ';
  $productHTML .= $newProduct2;
  }
  echo $productHTML;

  if (isset($_POST['view'])) {
    $_SESSION['product_id'] = $_POST['view'];
    
    if (!empty($_SESSION['product_id'])) {
       echo '<script>window.location.href = "../product/product.php";</script>'; 
    } else {
       echo "Product ID not found in the session.";
    }
    exit;
  }
?>
<script>
  console.log('hello world')

  window.onload = function() {
  var urlParams = new URLSearchParams(window.location.search);
  const message = urlParams.get('message');

  if (message) {
    var messageContainer = document.getElementById("messageContainer");
    messageContainer.textContent = decodeURIComponent(message); // Decode the URL-encoded message
    messageContainer.style.display = "block";
    messageContainer.classList.add("message-container");
    
    setTimeout(function() {
      messageContainer.style.display = "none";
      messageContainer.classList.remove("message-container");
      
      // Clear the message from the URL
      const url = new URL(window.location);
      url.searchParams.delete('message');
      window.history.replaceState({}, document.title, url);
    }, 3000);
  }
}

window.onload = function() {
  var urlParams = new URLSearchParams(window.location.search);
  const message2 = urlParams.get('message2');

  if (message2) {
    var messageContainer = document.getElementById("messageContainer");
    messageContainer.textContent = decodeURIComponent(message2); // Decode the URL-encoded message
    messageContainer.style.display = "block";
    messageContainer.classList.add("message-container");
    
    setTimeout(function() {
      messageContainer.style.display = "none";
      messageContainer.classList.remove("message-container");
      
      // Clear the message from the URL
      const url = new URL(window.location);
      url.searchParams.delete('message2');
      window.history.replaceState({}, document.title, url);
    }, 3000);
  }
}
var tracking = document.getElementById("tracking");

tracking.addEventListener("click", function() {
// Perform the navigation action here
window.location.href = "../tracking/tracking.php";
});


var refund = document.getElementById("refund");

refund.addEventListener("click", function() {
// Perform the navigation action here
window.location.href = "receipt.php";
});




</script>
  
</body>
  </html>
   