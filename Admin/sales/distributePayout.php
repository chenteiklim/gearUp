<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['adminUsername'])) {
    echo "Access denied.";
    exit;
}

$adminUsername = $_SESSION['adminUsername'];

// Get admin user ID
$sql = "SELECT user_id FROM users WHERE usernames = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $adminUsername);
$stmt->execute();
$result = $stmt->get_result();
$adminRow = $result->fetch_assoc();

if (!$adminRow) {
    echo "Admin user not found.";
    exit;
}

$adminId = $adminRow['user_id'];

// Start transaction
$conn->begin_transaction();

try {
    // Get orders not yet paid to seller, excluding admin as seller
    $sql = "
        SELECT 
            o.order_id,
            s.seller_id,
            s.sellerName,
            u.user_id AS seller_user_id,
            SUM(oi.quantity * oi.price) AS total_sale
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN products p ON oi.product_id = p.product_id
        JOIN seller s ON p.seller_id = s.seller_id
        JOIN users u ON s.sellerName = u.usernames
        WHERE o.order_status = 'purchased' AND o.wallet_status IS NULL AND u.user_id != ?
        GROUP BY o.order_id, s.seller_id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $message2 = "No Payout to distribute";
        header("Location: sales.php?message2=" . urlencode($message2));
        exit;
    }

    while ($row = $result->fetch_assoc()) {
        $orderId = $row['order_id'];
        $sellerUserId = $row['seller_user_id'];
        $totalSale = $row['total_sale'];

        $commission = $totalSale * 0.05;
        $sellerAmount = $totalSale - $commission;

        // 1. Add to seller wallet
        $stmt = $conn->prepare("UPDATE wallet SET wallet_balance = wallet_balance + ? WHERE user_id = ?");
        $stmt->bind_param("di", $sellerAmount, $sellerUserId);
        $stmt->execute();

        // 2. Add commission to admin wallet
        $stmt = $conn->prepare("UPDATE wallet SET wallet_balance = wallet_balance + ? WHERE user_id = ?");
        $stmt->bind_param("di", $commission, $adminId);
        $stmt->execute();

        // 3. Record transaction to seller
        $stmt = $conn->prepare("
            INSERT INTO transactions (sender_id, receiver_id, amount, type)
            VALUES (?, ?, ?, 'payout')
        ");
        $stmt->bind_param("iid", $adminId, $sellerUserId, $sellerAmount);
        $stmt->execute();

        // 4. Record commission
        $stmt = $conn->prepare("
            INSERT INTO transactions (sender_id, receiver_id, amount, type)
            VALUES (?, ?, ?, 'commission')
        ");
        $stmt->bind_param("iid", $sellerUserId, $adminId, $commission);
        $stmt->execute();

        // 5. Mark order as paid
        $stmt = $conn->prepare("UPDATE orders SET wallet_status = 'paid' WHERE order_id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
    }

    $conn->commit();
    $message = "Payout Distribute Successfully";
    header("Location: sales.php?message=" . urlencode($message));
} catch (Exception $e) {
    $conn->rollback();
    echo "Error processing payouts: " . $e->getMessage();
}

$conn->close();
?>