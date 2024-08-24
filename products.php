<?php

require_once('connect.php'); // Include connection file

$conn = new mysqli($servername, $Username, $Password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
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

$productHTML = ''; // Initialize an empty string to store product HTML

// Loop through the array of rows and generate product HTML
foreach ($rows as $index => $row) {
  $product_id = $row['product_id'];
  $product_name = $row['product_name'];
  $price = $row['price'];
  $image = $row['image'];
  $stock = $row['stock'];
  $status = $row['status'];
  $button_id = $product_id;

  $newProduct2 = '
    <div class="product">
      <div class="imageContainer">
        <img class="item" src="' . $image . '" alt="">
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

$conn->close();

echo $productHTML;

if (isset($_POST['view'])) {
  $product2_id = $_POST['view'];

  // Use the $product2_id variable as needed
  // For example, you can store it in a session variable
  $_SESSION['product_id'] = $product2_id;

  if (isset($_SESSION['product_id'])) {
    // Product ID is saved in the session
    $product_id = $_SESSION['product_id'];
    echo '<script>window.location.href = "login.html";</script>'; 
  } else {
    // Product ID is not saved in the session
    echo "Product ID not found in the session.";
  }

  exit;
}

?>