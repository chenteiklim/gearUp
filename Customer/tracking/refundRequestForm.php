<?php

session_start();
if (!isset($_SESSION['username'])) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;
}

$username = $_SESSION['username'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Customer/customerNavbar.php';

if (!isset($_GET['order_item_id'])) {
    echo "<h1>Order Item ID not provided</h1>";
    exit;
}

$order_item_id = $_GET['order_item_id'];

$stmt = $conn->prepare("
    SELECT 
        oi.order_item_id, oi.order_id, oi.product_id, p.product_name
    FROM 
        order_items oi
    JOIN 
        products p ON oi.product_id = p.product_id
    WHERE 
        oi.order_item_id = ?
");
$stmt->bind_param("i", $order_item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h1>Invalid order item ID</h1>";
    exit;
}

$row = $result->fetch_assoc();

$order_id = $row['order_id'];
$productName = $row['product_name'];

$stmt->close();
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Request</title>
    <link rel="stylesheet" href="refundRequestForm.css">
</head>



<form id="formContainer" action="refund.php" method="POST" enctype="multipart/form-data">
    <div id="container">
        <div id="orders">
            <label for="order_item_id">Order Item ID:</label>
            <input type="number" name="order_item_id" value="<?php echo ($order_item_id); ?>" readonly required>
         </div>

        <div id="productName">
            <label for="productName">Product Name:</label>
            <input type="text" name="productName" value="<?php echo htmlspecialchars($productName); ?>" readonly required>
        </div>

        <div id="textArea">
            <label id="reason" for="reason">Reason:</label>
            <textarea name="reason" required></textarea>
        </div>

        <div id="upload">
  <label id="uploadImage">Upload Image/Video of Product:</label>

  <!-- Clickable styled label -->
  <label for="proof" class="custom-upload">
    📁 Choose File
  </label>

  <!-- Hidden actual file input -->
  <input type="file" id="proof" name="proof" accept="image/*,video/*" required>

  <!-- Optional: Show selected file name -->
  <span id="file-name">No file chosen</span>
</div>

        <button class="button" id="refundBtn" type="submit">Request Refund</button>
    </div>
</form>