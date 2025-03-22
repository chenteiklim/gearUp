<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

// Step 1: Fetch a pending order with state
$sql = "SELECT orders_id, state FROM orders WHERE order_status = 'purchased' AND assigned_rider IS NULL LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("No pending orders.");
}

$order = $result->fetch_assoc();
$order_id = $order['orders_id'];
$order_state = $order['state'];

if (!$order_state) {
    die("Order state is missing.");
}

// Step 2: Fetch available riders in the same state
$sql = "SELECT rider_id FROM rider WHERE available = 1 AND state = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $order_state);
$stmt->execute();
$riders = $stmt->get_result();

if ($riders->num_rows == 0) {
    die("No available riders in state: $order_state.");
}

// Step 3: Assign the first available rider in the same state
$rider = $riders->fetch_assoc();
$assigned_rider = $rider['rider_id'];

// Step 4: Update order with assigned rider
$stmt = $conn->prepare("UPDATE orders SET assigned_rider = ?, order_status = 'assigned' WHERE orders_id = ?");
$stmt->bind_param("ii", $assigned_rider, $orders_id);
$stmt->execute();

// Step 5: Update rider table (set currentOrder & make unavailable)
$stmt = $conn->prepare("UPDATE rider SET currentOrder = ?, available = 0 WHERE rider_id = ?");
$stmt->bind_param("ii", $order_id, $assigned_rider);
$stmt->execute();

echo "Assigned Rider ID: $assigned_rider to Order ID: $order_id and updated rider status.";

$conn->close();
?>