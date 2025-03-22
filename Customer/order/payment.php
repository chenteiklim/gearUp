<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();
if (!isset($_SESSION['product_ids'], $_SESSION['order_id'])) {
    die("Session data missing.");
}

$product_ids = $_SESSION['product_ids'];
$order_id = (int) $_SESSION['order_id']; // Ensure it's an integer
$clickDate = date("Y-m-d");
$username = $_SESSION['username'];

// Get user_id
$stmt = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    die("User not found.");
}

$row = $result->fetch_assoc();
$user_id = (int) $row['user_id'];
$tableName = "cart" . $user_id;

// Secure table name usage
if (!preg_match('/^\w+$/', $tableName)) {
    die("Invalid table name.");
}

// Delete from cart
$stmt = $conn->prepare("DELETE FROM $tableName WHERE name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

// Update order status to "purchased"
$stmt = $conn->prepare("UPDATE orders SET order_status = 'purchased', date = ? WHERE order_id = ?");
$stmt->bind_param("si", $clickDate, $order_id);
if (!$stmt->execute()) {
    die("Error updating order: " . $stmt->error);
}

// Fetch order's state
$stmt = $conn->prepare("SELECT state FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Order not found.");
}

$order = $result->fetch_assoc();
$order_state = $order['state'];

// Find an available rider in the same state
$sql = "SELECT rider_id FROM rider WHERE available = 1 AND state = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $order_state);
$stmt->execute();
$riders = $stmt->get_result();

if ($riders->num_rows > 0) {
    $rider = $riders->fetch_assoc();
    $assigned_rider = $rider['rider_id'];

    // Assign the rider to the order and mark them as unavailable
    $stmt = $conn->prepare("UPDATE orders SET assigned_rider = ?, order_status = 'assigned' WHERE order_id = ?");
    $stmt->bind_param("ii", $assigned_rider, $order_id);
    $stmt->execute();

    // Update rider table: Set currentOrder and mark as unavailable
    $stmt = $conn->prepare("UPDATE rider SET currentOrder = ?, available = 0 WHERE rider_id = ?");
    $stmt->bind_param("ii", $order_id, $assigned_rider);
    $stmt->execute();

    echo "Order ID: $order_id assigned to Rider ID: $assigned_rider.";
} else {
    echo "No available riders in state: $order_state.";
}

// Update product stock
if (!is_array($product_ids) || empty($product_ids)) {
    die("Invalid product data.");
}

$product_ids_str = implode(',', array_map('intval', $product_ids));
$quantities = $_SESSION['quantities'];

$sql_select = "SELECT product_id, stock, status FROM products WHERE product_id IN ($product_ids_str)";
$result = $conn->query($sql_select);

if (!$result) {
    die("Error fetching product data: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $product_id = (int) $row['product_id'];
    $stock = (int) $row['stock'];
    $status = (int) $row['status'];
    $quantity = isset($quantities[$product_id]) ? (int) $quantities[$product_id] : 0;

    $updated_stock = max(0, $stock - $quantity);
    $updated_status = max(0, $status + $quantity);

    $stmt = $conn->prepare("UPDATE products SET stock = ?, status = ? WHERE product_id = ?");
    $stmt->bind_param("iii", $updated_stock, $updated_status, $product_id);
    $stmt->execute();
}

$_SESSION['orders_id'] = $order_id + 1;
header("Location: success.php");
exit;
?>