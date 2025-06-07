<?php


include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

session_start();
$username=$_SESSION['adminUsername'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php';

$selectProductQuery = "
    SELECT 
        products.product_id,
        products.product_name,
        seller.sellerName,
        products.price,
        products.stock,
        products.image
    FROM products
    JOIN seller ON products.seller_id = seller.seller_id
";
$stmt = $conn->prepare($selectProductQuery);
$stmt->execute();
$result = $stmt->get_result();

$products = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product = array(
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'sellerName' => $row['sellerName'],
            'price' => $row['price'],
            'stock' => $row['stock'],
            'image' => $row['image']
        );
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
   
          
      #container{
        margin-left:300px;
        margin-top:50px;

      }
      .item-container{
        background-color:white;
        display:flex;
        flex-direction:column;
        align-items:center;
        padding-bottom:30px;

      }
    #productContainer {
  margin-left: 30px;
  display: grid;
  grid-template-columns: repeat(5, 1fr);  /* 3 items per row */
  gap: 20px;
  max-height: 600px;                      /* scroll after this height */
  overflow-y: auto;
  overflow-x: hidden;
}
    
    
    #img{
      width: 160px;
      height:180px;
    }

    .row{
      margin-top:15px;
    }

    #edit{
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
      <div class="item-container">
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

