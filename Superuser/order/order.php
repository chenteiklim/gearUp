<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();

// Check if admin is logged in
if (!isset($_SESSION['emailAdmin'])) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;
}

$email = $_SESSION['emailAdmin'];

// Get admin username
$selectNameQuery = "SELECT username FROM superuser WHERE email = '$email'";
$resultAdmin = $conn->query($selectNameQuery);
$username = "Admin"; // Default if not found
if ($resultAdmin->num_rows > 0) {
    $row = $resultAdmin->fetch_assoc();
    $username = $row['username'];
}



include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/encryption_helper.php';
function decrypt_address($encrypted_address) {
    global $encryption_key, $encryption_iv;

    // Decrypt the address using AES-256-CBC
    $decrypted_address = openssl_decrypt($encrypted_address, 'aes-256-cbc', $encryption_key, 0, $encryption_iv);

    return $decrypted_address;
}
// Fetch orders
$sql = "SELECT * FROM orders";
$resultOrder = $conn->query($sql);

?>

<head>
    <title>Product</title>
    <link rel="stylesheet" href="order.css">
</head>
<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="Logo">
    <button class="button" id="home">Trust Toradora</button>
    <button class="button" id="name"><?php echo htmlspecialchars($username); ?></button>
    <form action="../login/logout.php" method="POST">
      <button type="submit" id="logout" class="button">Log Out</button>
    </form> 
</div>

<?php
// Display orders in a table
if ($resultOrder->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Order ID</th>
                <th>Username</th>
                <th>Address</th>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>order status</th>
                <th>rider_id</th>
            </tr>";
    
    while ($row = $resultOrder->fetch_assoc()) {
        $decrypted_address = decrypt_address($row['address']);  
        echo "<tr>
                <td>{$row['order_id']}</td>  <!-- Ensure this column exists -->
                <td>{$row['name']}</td>
                <td>" . htmlspecialchars($decrypted_address) . "</td>
                <td>";
        
        // Check if image exists before displaying
        if (!empty($row['image'])) {
            echo "<img id='img' src='/inti/gadgetShop/assets/" . htmlspecialchars($row['image']) . "' alt='Product Image' width='50' height='50'>";
        } else {
            echo "No Image Available";
        }

        echo "</td>
                <td>{$row['product_name']}</td>
                <td>RM {$row['price']}</td>
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

<script>
    document.getElementById("home").addEventListener("click", function() {
    window.location.href = "../mainpage/mainpage.php";
});
</script>