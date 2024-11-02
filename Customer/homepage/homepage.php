<link rel="stylesheet" href="homepage.css">
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

 

<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Computer Shop</button>
    <button id='register' class="button"><?php echo 'Register'?></button>
    <button id="login" class="button"><?php echo 'Log in' ?></button>
</div>

<div id="container">

<?php
  $productHTML = '';

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
          <button id="View" class="button" type="submit" name="view" value="' . $product_id . '">View</button>
        </form>
      </div>
    </div>
    ';
  $productHTML .= $newProduct2;
  }

  echo $productHTML;

  if (isset($_POST['view'])) {
    echo '<script>window.location.href = "../login/login.html";</script>';
  }
?>
</div>
<script src="homepage.js"></script>

