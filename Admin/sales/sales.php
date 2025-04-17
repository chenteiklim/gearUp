<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();

// Check if admin is logged in
if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;
}

$username = $_SESSION['adminUsername'];


include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/encryption_helper.php';

// Fetch orders
$sql = "SELECT * FROM orders";
$resultOrder = $conn->query($sql);

?>

<head>
    <title>Product</title>
    <link rel="stylesheet" href="sales.css">
</head>

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/adminNavbar.php';

// Display orders in a table
if ($resultOrder->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Order ID</th>
                <th>Username</th>
                <th>Store Name</th>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>order status</th>
                <th>rider_id</th>
            </tr>";
    
    while ($row = $resultOrder->fetch_assoc()) {
        echo "<tr>
                <td>{$row['order_id']}</td>  <!-- Ensure this column exists -->
                <td>{$row['usernames']}</td>
                <td>{$row['store_name']}</td>
                <td>";
        
        // Check if image exists before displaying
        if (!empty($row['image'])) {
            echo "<img id='img' src='/inti/gadgetShop/assets/" . htmlspecialchars($row['image']) . "' alt='Product Image' width='50' height='50'>";
        } else {
            echo "No Image Available";
        }

        echo "</td>
                <td>{$row['product_name']}</td>
                <td>{$row['quantity']}</td>
                <td>RM " . ($row['price'] * $row['quantity']) . "</td>
                <td>{$row['date']}</td>
                <td>{$row['order_status']}</td>
                <td>{$row['assigned_rider']}</td>

              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No orders found.</p>";
}

$conn->close();
?>
