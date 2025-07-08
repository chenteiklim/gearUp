<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

$usernames = $_SESSION['username'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Customer/customerNavbar.php';

// Get user_id
$stmt = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$stmt->bind_param("s", $usernames);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['user_id'];
} else {
    die("No user found with that username.");
}
$stmt->close();

// Get all orders (not cart)
$stmt = $conn->prepare("
    SELECT 
        o.order_id, oi.product_id, oi.order_item_id, oi.quantity,
         oi.price, o.order_status, o.order_date,
        p.product_name, p.image, s.storeName
    FROM 
        orders o
    JOIN 
        order_items oi ON o.order_id = oi.order_id
    JOIN 
        products p ON oi.product_id = p.product_id
    JOIN
        seller s ON p.seller_id = s.seller_id
    WHERE 
        o.user_id = ? AND o.order_status <> 'cart'
    ORDER BY 
        o.order_id ASC
");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {
    $order_item_id = $row['order_item_id'];
    $order_id = $row['order_id'];
    $store_name = $row['storeName'];
    $product_id = $row['product_id'];
    $product_name = $row['product_name'];
    $imageUrl = "/inti/gearUp/assets/" . $row['image'];
    $quantity = $row['quantity'];
    $item_price = $row['price'];
    $order_status = $row['order_status'];
    $order_date = $row['order_date'];

    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'order_date' => $order_date,
            'stores' => []
        ];
    }

    $orders[$order_id]['stores'][$store_name][] = [
        'order_item_id' => $order_item_id,
        'product_id' => $product_id,
        'product_name' => $product_name,
        'image' => $imageUrl,
        'quantity' => $quantity,
        'item_price' => $item_price,
        'order_status' => $order_status
    ];
}
$stmt->close();

// Get refund statuses
$refundQuery = $conn->query("SELECT order_item_id, status, 
rejectReason FROM refundRequest WHERE user_id = '$user_id'");
$refundedOrders = [];

while ($row = $refundQuery->fetch_assoc()) {
    $refundedOrders[$row['order_item_id']] = [
        'status' => $row['status'],
        'rejectReason' => $row['rejectReason']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Order Tracking</title>
    <link rel="stylesheet" href="tracking.css" />
</head>
<body>

<div id="container">
    <div id='messageContainer'></div>
    <?php if (empty($orders)): ?>
        <p style="text-align:center; font-size:18px; color:gray; margin-top:40px;">
            No order items found.
        </p>
    <?php else: ?>
        <?php foreach ($orders as $order_id => $orderData): ?>
            <div class="order-group">
                <h3>Order #<?= $order_id ?> — Order Date: <?= htmlspecialchars($orderData['order_date']) ?></h3>

                <?php foreach ($orderData['stores'] as $store_name => $items): ?>
                    <div class="store-group">
                        <h4>Store: <?= htmlspecialchars($store_name) ?></h4>

                        <div id="store-<?= $order_id ?>-<?= htmlspecialchars($store_name) ?>">
                            <?php foreach ($items as $item): ?>
                                <div class="order-item">
                                    <img src="<?= $item['image'] ?>" alt="" />
                                    <div class="order-info">
                                        <div><strong><?= htmlspecialchars($item['product_name']) ?></strong></div>
                                        <div>Order Item ID: <?= $item['order_item_id'] ?></div>
                                        <div>Quantity: x<?= $item['quantity'] ?></div>
                                        <div>Total: RM<?= $item['item_price'] ?></div>
                                        <div class="order-status"><?= htmlspecialchars($item['order_status']) ?></div>

                                        <?php if (array_key_exists($item['order_item_id'], $refundedOrders)): ?>
                                            <?php 
                                                $refundStatus = $refundedOrders[$item['order_item_id']]['status'];
                                                $rejectionReason = $refundedOrders[$item['order_item_id']]['rejectReason']; 
                                            ?>

                                            <?php if ($refundStatus === 'approved'): ?>
                                                <button disabled style="background-color:#4CAF50; color:white;">Refund Approved</button>
                                            <?php elseif ($refundStatus === 'rejected'): ?>
                                                <button disabled style="background-color:#f44336; color:white;">Refund Rejected</button>
                                                <button class="view-reason-btn" data-reason="<?= htmlspecialchars($rejectionReason) ?>" onclick="showRejectReason(this)">View Reject Reason</button>
                                            <?php else: ?>
                                                <button disabled style="background-color:gray; color:white;">Refund Requested</button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <button class="refund-btn" onclick="confirmRefund(<?= $item['order_item_id'] ?>)">Request Refund</button>
                                        <?php endif; ?>

                                        <?php if ($item['order_status'] === 'purchased'): ?>
                                            <button class="rate-product-btn" onclick="location.href='rateProduct.php?order_id=<?= $order_id ?>&product_name=<?= urlencode($item['product_name']) ?>'">Rate this product</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Rejection Modal -->
<div id="rejectReasonModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeRejectReason()">&times;</span>
        <h4>Rejection Reason</h4>
        <p id="rejectReasonText"></p>
    </div>
</div>

<script>
function showRejectReason(button) {
    const reason = button.getAttribute('data-reason');
    document.getElementById('rejectReasonText').innerText = reason;
    document.getElementById('rejectReasonModal').style.display = 'flex';
}

function closeRejectReason() {
    document.getElementById('rejectReasonModal').style.display = 'none';
}

function confirmRefund(orderItemId) {
    const confirmAction = confirm("Are you sure you want to request a refund for this product?");
    if (confirmAction) {
        window.location.href = `refundRequestForm.php?order_item_id=${orderItemId}`;
    }
}

window.onload = function() {

    var urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    const message2 = urlParams.get('message2');
    const message3 = urlParams.get('message3');

  
    if (message) {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = decodeURIComponent(message); // Decode the URL-encoded message
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        
        // Clear the message from the URL
        const url = new URL(window.location);
        url.searchParams.delete('message');
        window.history.replaceState({}, document.title, url);
      }, 5000);
    }
    
    if (message2) {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = decodeURIComponent(message2); // Decode the URL-encoded message
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        
        // Clear the message from the URL
        const url = new URL(window.location);
        url.searchParams.delete('message2');
        window.history.replaceState({}, document.title, url);
      }, 5000);
    }
    
    if (message3) {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = decodeURIComponent(message3); // Decode the URL-encoded message
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        
        // Clear the message from the URL
        const url = new URL(window.location);
        url.searchParams.delete('message3');
        window.history.replaceState({}, document.title, url);
      }, 5000);
    }
}


</script>

</body>
</html>