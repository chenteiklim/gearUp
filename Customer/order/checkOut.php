<?php

session_start();
$usernames=$_SESSION['username'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/Customer/customerNavbar.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/encryption_helper.php';

$stmt = $conn->prepare("SELECT * FROM users WHERE usernames = ?");
$stmt->bind_param("s", $usernames);
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the result as an associative array
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
    $encryptedAddress= $row['address'];
    $address = openssl_decrypt($encryptedAddress, 'AES-256-CBC', $encryption_key, 0, $encryption_iv);

} else {
    echo "No user found with that username.";
}
$stmt->close();
$orderQuery = $conn->prepare("SELECT order_id, total_price FROM orders WHERE user_id = ? AND order_status = 'cart'");
$orderQuery->bind_param("i", $user_id);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
$rows = [];
if ($orderRow = $orderResult->fetch_assoc()) {
    $order_id = $orderRow['order_id'];
    $grandTotal = $orderRow['total_price'];

    // Get items
    $itemQuery = $conn->prepare("SELECT *, (quantity * price) AS total_price FROM order_items WHERE order_id = ?");
    $itemQuery->bind_param("i", $order_id);
    $itemQuery->execute();
    $itemsResult = $itemQuery->get_result();
    while ($row = $itemsResult->fetch_assoc()) {
        $rows[] = $row;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../product/cart.css">
    <link rel="stylesheet" href="checkOut.css">
</head>
<body>
    <div id="messageContainer"></div>
     <div class='address'>
        Shipping Address:
        <?php echo $address;?>
    </div>   
    
<div id="container">
    <div class='title'>
        <div class="Product">Product</div>
        <div class="product_name">Product Name</div>
        <div class="price">Price</div>
        <div class="quantity">Quantity</div>
        <div class="total_price">Total Price</div>
    </div>

    <?php if (empty($rows)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <?php foreach ($rows as $row): 
            $product_id = $row['product_id']; 
            $product_name = $row['product_name'];
            $imageUrl = "/inti/gadgetShop/assets/" . $row['image'];
            $price = $row['price'];
            $quantity = $row['quantity'];
            $total_price = $row['total_price'];
        ?>
        <div class="content" id="row_<?php echo $product_id; ?>">
            <img class="item" src="<?php echo $imageUrl; ?>" alt="">
            <div class="product_name"><?php echo $product_name; ?></div>
            <div id="price">RM<?php echo $price; ?></div>
            <div id="quantity">x<?php echo $quantity; ?></div>
            <div id="total_price">RM<?php echo $total_price; ?></div>

         
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

    <div id='footers'>
        <div id="paymentMethod">
            <div id="paymentTitle">Payment Method</div>
            <button id="COD" class="paymentButton selected" onclick="selectPayment('COD')">Cash On Delivery</button>
            <button id="walletSelection" class="paymentButton" onclick="selectPayment('walletSelection')">Wallet</button>
        </div>
        <form id="checkOut" action="payment.php" method="POST">
            <input type="hidden" name="payment_method" id="selectedPayment" value="COD"> <!-- Default: COD -->
            <div id='merchandise' class="row">
                <div>Merchandise Subtotal</div>
                <div class='row2'> <?php echo "RM $grandTotal"?></div>
            </div>

            <div class="row">
                <div>Shipping total</div>
                <div class='row3'><?php echo "RM 9.00"?></div>
            </div>

            <div class='row'>
                <div>Total Payment</div>
                    <?php $order_price= $grandTotal + 9 ?>
                <div class='row4'><?php echo "RM $order_price"?></div>
            </div>
            
            <button id="checkOutbtn" class="button"><?php echo 'Place Order' ?></button>
        </form>  
    </div>
</body>
</html>
<script src="checkOut.js"></script>

