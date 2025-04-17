<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';
session_start();

if (!isset($_SESSION['username'])) {
?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Access Denied</title>
    </head>
    <body>
        <h1>This Website is Not Accessible</h1>
        <p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>
    </body>
    </html>
<?php
    exit;
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/customerNavbar.php';

$username = $_SESSION['username'];

// Fetch user ID
$selectNameQuery = "SELECT user_id FROM users WHERE usernames = ?";
$stmt = $conn->prepare($selectNameQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Fetch products
$selectRowsQuery = "SELECT * FROM products ORDER BY product_id ASC";
$selectRowsResult = $conn->query($selectRowsQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Product</title>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="customerMainpage.css">
</head>
<body>

<div id="messageContainer"></div>

<div id="container">
    <?php while ($row = $selectRowsResult->fetch_assoc()): ?>
        <div class="product">
            <div class="imageContainer">
                <img class="item" src="/inti/gadgetShop/assets/<?= $row['image'] ?>" alt="">
            </div>
            <div class="productDetails">
                <div class="product_name"><?= htmlspecialchars($row['product_name']) ?></div>
                <div class="price">
                    <div class="unit">RM</div>
                    <div><?= number_format($row['price'], 2) ?></div>
                </div>
                <div class="stock"><?= $row['stock'] > 0 ? $row['stock'] . ' stock available' : 'Out of stock' ?></div>
                <?php if ($row['status'] > 0): ?>
                    <div class="status"><?= $row['status'] ?> sold</div>
                <?php endif; ?>
                <div class="sellerName">
                    Store Name: <?= htmlspecialchars($row['storeName']) ?>
                </div>
                <div class="sellerName">
                    Seller Name: <?= htmlspecialchars($row['sellerName']) ?>
                </div>
                <form action="" method="post">
                    <button class="button" type="submit" name="view" value="<?= $row['product_id'] ?>">View</button>
                </form>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<div id="chatIcon">
    <i class="fa fa-comment"></i>
</div>

<div id="sellerList">
    <div id="sellerNavbar">
      <h3 id="sellerHeader">
          Chat with Seller
      </h3>
      <button id="closeSellerList" class="close-btn">&times;</button>
    </div>              
    <div id="sellersContainer">
        <!-- Seller items will be dynamically inserted here -->
    </div>
</div>

<!-- Chat Popup Window -->
<div id="chatPopup">
    <div id="chatHeader">
        <span>Chat with <span id="chatSellerName"></span></span>
        <button id="closeChat">&times;</button>
    </div>
    <div id="chatBody">
        <div id="chatMessages"></div>
    </div>
    <div id="chatFooter">
        <input type="text" id="chatInput" placeholder="Type a message..." />
        <button id="sendMessage">Send</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        localStorage.setItem("customerName", "<?= $username ?>");
    });
</script>
<script src="customerMainpage.js"></script>
<script src="chat.js"></script>
<script src="getSeller.js"></script>

</body>
</html>

<?php
// Handle product view button
if (isset($_POST['view'])) {
    $_SESSION['product_id'] = $_POST['view'];
    echo '<script>window.location.href = "../product/product.php";</script>'; 
    exit;
}
?>