<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();


$product_ids = $_SESSION['product_ids'];
$order_id = (int) $_SESSION['order_id'];
$clickDate = date("Y-m-d");
$username = $_SESSION['username'];
$platformName='Trust Toradora';

$payment_method = $_POST['payment_method']; // "COD" or "wallet"
if (!isset($_SESSION['product_ids'], $_SESSION['order_id'], $_SESSION['username'], $_POST['payment_method'])) {
    die("Session data missing.");
}
// Start transaction
$conn->begin_transaction();

// Get user_id
$stmt = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    $conn->rollback();
    die("User not found.");
}

$row = $result->fetch_assoc();
$user_id = (int) $row['user_id'];
$tableName = "cart" . $user_id;

// Secure table name usage
if (!preg_match('/^\w+$/', $tableName)) {
    $conn->rollback();
    die("Invalid table name.");
}

// Get wallet balance (wallet table uses username)
$stmt = $conn->prepare("SELECT * FROM wallet WHERE usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$customerWallet_result = $stmt->get_result();

$customerWallet = $customerWallet_result->fetch_assoc();
$customerWallet_balance = (float) $customerWallet['wallet_balance'];


// Get wallet balance (wallet table uses username)
$stmtplatform = $conn->prepare("SELECT * FROM wallet WHERE usernames = ?");
$stmtplatform->bind_param("s", $platformName);
$stmtplatform->execute();
$platformWallet_result = $stmtplatform->get_result();

$platformWallet = $platformWallet_result->fetch_assoc();
$platformWallet_balance = isset($platformWallet['wallet_balance']) ? (float)$platformWallet['wallet_balance'] : 99.00;

echo $user_id;
echo $tableName;

// Calculate total price from cart using user_id
$stmt = $conn->prepare("SELECT SUM(price * quantity) AS total_price FROM $tableName WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();

$cart = $cart_result->fetch_assoc();
$total_price = (float) $cart['total_price'];
echo $total_price;

if ($total_price <= 0) {
    $conn->rollback();
    die("Invalid total price.");
}

if ($payment_method === "walletSelection") {
    if ($customerWallet_balance < $total_price) {
        $conn->rollback();
        die("Insufficient wallet balance.");
    }

   // Deduct from wallet (wallet uses username)
    $customerWallet_balance = $customerWallet_balance - $total_price;
    $stmtDeduct = $conn->prepare("UPDATE wallet SET wallet_balance = ? WHERE usernames = ?");
    $stmtDeduct->bind_param("ds", $customerNew_balance, $username);
    if (!$stmtDeduct->execute()) {
        $conn->rollback();
        die("Error deducting wallet balance.");
    }

    // Update transaction status

    // Get complete transaction history (both sent & received)
    $param1= 'purchase';
    $platformName='Trust Toradora';
    $transactionCustomer = "INSERT INTO transactions (sender_name, receiver_name, amount, type, timestamp) 
    VALUES (?, ?, ?, ?, NOW())";
    $stmtTransaction = $conn->prepare($transactionCustomer);
    $stmtTransaction->bind_param("ssds", $username, $platformName, $total_price, $param1);
    if (!$stmtTransaction->execute()) {
        $conn->rollback();
        die("Error inserting transaction record.");
    }

    //transfer to platform's wallet (wallet using username)
    $platformNew_balance = $platformWallet_balance + $total_price;
    echo $platformNew_balance;
    $stmtPlatform = $conn->prepare("UPDATE wallet SET wallet_balance = ? WHERE usernames = ?");
    $stmtPlatform->bind_param("ds", $platformNew_balance, $platformName);
    if (!$stmtPlatform->execute()) {
        $conn->rollback();
        die("Error deducting wallet balance.");
    }

}
else{
    echo $payment_method;
    exit;
}

// Delete from cart (check if 'name' is correct column)
$stmt = $conn->prepare("DELETE FROM $tableName WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    $conn->rollback();
    die("Error clearing cart.");
}

// Update order status to "purchased"
$stmt = $conn->prepare("UPDATE orders SET order_status = 'purchased', date = ? WHERE order_id = ?");
$stmt->bind_param("si", $clickDate, $order_id);
if (!$stmt->execute()) {
    $conn->rollback();
    die("Error updating order: " . $stmt->error);
}

// Fetch order's state
$stmt = $conn->prepare("SELECT state FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $conn->rollback();
    die("Order not found.");
}

$order = $result->fetch_assoc();
$order_state = $order['state'];

// Find an available rider in the same state
$stmt = $conn->prepare("SELECT rider_id FROM rider WHERE available = 1 AND state = ? LIMIT 1");
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
    $conn->rollback();
    die("Invalid product data.");
}

$product_ids_str = implode(',', array_map('intval', $product_ids));
$quantities = $_SESSION['quantities'];

$sql_select = "SELECT product_id, stock, status FROM products WHERE product_id IN ($product_ids_str)";
$result = $conn->query($sql_select);

if (!$result) {
    $conn->rollback();
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
    if (!$stmt->execute()) {
        $conn->rollback();
        die("Error updating product stock.");
    }
}

// Commit transaction
$conn->commit();

$_SESSION['orders_id'] = $order_id + 1;
header("Location: success.php");
exit;
?>