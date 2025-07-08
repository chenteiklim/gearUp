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
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'avif', 'webp'];
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
       
        #container {
            margin-left: 450px;
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