<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

function assignUnassignedOrders($conn) {
    // Get unassigned orders with state
    $sql = "SELECT order_id, state FROM orders WHERE assigned_rider IS NULL ORDER BY date ASC LIMIT 10";
    $ordersResult = $conn->query($sql);

    if ($ordersResult->num_rows > 0) {
        while ($order = $ordersResult->fetch_assoc()) {
            $order_id = $order['order_id'];
            $order_state = $order['state']; // Fetch state directly

            // Find an available rider in the same state
            $sql = "SELECT rider_id FROM rider WHERE available = 1 AND state = ? LIMIT 1";
            $stmt1 = $conn->prepare($sql);
            $stmt1->bind_param("s", $order_state);
            $stmt1->execute();
            $riders = $stmt1->get_result();

            if ($riders->num_rows > 0) {
                $rider = $riders->fetch_assoc();
                $assigned_rider = $rider['rider_id'];

                // Assign the rider to the order
                $stmt2 = $conn->prepare("UPDATE orders SET assigned_rider = ?, order_status = 'assigned' WHERE order_id = ?");
                $stmt2->bind_param("ii", $assigned_rider, $order_id);
                $stmt2->execute();

                // Update rider table: Set currentOrder and mark as unavailable
                $stmt3 = $conn->prepare("UPDATE rider SET currentOrder = ?, available = 0, last_assigned = NOW() WHERE rider_id = ?");
                $stmt3->bind_param("ii", $order_id, $assigned_rider);
                $stmt3->execute();

                echo " Order ID: $order_id assigned to Rider ID: $assigned_rider.<br>";
            } else {
                echo " No available riders in state: $order_state.<br>";
            }
        }
    } else {
        echo " No unassigned orders found.<br>";
    }
}

// Execute the function
assignUnassignedOrders($conn);
$conn->close();
?>