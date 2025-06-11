<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

session_start();
$username=$_SESSION['username'];

$sql = "
    SELECT s.seller_id 
    FROM users u
    JOIN seller s ON u.user_id = s.user_id
    WHERE u.usernames = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $seller_id = $row['seller_id'];
}


include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Seller/sellerNavbar.php';


$selectProductQuery = "
    SELECT p.*, s.sellerName
    FROM products p
    JOIN seller s ON p.seller_id = s.seller_id
    WHERE p.seller_id = ?
";

$stmt = $conn->prepare($selectProductQuery);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

$products = array(); // Initialize an empty array to store the products

if ($result->num_rows > 0) {
    // Loop through the result and retrieve each product as an array
    while ($row = $result->fetch_assoc()) {
        $product = array(
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'sellerName' => $row['sellerName'],
            'price' => $row['price'],
            'stock' => $row['stock'],
            'image'=> $row['image'],
        );

        // Add the product array to the products array
        $products[] = $product;
    }
  }

 
  
  ?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <style>
   
          


      .container{
        background-color:white;
        display:flex;
        flex-direction:column;
        align-items:center;
        padding-bottom:30px;

      }
      
      #productContainer{
        margin-left: 30px;
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 60px;
        margin-top: 10px;
        flex-wrap: wrap;
        width: 1490px;
        height:auto;
      }
        
    #img{
      width: 160px;
      height:180px;
    }

    .row{
      margin-top:15px;
    }

    #edit{
    background-color:#3498db;
      margin-top:30px;
    }
    #delete{
      margin-top:10px;
    }

    #title{
      font-size:20px;
      font-weight:600;
    }

    </style>
</head>



<div id="container">
  <div id='productContainer'>
    <?php foreach ($products as $product): ?>
      <div class="container">
        <div id='title' class='row'>
            <?php echo htmlspecialchars($product['product_name']); ?>
        </div>

        <div id='id' class='row'>
            <?php echo 'Product ID: ' . htmlspecialchars($product['product_id']); ?>
        </div> 

        <div id="imageContainer" class='row'>
            <img id='img' src="<?php echo htmlspecialchars('/inti/gearUp/assets/' . $product['image']); ?>" alt="Product Image" />
        </div>

        <div id="priceContainer" class='row'>   
            <?php echo 'Price: RM ' . htmlspecialchars($product['price']); ?>
        </div>

        <div id='stockContainer' class='row'>
            <?php echo 'Stock: ' . htmlspecialchars($product['stock']); ?>
        </div>

        <div id='seller' class='row'>
            <?php echo 'Seller Name: ' . htmlspecialchars($product['sellerName']); ?>
        </div>

        <!-- Hidden field inside the form -->
        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">

        <!-- Submit button inside the form -->
        <form method="POST" action="editProduct.php">
          <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
          <button id="edit" class="button" type="submit" name="editSingle">Edit product</button>
        </form>

        <form method="POST" action="deleteProduct.php" onsubmit="return confirm('Are you sure you want to delete this product?');">
          <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
          <button type="submit" name="delete" class="button" style="background-color: red; color: white;">Delete</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</div>

