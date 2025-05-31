<?php
session_start();
$username = $_SESSION['username'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Customer/customerNavbar.php';

// Get user_id
$userQuery = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user_id = $userResult->fetch_assoc()['user_id'];

// Get active cart order
$orderQuery = $conn->prepare("SELECT order_id, subtotal FROM orders WHERE user_id = ? AND order_status = 'cart'");
$orderQuery->bind_param("i", $user_id);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
$order_id = null;
$grand_total = 0;

if ($orderRow = $orderResult->fetch_assoc()) {
    $order_id = $orderRow['order_id'];
    $grand_total = $orderRow['subtotal'];
}

// Handle delete request before rendering HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && $order_id) {
    $product_id_to_delete = $_POST['product_id'];

    // Delete item from order_items
    $deleteStmt = $conn->prepare("DELETE FROM order_items WHERE order_id = ? AND product_id = ?");
    $deleteStmt->bind_param("ii", $order_id, $product_id_to_delete);
    $deleteStmt->execute();

    // Recalculate new total
    $recalcStmt = $conn->prepare("SELECT SUM(quantity * price) AS new_total FROM order_items WHERE order_id = ?");
    $recalcStmt->bind_param("i", $order_id);
    $recalcStmt->execute();
    $recalcResult = $recalcStmt->get_result();
    $newTotal = $recalcResult->fetch_assoc()['new_total'] ?? 0;

    // Update new total in orders
    $updateOrder = $conn->prepare("UPDATE orders SET subtotal = ? WHERE order_id = ?");
    $updateOrder->bind_param("di", $newTotal, $order_id);
    $updateOrder->execute();

    // Redirect to avoid form resubmission
    header("Location: cart.php");
    exit();
}

// Get order items
$rows = [];
if ($order_id) {
    $itemQuery = $conn->prepare("SELECT *, (quantity * price) AS subtotal FROM order_items WHERE order_id = ?");
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
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>

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
            $total_price = $row['subtotal'];
        ?>
        <div class="content" id="row_<?php echo $product_id; ?>">
            <img class="item" src="<?php echo $imageUrl; ?>" alt="">
            <div class="product_name"><?php echo $product_name; ?></div>
            <div id="price">RM<?php echo $price; ?></div>
            <div id="quantity">x<?php echo $quantity; ?></div>
            <div id="total_price">RM<?php echo $total_price; ?></div>

            <form action="cart.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <button class="button" type="submit">Delete</button>
            </form>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="text">
        <div class="total">
            <div>Total:</div>
            <div id="total_prices">
                RM <?php echo number_format($grand_total, 2); ?>
                <button id="checkOutBtn" class="button" onclick="window.location.href='../order/checkOut.php'">Check Out</button>
            </div>
        </div>
</div>

<script src="cart.js"></script>
</body>
</html>