<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>Access Denied</h1><p>You do not have permission to view this page.</p>";
    exit;
}
$username=$_SESSION['adminUsername'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fbff;
            margin: 30px;
        }

        #container {
            margin-left: 250px;
        }

        h1, h2 {
            color: #2c3e50;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            padding: 8px 0;
            font-size: 16px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 40px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<div id="container">
    <h1>Sales Summary (Admin)</h1>

    <?php
    // Total Sales Amount
    $salesQuery = "
        SELECT 
            SUM(oi.quantity * oi.price) AS total_sales,
            COUNT(DISTINCT o.order_id) AS completed_orders,
            SUM(oi.quantity) AS total_items
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        WHERE o.order_status = 'Delivered'
    ";
    $salesResult = $conn->query($salesQuery);
    $salesData = $salesResult->fetch_assoc();

    // Top 5 Products
    $topProductsQuery = "
        SELECT 
            p.product_name,
            SUM(oi.quantity) AS total_sold,
            SUM(oi.quantity * oi.price) AS total_revenue
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        JOIN orders o ON oi.order_id = o.order_id
        WHERE o.order_status = 'Delivered'
        GROUP BY oi.product_id
        ORDER BY total_sold DESC
        LIMIT 5
    ";
    $topProductsResult = $conn->query($topProductsQuery);

    // Sales by Store
    $storeSalesQuery = "
        SELECT 
            s.storeName,
            SUM(oi.quantity * oi.price) AS total_store_sales
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        JOIN seller s ON p.seller_id = s.seller_id
        JOIN orders o ON oi.order_id = o.order_id
        WHERE o.order_status = 'Delivered'
        GROUP BY s.storeName
    ";
    $storeSalesResult = $conn->query($storeSalesQuery);
    ?>

    <h2>Overall Metrics</h2>
    <ul>
        <li><strong>Total Sales:</strong> RM <?= number_format($salesData['total_sales'], 2) ?></li>
        <li><strong>Total Completed Orders:</strong> <?= $salesData['completed_orders'] ?></li>
        <li><strong>Total Products Sold:</strong> <?= $salesData['total_items'] ?></li>
    </ul>

    <h2>Top 5 Best-Selling Products</h2>
    <table>
        <tr>
            <th>Product</th>
            <th>Quantity Sold</th>
            <th>Total Revenue (RM)</th>
        </tr>
        <?php while ($row = $topProductsResult->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= $row['total_sold'] ?></td>
            <td><?= number_format($row['total_revenue'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Sales by Store</h2>
    <table>
        <tr>
            <th>Store Name</th>
            <th>Total Sales (RM)</th>
        </tr>
        <?php while ($row = $storeSalesResult->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['storeName']) ?></td>
            <td><?= number_format($row['total_store_sales'], 2) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>
</body>
</html>

<?php $conn->close(); ?>