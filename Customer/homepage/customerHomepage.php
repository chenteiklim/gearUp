<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

// Get product list
$selectRowsQuery = "SELECT * FROM products ORDER BY product_id ASC";
$selectRowsResult = $conn->query($selectRowsQuery);

$searchTerm = $_GET['search'] ?? '';

if (!empty($searchTerm)) {
    $selectRowsQuery = "
        SELECT p.*, s.sellerName AS sellerName
        FROM products p
        JOIN seller s ON p.seller_id = s.seller_id
        WHERE p.product_name LIKE ?
        ORDER BY p.product_id ASC
    ";
    $stmt = $conn->prepare($selectRowsQuery);
    $likeSearch = "%" . $searchTerm . "%";
    $stmt->bind_param("s", $likeSearch);
    $stmt->execute();
    $selectRowsResult = $stmt->get_result();
} else {
    $selectRowsQuery = "
        SELECT p.*, s.sellerName AS sellerName
        FROM products p
        JOIN seller s ON p.seller_id = s.seller_id
        ORDER BY p.product_id ASC
    ";
    $selectRowsResult = $conn->query($selectRowsQuery);
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="customerHomepage.css">
</head>
<body>
<div id="navContainer"> 
  <img id="logoImg" src="/inti/gearUp/assets/logo.jpg" alt="" srcset="">
  <button class="navButton" id="home">GearUp</button>
  <button class="navButton" id="login">Login</button>
  <button class="navButton" id="register">Register</button>

</div>
<div id="messageContainer"></div>
<form class="search-bar" method="GET" action="">
    <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />
    <button type="submit"><i class="fa fa-search"></i></button>
</form>
<?php if (!empty($searchTerm)): ?>
    <div style="text-align: right; margin-right: 200px; margin-bottom: 10px;">
        <a href="customerHomepage.php" style="color: #3498db; text-decoration: none; font-weight: bold;">
            🔄 Show All Products
        </a>
    </div>
<?php endif; ?>
<?php if ($selectRowsResult->num_rows === 0): ?>
    <p style="margin-left: 300px;">No products found for "<?= htmlspecialchars($searchTerm) ?>".</p>
<?php endif; ?>
<div id="container">
<?php while ($row = $selectRowsResult->fetch_assoc()): ?>
    <div class="product">
        <div class="imageContainer">
            <img class="item" src="/inti/gearUp/assets/<?= $row['image'] ?>" alt="">
        </div>
        <div class="productDetails">
            <div class="product_name"><?= htmlspecialchars($row['product_name']) ?></div>
            <div class="price">
                <div class="unit">RM</div>
                <div><?= number_format($row['price'], 2) ?></div>
            </div>

        <button id="view" class="button" onclick="location.href='../login/login.php'">View</button>
</div>
    </div>
<?php endwhile; ?>
</div>

<script src="customerHomepage.js"></script>