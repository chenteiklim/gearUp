<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();
if (!isset($_SESSION['username'])) {
  echo "<h1>This Website is Not Accessible</h1>";
  echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
  exit;
}    

$username = $_SESSION['username'];
if (isset($_GET['orders_id'])) {
    $orders_id = $_GET['orders_id'];
} else {
    // Provide a default or error message if not found
    $orders_id = 'Not Available';
}

// Prepare the SQL query to fetch the product name from the products table
$stmt = $conn->prepare("SELECT product_name FROM orders WHERE orders_id = ?");
$stmt->bind_param("s", $orders_id); // Binding the orders_id as a string (assuming orders_id is a string)
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the product name from the result
    $row = $result->fetch_assoc();
    $productName = $row['product_name'];
} else {
    $productName = 'Product not found';
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Request</title>
    <link rel="stylesheet" href="refundRequestForm.css">
</head>

<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="">
    <button class="button" id="home">Trust Toradora</button>
    <button class="button" id="name"><?php echo htmlspecialchars($username); ?></button>
    <form action="../login/logout.php" method="POST">
        <button type="submit" id="logout" class="button">Log Out</button>
    </form> 
</div>

<form id="formContainer" action="refund.php" method="POST" enctype="multipart/form-data">
    <div id="container">
        <div id="orders">
            <label for="orders_id">Orders ID:</label>
            <input type="number" name="orders_id" value="<?php echo isset($orders_id) ? htmlspecialchars($orders_id) : ''; ?>" readonly required>        </div>

        <div id="productName">
            <label for="productName">Product Name:</label>
            <input type="text" name="productName" value="<?php echo htmlspecialchars($productName); ?>" readonly required>
        </div>

        <div id="textArea">
            <label id="reason" for="reason">Reason:</label>
            <textarea name="reason" required></textarea>
        </div>

        <div id="upload">
            <label id="uploadImage" for="proof">Upload Image/Video of Product:</label>
            <input type="file" name="proof" accept="image/*,video/*" required>          
        </div>

        <button class="button" id="refundBtn" type="submit">Request Refund</button>
    </div>
</form>