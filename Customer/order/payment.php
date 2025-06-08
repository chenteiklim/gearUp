<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

session_start();


$username = $_SESSION['username'];

$payment_method = $_POST['payment_method']; // "COD" or "wallet"

$username=$_SESSION['username'];
// Get customer's user_id
$stmt = $conn->prepare("SELECT * FROM users WHERE usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$user_id = $row['user_id'];
$state=$row['state'];

// Get Platform's user_id
$stmt = $conn->prepare("SELECT user_id FROM users WHERE role = 'admin'");
$stmt->execute(); // No bind_param needed since there's no placeholder
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$platform_user_id = $row['user_id'];

//get the latest order_id
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];
    echo $order_id;
    $total_price = $row['total_price'];
    echo $total_price;
} else {
    $order_id = null; // No orders found
}

// Get Customer's wallet balance (wallet table uses user_id)
$stmt = $conn->prepare("SELECT * FROM wallet WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$customerWallet_result = $stmt->get_result();

$customerWallet = $customerWallet_result->fetch_assoc();
$customerWallet_balance = (float) $customerWallet['wallet_balance'];
echo $customerWallet_balance;
 
// Get GearUp's wallet balance (wallet table uses user_id)
$stmtplatform = $conn->prepare("SELECT * FROM wallet WHERE user_id = ?");
$stmtplatform->bind_param("s", $platform_user_id);
$stmtplatform->execute();
$platformWallet_result = $stmtplatform->get_result();

$platformWallet = $platformWallet_result->fetch_assoc();
$platformWallet_balance = (float)($platformWallet['wallet_balance'] ?? 99.00);

 
if ($payment_method === "walletSelection") {
    if ($customerWallet_balance < $total_price) {
        $conn->rollback();
        $message = "Insufficient wallet balance";
        header("Location: checkOut.php?message=" . urlencode($message));
        exit;
    }

   // Deduct from customer's wallet)
    $customerWallet_balance = $customerWallet_balance - $total_price;
    $stmtDeduct = $conn->prepare("UPDATE wallet SET wallet_balance = ? WHERE user_id = ?");
    $stmtDeduct->bind_param("ds", $customerWallet_balance, $user_id);
    if (!$stmtDeduct->execute()) {
        $conn->rollback();
        die("Error deducting wallet balance.");
    }

    // Get complete transaction history (both sent & received)
    $param1= 'purchase';
    $transactionCustomer = "INSERT INTO transactions (sender_id, receiver_id, amount, type, timestamp) 
    VALUES (?, ?, ?, ?, NOW())";
    $stmtTransaction = $conn->prepare($transactionCustomer);
    $stmtTransaction->bind_param("ssds", $user_id, $platform_user_id, $total_price, $param1);
    if (!$stmtTransaction->execute()) {
        $conn->rollback();
        die("Error inserting transaction record.");
    }

    //transfer to platform's wallet 
    $platformNew_balance = $platformWallet_balance + $total_price;
    echo $platformNew_balance;
    $stmtPlatform = $conn->prepare("UPDATE wallet SET wallet_balance = ? WHERE user_id = ?");
    $stmtPlatform->bind_param("ds", $platformNew_balance, $platform_user_id);
    if (!$stmtPlatform->execute()) {
        $conn->rollback();
        die("Error deducting wallet balance.");
    }
    
}  
echo $order_id;
// Update order status to "purchased"
$stmt = $conn->prepare("UPDATE orders SET order_status = 'purchased', order_date = NOW() WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();


//Get All Products and Quantities for the Order
$stmt = $conn->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];
        
    //Check Stock
    $checkStock = $conn->prepare("SELECT stock FROM products WHERE product_id = ?");
    $checkStock->bind_param("i", $product_id);
    $checkStock->execute();
    $stockResult = $checkStock->get_result()->fetch_assoc();
    
    if ($stockResult['stock'] < $quantity) {
        $conn->rollback(); // or handle gracefully
        header("Location: checkOut.php?message2=" . urlencode($message2));
    }

    // Reduce stock
    $updateStock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
    $updateStock->bind_param("ii", $quantity, $product_id);
    $updateStock->execute();
}


header("Location: success.php"); 
exit;
?>