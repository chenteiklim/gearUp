<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

// Check admin login
if (!isset($_SESSION['username'])) {
    echo "<h1>Access Denied</h1><p>You must be logged in as an seller to view this page.</p>";
    exit;
}
$username=$_SESSION['username'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Seller/sellerNavbar.php';

// Get product ID from POST
if (!isset($_POST['product_id']) || !is_numeric($_POST['product_id'])) {
    echo "<p>Invalid product ID.</p>";
    exit;
}

$product_id = $_POST['product_id'];

// Get product info
$stmt = $conn->prepare("SELECT product_name FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$productResult = $stmt->get_result();
$product = $productResult->fetch_assoc();

if (!$product) {
    echo "<p>Product not found.</p>";
    exit;
}

// Fetch ratings and reviews
$reviewStmt = $conn->prepare("
    SELECT r.rating, r.review, r.created_at, u.usernames
    FROM ratings r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC
");
$reviewStmt->bind_param("i", $product_id);
$reviewStmt->execute();
$reviewResult = $reviewStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Product Reviews</title>
    <style>
      
        h1 {
            margin-top: 50px;
        }
        .review-box {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #ccc;
            width: 80%;
        }
        .rating {
            font-weight: bold;
            color: #e67e22;
        }
        #container{
            margin-left:350px;
        }
    </style>
</head>
<body>
    <div id='container'>   
         <h1>Reviews for: <?= htmlspecialchars($product['product_name']) ?></h1>

    <?php if ($reviewResult->num_rows > 0): ?>
        <?php while ($review = $reviewResult->fetch_assoc()): ?>
            <div class="review-box">
                <p><strong>User:</strong> <?= htmlspecialchars($review['usernames']) ?></p>
                <p class="rating">Rating: <?= htmlspecialchars($review['rating']) ?> / 5</p>
                <p><strong>Comment:</strong> <?= nl2br(htmlspecialchars($review['review'])) ?></p>
                <p><em>Posted on:</em> <?= htmlspecialchars($review['created_at']) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No reviews available for this product yet.</p>
    <?php endif; ?>
    </div>
   
</body>
</html>

<?php
$conn->close();
?>