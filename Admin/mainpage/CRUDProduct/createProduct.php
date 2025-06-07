<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

session_start();
$username = $_SESSION['adminUsername'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php';
// Prepare the SQL query to fetch user_id
$stmt = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

// Get the result
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];

    // Now you can use $userId
    // e.g. store in session or insert into other tables
} else {
    echo "User not found.";
}

$stmt->close();
if (isset($_POST['submit'])) {
    $productName = $_POST['productName'];
    $productImage = $_FILES['productImage']['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Upload path - store images in C:/xampp/htdocs/gearUp/inti/assets/
    $targetDir = "C:/xampp/htdocs/gearUp/inti/assets/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $targetFile = $targetDir . basename($productImage);

    // Allowed file types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (in_array($fileExtension, $allowedTypes)) {
        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $targetFile)) {
            // Store only the image filename in DB
            $imageFileName = basename($productImage);

            // Get next product ID
            $nextProductIDQuery = "SELECT MAX(product_id) AS max_id FROM products";
            $result = $conn->query($nextProductIDQuery);
            $row = $result->fetch_assoc();
            $maxProductID = $row['max_id'];
            $nextProductID = $maxProductID + 1;

            // Insert product into DB
            $insertProduct = "INSERT INTO products (product_id, product_name, image, price, stock, seller_id) 
                              VALUES ('$nextProductID', '$productName', '$imageFileName', '$price', '$stock', '$user_id')";

            if ($conn->query($insertProduct) === TRUE) {
                header("Location: createProduct.php?success=1");
                exit();
            } else {
                echo "Error inserting product: " . $conn->error;
            }
        } else {
            echo "Error uploading the file.";
        }
    } else {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sell Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .container {
            margin-left: 400px;
            margin-top: 50px;
            width: 400px;
            height: 550px;
            background-color: white;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .content {
            margin-left: 80px;
            width: 480px;
        }

        #title {
            font-size: 20px;
            margin-top: 30px;
            margin-left: 40px;
        }

        form {
            height: 320px;
        }

        #nameContainer,
        .stockContainer,
        .imageContainer,
        .priceContainer {
            flex-direction: column;
            gap: 5px;
            margin-bottom: 10px;
            margin-top: 30px;
            width: 250px;
        }

        input[type="file"],
        input[type="text"],
        input[type="number"] {
            margin-top: 10px;
            width: 200px;
            height: 40px;
        }

        #createProduct {
            background-color: #BFB9FA;
            color: black;
            padding: 14px 20px;
            border: none;
            cursor: pointer;
            width: 120px;
            margin-top: 10px;
            margin-left: 40px;
        }

        /* Remove number input arrows */
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
<div class="container">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div id="successMessage" style="color: black; font-weight: bold; margin-top: 10px; margin-left: 100px;">
            Product created successfully!
        </div>
    <?php endif; ?>

    <div class='content'>
        <div id='title'>Sell Product</div>
        <form action="createProduct.php" method="post" enctype="multipart/form-data">
            <div id="nameContainer">
                <label for="productName">Product Name</label>
                <input type="text" placeholder="Enter Product Name" name="productName" required>
            </div>

            <div class="stockContainer">
                <label for="stock">Stock</label>
                <input type="number" placeholder="Enter Stock" name="stock" required>
            </div>

            <div class="imageContainer">
                <label for="productImage">Product Image</label>
                <input type="file" name="productImage" required>
            </div>

            <div class="priceContainer">
                <label for="price">Price (RM)</label>
                <input type="number" placeholder="Enter price" name="price" required>
            </div>

            <input id="createProduct" type="submit" name="submit" value="Sell">
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