<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$order_id = $_GET['order_id'] ?? '';
$product_name = $_GET['product_name'] ?? '';

// Establish a database connection
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

// Fetch product_id from the database using product_name
$product_id = '';
if ($product_name) {
    $stmt = $conn->prepare("SELECT product_id FROM products WHERE product_name = ?");
    $stmt->bind_param("s", $product_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product_data = $result->fetch_assoc();
        $product_id = $product_data['product_id'];
    } else {
        echo "Product not found.";
        exit;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rate Product</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
            background: #f7f7f7;
            padding: 20px;
            border-radius: 8px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        textarea {
            width: 100%;
            height: 100px;
        }
        .star-rating {
            font-size: 28px;
            cursor: pointer;
            color: lightgray;
            user-select: none;
        }
        .star.selected {
            color: gold;
        }
        .submit-btn {
            margin-top: 20px;
            padding: 8px 16px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<h2>Rate Product</h2>
<p>You're rating: <strong><?= htmlspecialchars($product_name) ?></strong></p>

<form method="POST" action="submitRating.php">
    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order_id) ?>">
    <input type="hidden" name="product_name" value="<?= htmlspecialchars($product_name) ?>">
    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product_id) ?>"> <!-- Pass product_id -->
    <input type="hidden" name="rating" id="ratingInput" value="0">

    <label>Rating:</label>
    <div class="star-rating" id="starRating">
        <span class="star" data-value="1">&#9733;</span>
        <span class="star" data-value="2">&#9733;</span>
        <span class="star" data-value="3">&#9733;</span>
        <span class="star" data-value="4">&#9733;</span>
        <span class="star" data-value="5">&#9733;</span>
    </div>

    <label for="comment">Comment (optional):</label>
    <textarea name="review" id="comment" placeholder="Write your feedback here..."></textarea> <!-- Changed name to 'review' -->

    <button class="submit-btn" type="submit">Submit Rating</button>
</form>

<script>
    const stars = document.querySelectorAll('#starRating .star');
    const ratingInput = document.getElementById('ratingInput');
    let currentRating = 0;

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const selected = parseInt(star.dataset.value);
            currentRating = (selected === currentRating) ? 0 : selected;
            ratingInput.value = currentRating;
            updateStars();
        });
    });

    function updateStars() {
        stars.forEach(star => {
            const val = parseInt(star.dataset.value);
            star.classList.toggle('selected', val <= currentRating);
        });
    }
</script>

</body>
</html>