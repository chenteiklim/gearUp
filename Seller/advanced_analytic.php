<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();
$username = $_SESSION['username'] ?? '';

// Get seller_id
$seller_id = null;
$stmt = $conn->prepare("
    SELECT s.seller_id 
    FROM users u 
    JOIN seller s ON u.user_id = s.user_id 
    WHERE u.usernames = ?
");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $seller_id = $row['seller_id'];
}
if (!$seller_id) {
    echo "Seller not found.";
    exit;
}

// Filter date
$start = $_GET['start_date'] ?? date("Y-m-d", strtotime("-30 days"));
$end = $_GET['end_date'] ?? date("Y-m-d");

// Query sales data
$stmt = $conn->prepare("
    SELECT DATE(o.order_date) AS order_date, 
           SUM(o.total_price) AS daily_sales
    FROM orders o
    JOIN order_items oi ON o.order_id = oi.order_id
    JOIN products p ON oi.product_id = p.product_id
    WHERE o.order_status = 'purchased'
      AND p.seller_id = ?
      AND o.wallet_status = 'paid'
      AND DATE(o.order_date) BETWEEN ? AND ?
    GROUP BY DATE(o.order_date)
    ORDER BY order_date
");
$stmt->bind_param("iss", $seller_id, $start, $end);
$stmt->execute();
$dataResult = $stmt->get_result();

$labels = [];
$data = [];
while ($row = $dataResult->fetch_assoc()) {
    $labels[] = $row['order_date'];
    $data[] = $row['daily_sales'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Advanced Sales Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #container {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fc;
            padding: 20px;
            margin-left:30px;
            color: #333;
        }
        h1, h2 {
            color: #2c3e50;
        }
        #filters {
            margin-bottom: 30px;
        }
        input[type="date"], button {
            padding: 8px;
            margin-right: 10px;
        }
        .chart-container {
            width: 90%;
            max-width: 800px;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Seller/sellerNavbar.php';?>
<div id= "container">

<h1>📈 Advanced Sales Analytics</h1>

<div id="filters">
    <form method="GET">
        <label>Start Date:
            <input type="date" name="start_date" value="<?= $start ?>">
        </label>
        <label>End Date:
            <input type="date" name="end_date" value="<?= $end ?>">
        </label>
        <button type="submit">Apply Filter</button>
    </form>
</div>

<!-- Actual Chart with Dynamic Data -->
<div class="chart-container">
    <h2>Line Chart: Sales Over Time</h2>
    <canvas id="salesChart"></canvas>
</div>



<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Daily Sales (RM)',
                data: <?= json_encode($data) ?>,
                backgroundColor: 'rgba(58, 58, 126, 0.2)',
                borderColor: '#3a3a7e',
                borderWidth: 2,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    enabled: true
                },
                title: {
                    display: true,
                    text: 'Seller Daily Sales (RM)'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
</div>

</body>
</html>
<?php $conn->close(); ?>