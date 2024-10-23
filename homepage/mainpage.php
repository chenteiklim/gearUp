
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
$email = $_SESSION['email'] ?? null;

if (!$email) {
  echo "<h1>This Website is Not Accessible</h1>";
  echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
  exit;  // Stop further execution of the script
}
if (isset($_SESSION['orders_id'])) {
  $order_id = $_SESSION['orders_id'];
  // Your code here that uses the $order_id
}

mysqli_select_db($conn, $dbname);
$selectNameQuery = "SELECT usernames FROM users WHERE email = '$email'";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
    $usernames = $row['usernames'];
}
    // Get the address value from the fetched row

    

    $maxIdQuery = "SELECT MAX(product_id) AS max_id FROM products";
    $maxIdResult = $conn->query($maxIdQuery);
    
    if ($maxIdResult && $maxIdResult->num_rows > 0) {
        $row9 = $maxIdResult->fetch_assoc();
        $maxId = $row9['max_id'];
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

<div id="navContainer"> 
    <img id="logoImg" src="../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Computer Shop</button>
    <button class="button" id="cart" onclick="window.location.href = '../product/cart.php';"><?php echo 'Shopping Cart'; ?></button>
    <button class="button" id="tracking"><?php echo 'Tracking' ?></button>
    <button class="button" id="refund" type="submit" name="refund" value="">refund</button>
    <button class="button" id="name"><?php echo $usernames ?></button>
    <form action="../userLogin/logout.php" method="POST">
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
        $button_id = $product_id;
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
              <button class="button" type="submit" name="view" value="' . $button_id . '">View</button>
            </form>
          </div>
        </div>
        ';
  $productHTML .= $newProduct2;
  
}
  echo $productHTML;
  if (isset($_POST['view'])) {
    $product2_id = $_POST['view'];

    // Use the $product2_id variable as needed
    // For example, you can store it in a session variable
    $_SESSION['product_id'] = $product2_id;
    
    if (isset($_SESSION['product_id'])) {
      // Product ID is saved in the session
      $product_id = $_SESSION['product_id'];
      echo $product_id;

       echo '<script>window.location.href = "../product/product.php";</script>';
     
      
  } 
  
  else {
      // Product ID is not saved in the session
      echo "Product ID not found in the session.";
  }

    exit;
}
?>

<script src="mainpage.js"></script>

  
   