<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Customer/customerNavbar.php';

// Get product list
$selectRowsQuery = "SELECT * FROM products ORDER BY product_id ASC";
$selectRowsResult = $conn->query($selectRowsQuery);

$products = [];
if ($selectRowsResult && $selectRowsResult->num_rows > 0) {
    while ($row = $selectRowsResult->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<link rel="stylesheet" href="customerHomepage.css">

<div id="container">
    <?php foreach ($products as $product): ?>
        <div class="product">
            <div class="imageContainer">
                <img class="item" src="/inti/gearUp/assets/<?php echo htmlspecialchars($product['image']); ?>" alt="">
            </div>
            <div class="productDetails">
                <div class="product_name"><?php echo htmlspecialchars($product['product_name']); ?></div>
                <div class="price">
                    <div class="unit">RM</div>
                    <div><?php echo htmlspecialchars($product['price']); ?></div>
                </div>
              
                <button id="view" class="button" onclick="location.href='../login/login.php'">View</button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="customerHomepage.js"></script>