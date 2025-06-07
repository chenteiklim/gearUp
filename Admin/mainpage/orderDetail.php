<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

session_start();

// Only allow admin
if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>You do not have permission to access this page.</p>";
    exit;
}

$username = $_SESSION['adminUsername'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php';

$sql = "
SELECT 
    o.order_id,
    o.order_date,
    o.order_status,
    u.usernames,
    oi.quantity,
    oi.price,
    p.product_name,
    p.image,
    s.storeName
FROM orders o
JOIN users u ON o.user_id = u.user_id
JOIN order_items oi ON o.order_id = oi.order_id
JOIN products p ON oi.product_id = p.product_id
JOIN seller s ON p.seller_id = s.seller_id
ORDER BY o.order_date DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Orders - Admin</title>
    <style>
            body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 30px;
            background-color: #f7f9fc;
        }
        #container{
            margin-left:250px;
        }

        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        h2 {
            margin-top: 40px;
            color: #34495e;
        }

        .order-header {
            background-color: #ecf0f1;
            padding: 10px;
            border-left: 5px solid #3498db;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 50px;
            box-shadow: 0 0 8px rgba(0,0,0,0.05);
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        img {
            width: 60px;
            border-radius: 5px;
        }

        .no-orders {
            font-style: italic;
            color: #888;
        }
    </style>
</head>
<body>
<div id='container'>

<h1>All Orders (Admin View)</h1>

<?php

if ($result->num_rows > 0) {
    $currentOrderId = null;
    while ($row = $result->fetch_assoc()) {
        if ($currentOrderId !== $row['order_id']) {
            if ($currentOrderId !== null) {
                echo "</table>";
            }
            $currentOrderId = $row['order_id'];
            echo "<div class='order-header'><h2>Order #{$row['order_id']}</h2>
                  <p><strong>Customer:</strong> {$row['usernames']} |
                     <strong>Status:</strong> {$row['order_status']} |
                     <strong>Date:</strong> {$row['order_date']}</p></div>";
            echo "<table>
                    <tr>
                        <th>Product</th>
                        <th>Store</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price (Each)</th>
                        <th>Total</th>
                    </tr>";
        }

        $total = $row['quantity'] * $row['price'];
        echo "<tr>
                <td>{$row['product_name']}</td>
                <td>{$row['storeName']}</td>
                <td><img src='/inti/gearUp/assets/" . htmlspecialchars($row['image']) . "' alt='Product'></td>
                <td>{$row['quantity']}</td>
                <td>RM {$row['price']}</td>
                <td>RM $total</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p class='no-orders'>No orders found.</p>";
}

$conn->close();
?>
</div>

</body>
</html>