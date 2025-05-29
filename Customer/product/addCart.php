<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';
session_start();

$username = $_SESSION['username'];

// First get the user_id based on username
$userQuery = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();

if ($userResult && $userResult->num_rows > 0) {
    $userRow = $userResult->fetch_assoc();
    $user_id = $userRow['user_id'];
} else {
    die("User not found");
}

if (isset($_POST['addCart'])) {
    $product_id = $_SESSION['product_id'];
    $quantity = $_POST['quantity_input'];

    // Get product info
    $productSql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($productSql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $productResult = $stmt->get_result();

    if ($productResult && $productResult->num_rows > 0) {
        $product = $productResult->fetch_assoc();

        $product_name = $product['product_name'];
        $price = $product['price'];
        $image = $product['image'];
        $total_price = $quantity * $price;

        // Check if user already has a cart order
        $orderCheckSql = "SELECT order_id FROM orders WHERE user_id = ? AND order_status = 'cart' LIMIT 1";
        $orderCheckStmt = $conn->prepare($orderCheckSql);
        $orderCheckStmt->bind_param("i", $user_id);
        $orderCheckStmt->execute();
        $orderCheckResult = $orderCheckStmt->get_result();

        if ($orderCheckResult && $orderCheckResult->num_rows > 0) {
            // Reuse existing order_id
            $orderRow = $orderCheckResult->fetch_assoc();
            $order_id = $orderRow['order_id'];
        } else {
            // Create new order with status 'cart'
            $createOrderSql = "INSERT INTO orders (user_id, total_price, order_status, order_date) VALUES (?, 0, 'cart', NOW())";
            $createOrderStmt = $conn->prepare($createOrderSql);
            $createOrderStmt->bind_param("i", $user_id);
            $createOrderStmt->execute();
            $order_id = $conn->insert_id;  // get last inserted order_id
        }

        // Check if the product already exists in this order's items
        $itemCheckSql = "SELECT quantity FROM order_items WHERE order_id = ? AND product_id = ?";
        $itemCheckStmt = $conn->prepare($itemCheckSql);
        $itemCheckStmt->bind_param("ii", $order_id, $product_id);
        $itemCheckStmt->execute();
        $itemCheckResult = $itemCheckStmt->get_result();

        if ($itemCheckResult && $itemCheckResult->num_rows > 0) {
            // Update quantity if product already in cart
            $itemRow = $itemCheckResult->fetch_assoc();
            $newQuantity = $itemRow['quantity'] + $quantity;

            $updateItemSql = "UPDATE order_items SET quantity = ? WHERE order_id = ? AND product_id = ?";
            $updateItemStmt = $conn->prepare($updateItemSql);
            $updateItemStmt->bind_param("iii", $newQuantity, $order_id, $product_id);
            $updateItemStmt->execute();

        } else {
            // Insert new item to order_items
            $insertItemSql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price, image) VALUES (?, ?, ?, ?, ?, ?)";
            $insertItemStmt = $conn->prepare($insertItemSql);
            $insertItemStmt->bind_param("iisids", $order_id, $product_id, $product_name, $quantity, $price, $image);
            $insertItemStmt->execute();
        }

        // Update total price in orders table (sum of all order_items)
        $totalPriceSql = "SELECT SUM(quantity * price) as total FROM order_items WHERE order_id = ?";
        $totalPriceStmt = $conn->prepare($totalPriceSql);
        $totalPriceStmt->bind_param("i", $order_id);
        $totalPriceStmt->execute();
        $totalPriceResult = $totalPriceStmt->get_result();
        $totalPriceRow = $totalPriceResult->fetch_assoc();
        $newTotalPrice = $totalPriceRow['total'];

        $updateOrderPriceSql = "UPDATE orders SET total_price = ? WHERE order_id = ?";
        $updateOrderPriceStmt = $conn->prepare($updateOrderPriceSql);
        $updateOrderPriceStmt->bind_param("di", $newTotalPrice, $order_id);
        $updateOrderPriceStmt->execute();
        $message2 = "Cart added successfully";
        header("Location: ../mainpage/customerMainpage.php?message2=" . urlencode($message2));


    } else {
        echo "Product not found.";
    }
}
?>