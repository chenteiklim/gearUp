<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

session_start();
$usernames = $_SESSION['username'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Customer/customerNavbar.php';

// Fetch user email
$stmt = $conn->prepare("SELECT email FROM users WHERE usernames = ?");
$stmt->bind_param("s", $usernames);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $encrypted_email = $user['email'];
} else {
    die("No user found with that username.");
}
$stmt->close();

// Fetch orders
$selectOrdersQuery = "SELECT * FROM orders WHERE email='$encrypted_email' AND order_status <> 'cart' ORDER BY order_id ASC";
$selectOrdersResult = $conn->query($selectOrdersQuery);

if (!$selectOrdersResult) {
    die("Query failed: " . $conn->error);
}

$orders = [];
while ($row = $selectOrdersResult->fetch_assoc()) {
    $orders_id = $row['orders_id'];
    $store_name = $row['store_name']; // Make sure this column exists in your DB
    $product_name = $row['product_name'];
    $image = $row['image'];
    $imageUrl = "/inti/gearUp/assets/" . $image;
    $quantity = $row['quantity'];
    $total_price = $row['total_price'];
    $order_status = $row['order_status'];

    $orders[$orders_id][$store_name][] = [
        'product_name' => $product_name,
        'image' => $imageUrl,
        'quantity' => $quantity,
        'total_price' => $total_price,
        'order_status' => $order_status
    ];
}

// Get all orders with refund already requested and their status
$refundQuery = $conn->query("SELECT orders_id, status, rejectReason FROM refundRequest WHERE usernames = '$usernames'");
$refundedOrders = [];

while ($row = $refundQuery->fetch_assoc()) {
    $refundedOrders[$row['orders_id']] = [
        'status' => $row['status'],
        'rejectReason' => $row['rejectReason'] // Store rejection reason
    ]; // Store the refund status and rejection reason
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking</title>
    <link rel="stylesheet" href="tracking.css">
</head>
<body>

<div id="container">
    <?php foreach ($orders as $orders_id => $stores): ?>
        <div class="order-group">
            <h3>Order #<?= $orders_id ?></h3>

            <?php foreach ($stores as $store_name => $items): ?>
                <div class="store-group">
                    <h4>Store: <?= htmlspecialchars($store_name) ?></h4>

                    <?php if (array_key_exists($orders_id, $refundedOrders)): ?>
                        <!-- Refund requested, show status buttons -->
                        <?php 
                            $refundStatus = $refundedOrders[$orders_id]['status'];
                            $rejectionReason = $refundedOrders[$orders_id]['rejectReason']; 
                            if ($refundStatus === 'approved'): ?>
                                <button disabled style="background-color: #4CAF50; color: white;">Refund Approved</button>
                            <?php elseif ($refundStatus === 'rejected'): ?>
                                <button disabled style="background-color: #f44336; color: white;">Refund Rejected</button>
                                <!-- View Reject Reason Button -->
                                <button class="view-reason-btn" data-reason="<?= htmlspecialchars($rejectionReason) ?>" onclick="showRejectReason(this)">View Reject Reason</button>
                            <?php else: ?>
                                <button disabled style="background-color: grey; color: white;">Refund Requested</button>
                            <?php endif; ?>
                    <?php else: ?>
                        <!-- Show request refund button -->
                        <button class="refund-btn" data-order-id="<?= $orders_id ?>">Request Refund</button>
                    <?php endif; ?>
                    
                    <div id="store-<?= $orders_id ?>-<?= $store_name ?>">
                        <?php foreach ($items as $index => $item): ?>
                            <div class="order-item" <?= $index > 0 ? 'style="display: none;"' : '' ?>>
                                <img src="<?= $item['image'] ?>" alt="">
                                <div class="order-info">
                                    <div><strong><?= htmlspecialchars($item['product_name']) ?></strong></div>
                                    <div>Quantity: x<?= $item['quantity'] ?></div>
                                    <div>Total: RM<?= $item['total_price'] ?></div>
                                    <div class="order-status"><?= htmlspecialchars($item['order_status']) ?></div>

                                    <!-- Rate Product Button -->
                                    <?php if ($item['order_status'] === 'sent'): ?>
                                        <button class="rate-product-btn" onclick="location.href='rateProduct.php?order_id=<?= $orders_id ?>&product_name=<?= urlencode($item['product_name']) ?>'">Rate this product</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal for displaying rejection reason -->
<div id="rejectReasonModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeRejectReason()">&times;</span>
        <h4>Rejection Reason</h4>
        <p id="rejectReasonText"></p>
    </div>
</div>

<script>
    function showRejectReason(button) {
        var reason = button.getAttribute('data-reason');
        document.getElementById('rejectReasonText').innerText = reason;
        document.getElementById('rejectReasonModal').style.display = 'flex';
    }

    function closeRejectReason() {
        document.getElementById('rejectReasonModal').style.display = 'none';
    }
</script>

</body>
<script src="trackings.js?v=1.0.1"></script>

</html>