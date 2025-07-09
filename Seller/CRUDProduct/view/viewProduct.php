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

  
  ?><head>
  <meta charset="UTF-8">
  <title>My Products</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f9f9f9;
    }

    h1 {
      margin-top: 40px;
      margin-left: 30px;
    }

    #productContainer {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 20px;
      padding: 20px;
      margin-left: 30px;
    }

    .item-container {
      background-color: white;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      font-size: 16px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .row {
      margin: 6px 0;
    }

    img#img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 4px;
    }

    .button {
      padding: 8px 12px;
      border: none;
      cursor: pointer;
      font-size: 14px;
      border-radius: 4px;
      margin-top: 10px;
    }

    #edit {
      background-color: #007bff;
      color: white;
    }

    #delete {
      background-color: #dc3545;
      color: white;
    }

    #rating {
      background-color: #ffc107;
      color: black;
    }
  </style>
</head>

<body>
  <h1>My Products</h1>
  <div id="productContainer">
    <?php foreach ($products as $product): ?>
      <div class="item-container">
        <div class="row"><strong>Product Name:</strong> <?= htmlspecialchars($product['product_name']) ?></div>
        <div class="row"><strong>Product ID:</strong> <?= htmlspecialchars($product['product_id']) ?></div>
        <div class="row"><img id="img" src="<?= htmlspecialchars('/inti/gearUp/assets/' . $product['image']) ?>" alt="Product Image" /></div>
        <div class="row"><strong>Price:</strong> RM <?= htmlspecialchars($product['price']) ?></div>
        <div class="row"><strong>Stock:</strong> <?= htmlspecialchars($product['stock']) ?></div>
        <div class="row"><strong>Seller Name:</strong> <?= htmlspecialchars($product['sellerName']) ?></div>

        <div class="row">
          <?php
            $ratingStmt = $conn->prepare("SELECT ROUND(AVG(rating),1) AS avg_rating FROM ratings WHERE product_id = ?");
            $ratingStmt->bind_param("i", $product['product_id']);
            $ratingStmt->execute();
            $ratingResult = $ratingStmt->get_result();
            $ratingRow = $ratingResult->fetch_assoc();
            $avgRating = $ratingRow['avg_rating'] ?? 'No rating';
          ?>
          <strong>Rating:</strong> <?= htmlspecialchars($avgRating) ?>
        </div>

        <form method="POST" action="viewProductReview.php">
          <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
          <button id="rating" class="button" type="submit">View Reviews</button>
        </form>

        <form method="POST" action="editProduct.php">
          <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
          <button id="edit" class="button" type="submit">Edit Product</button>
        </form>

        <form method="POST" action="deleteProduct.php" onsubmit="return confirm('Are you sure you want to delete this product?');">
          <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
          <button id="delete" name="delete" class="button" type="submit">Delete Product</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</body>