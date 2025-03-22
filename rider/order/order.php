<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();

$username = $_SESSION['username'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/encryption_helper.php';
function decrypt_address($encrypted_address) {
    global $encryption_key, $encryption_iv;
    return openssl_decrypt($encrypted_address, 'aes-256-cbc', $encryption_key, 0, $encryption_iv);
}

$selectRiderIdSql = "SELECT * FROM rider WHERE username = '$username'";
$resultRiderId = $conn->query($selectRiderIdSql);
$row = $resultRiderId->fetch_assoc();
$rider_id = $row['rider_id'];

// Fetch orders
$sql = "SELECT * FROM orders WHERE assigned_rider = '$rider_id'";
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
    ?><div id="messageContainer"></div>
    <?php
    echo "<table border='1'>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Address</th>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Action</th>
            </tr>";

    while ($row = $resultOrder->fetch_assoc()) {
        $decrypted_address = decrypt_address($row['address']);  
        echo "<tr>
                <td>{$row['order_id']}</td>
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
                <td>
                    <form method='POST' action='sent.php'>
                        <input type='hidden' name='order_id' value='{$row['order_id']}'>
                        <button class='button' type='submit'>Sent</button>
                    </form>
                </td>
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
    window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    const message2 = urlParams.get('message2');
  
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
      }, 10000);
    }
  }
</script>