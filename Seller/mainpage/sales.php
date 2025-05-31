<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;
}

$username = $_SESSION['username'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/encryption_helper.php';

$selectSellerId = "SELECT seller_id FROM users WHERE usernames = ?";
$stmt = $conn->prepare($selectSellerId);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $sellerId = $row['seller_id'];
} else {
    // Handle case where seller_id not found
    echo "Seller ID not found for username: $username";
    exit;
}

$sql = "SELECT * FROM orders WHERE seller_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sellerId);
$stmt->execute();
$resultOrder = $conn->query();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product</title>
    <link rel="stylesheet" href="sales.css">
</head>
<body>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminNavbar.php'; ?>

<?php if ($resultOrder->num_rows > 0): ?>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Username</th>
            <th>Store Name</th>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Order Status</th>
            <th>Rider ID</th>
        </tr>
        <?php while ($row = $resultOrder->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['order_id']) ?></td>
                <td><?= htmlspecialchars($row['usernames']) ?></td>
                <td><?= htmlspecialchars($row['store_name']) ?></td>
                <td>
                    <?php if (!empty($row['image'])): ?>
                        <img id="img" src="/inti/gearUp/assets/<?= htmlspecialchars($row['image']) ?>" alt="Product Image" width="50" height="50">
                    <?php else: ?>
                        No Image Available
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td>RM <?= number_format($row['price'] * $row['quantity'], 2) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars($row['order_status']) ?></td>
                <td><?= htmlspecialchars($row['assigned_rider']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No orders found.</p>
<?php endif; ?>

<?php $conn->close(); ?>
</body>
</html>