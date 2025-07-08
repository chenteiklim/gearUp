<?php

session_start();
$usernames=$_SESSION['username'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Customer/customerNavbar.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/encryption_helper.php';

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

// Step 1: retrieve order
$orderQuery = $conn->prepare("SELECT * FROM orders WHERE user_id = ? AND order_status = 'cart'");
$orderQuery->bind_param("i", $user_id);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
$rows = [];

if ($orderRow = $orderResult->fetch_assoc()) {
    $order_id = $orderRow['order_id'];
    $shipping_price = 9.00;
    $subtotal = $orderRow['subtotal'];

    $total_price = $subtotal + $shipping_price;

    // Step 2: Update total_price to include shipping fees
    $updateQuery = $conn->prepare("UPDATE orders 
    SET total_price = ? WHERE order_id = ?");
    $updateQuery->bind_param("di", $total_price, $order_id);
    $updateQuery->execute();

    // Step 3: Retrieve all order items (for displaying)
    $itemQuery = $conn->prepare("SELECT * FROM order_items
     WHERE order_id = ?");
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
            $imageUrl = "/inti/gearUp/assets/" . $row['image'];
            $price = $row['price'];
            $quantity = $row['quantity'];
            $item_price = $price * $quantity; // <-- this line calculates the item price

        ?>
        <div class="content" id="row_<?php echo $product_id; ?>">
            <img class="item" src="<?php echo $imageUrl; ?>" alt="">
            <div class="product_name"><?php echo $product_name; ?></div>
            <div id="price"><?php echo "RM " . number_format($price, 2); ?></div>
            <div id="quantity">x<?php echo $quantity; ?></div>
            <div id="item_price"><?php echo "RM " . number_format($item_price, 2); ?></div>

         
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
                <div class='row2'> <?php echo "RM $subtotal"?></div>
            </div>

            <div class="row">
                <div>Shipping total</div>
                <div class='row3'><?php echo "RM " . number_format($shipping_price, 2); ?></div>
            </div>

            <div class='row'>
                <div>Total Payment</div>
                <div class='row4'><?php echo "RM " . number_format($total_price, 2); ?></div>
            </div>
            
            <button id="checkOutbtn" class="button"><?php echo 'Place Order' ?></button>
        </form>  
    </div>
</body>
</html>
<script src="checkOut.js"></script>

