<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();
$username = $_SESSION['username'];


$stmt = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $user_id = $row['user_id'];
} else {
    echo "User not found.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM seller WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $seller_id = $row['seller_id'];
}

if (isset($_POST['submit'])) {
    $productName = $_POST['productName'];
    $productImage = $_FILES['productImage']['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $targetDir = "C:/xampp/htdocs/gearUp/assets/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $targetFile = $targetDir . basename($productImage);
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
    $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (in_array($fileExtension, $allowedTypes)) {
        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $targetFile)) {
            $imageUrl = "/inti/gearUp/assets/" . basename($productImage);
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    } else {
        echo "Sorry, only JPG, JPEG, PNG, GIF, WebP, and AVIF files are allowed.";
        exit;
    }

    $nextProductIDQuery = "SELECT MAX(product_id) AS max_id FROM products";
    $result = $conn->query($nextProductIDQuery);
    $row = $result->fetch_assoc();
    $maxProductID = $row['max_id'];
    $nextProductID = $maxProductID + 1;

    $insertProduct = "INSERT INTO products (product_id, seller_id, product_name, image, price, stock) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertProduct);
    $stmt->bind_param("iissdi", $nextProductID, $seller_id, $productName, $productImage, $price, $stock);

    if ($stmt->execute()) {
        header("Location: createProduct.php?success=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

}

?>
<?php     include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Seller/sellerNavbar.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
            color: #333;
        }

        #container {
            margin-left: 250px;
            padding: 20px;
        }

        .form-container {
            width: 480px;
            background-color: #ffffff;
            padding: 30px;
            box-shadow: 0 0 8px rgba(0,0,0,0.05);
            margin-top: 40px;
        }

        .form-title {
            font-size: 22px;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"] {
            padding: 10px;
            font-size: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }

        .form-group input[type="file"] {
            padding: 4px;
        }

        .submit-button {
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
        }

        .submit-button:hover {
            background-color: #2980b9;
        }

        #successMessage {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body>
<div id="container">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div id="successMessage">Product created successfully!</div>
    <?php endif; ?>

    <div class="form-container">
        <div class="form-title">Sell Product</div>
        <form action="createProduct.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="productName">Product Name</label>
                <input type="text" placeholder="Enter Product Name" name="productName" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" placeholder="Enter Stock" name="stock" required>
            </div>

            <div class="form-group">
                <label for="productImage">Product Image</label>
                <input type="file" name="productImage" required>
            </div>

            <div class="form-group">
                <label for="price">Price (RM)</label>
                <input type="number" step="0.01" placeholder="Enter Price" name="price" required>
            </div>

            <input class="submit-button" type="submit" name="submit" value="Sell">
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var successMessage = document.getElementById("successMessage");

        if (successMessage) {
            setTimeout(function () {
                successMessage.style.display = "none";
            }, 10000);

            setTimeout(function () {
                var newUrl = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            }, 10000);
        }
    });
</script>
</body>
</html>
