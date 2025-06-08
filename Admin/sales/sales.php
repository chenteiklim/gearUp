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
        
.message-container {
  
  background-color: rgba(0, 0, 0, 0.7);
  position: fixed;
  padding-left: 120px;
  padding-right: 120px;
  padding-top: 90px;
  padding-bottom: 90px;
  color: white;
  font-size: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
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
           o.total_price,
            COUNT(DISTINCT o.order_id) AS completed_orders,
            SUM(oi.quantity) AS total_items
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        WHERE o.order_status = 'purchased'
    ";
    $salesResult = $conn->query($salesQuery);
    $salesData = $salesResult->fetch_assoc();

    // Top 5 Products
    $topProductsQuery = "
        SELECT 
            p.product_name,
            SUM(oi.quantity) AS total_sold,
            o.total_price AS total_revenue
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        JOIN orders o ON oi.order_id = o.order_id
        WHERE o.order_status = 'purchased'
        GROUP BY oi.product_id
        ORDER BY total_sold DESC
        LIMIT 5
    ";
    $topProductsResult = $conn->query($topProductsQuery);

    // Sales by Store
  $storeSalesQuery = "
    SELECT 
        s.storeName, s.sellerName, s.seller_id, u.role,
        o.total_price AS total_store_sales,
        ROUND(o.total_price * 0.05, 2) AS commission,
        ROUND(o.total_price * 0.95, 2) AS seller_earnings
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    JOIN seller s ON p.seller_id = s.seller_id
    JOIN users u ON s.user_id = u.user_id  -- << join using user_id in seller
    JOIN orders o ON oi.order_id = o.order_id
    WHERE o.order_status = 'purchased' 
    GROUP BY s.storeName
";
    $storeSalesResult = $conn->query($storeSalesQuery);
    ?>

    <h2>Overall Metrics</h2>
    <div id="messageContainer"></div>

    <ul>
        <li><strong>Total Sales:</strong> RM <?= number_format($salesData['total_price'], 2) ?></li>
        <li><strong>Shipping Price: </strong> RM 9.00 </li>
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
<form method="post" action="distributePayout.php">
<table>
    <tr>
        <th>Seller Name</th>
        <th>Store Name</th>
        <th>Total Sales (RM)</th>
        <th>Commission (5%)</th>
        <th>Seller Earnings</th>
        <th>Distribute</th>
    </tr>
 <?php while ($row = $storeSalesResult->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['sellerName']) ?></td>
    <td><?= htmlspecialchars($row['storeName']) ?></td>
    <td><?= number_format($row['total_store_sales'], 2) ?></td>
    <td><?= number_format($row['commission'], 2) ?></td>
    <td><?= number_format($row['seller_earnings'], 2) ?></td>
    <td>
        <?php if ($row['role'] === 'seller'): ?>
            <button class="button" type="submit" name="distribute[]" value="<?= $row['seller_id'] ?>">Distribute</button>
        <?php else: ?>
        
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</table>
</form>
</div>
</body>
</html>

<?php $conn->close(); ?>

<script>
    window.onload = function() {

    var urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    const message2 = urlParams.get('message2');


  
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
      }, 3000);
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
      }, 3000);
    }
}
</script>