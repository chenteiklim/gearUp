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

function decrypt_address($encrypted_address) {
    global $encryption_key, $encryption_iv;
    return openssl_decrypt($encrypted_address, 'aes-256-cbc', $encryption_key, 0, $encryption_iv);
}


// Fetch sellers with user info
$sql = "SELECT seller.*, users.usernames, users.email
        FROM seller
        JOIN users ON seller.user_id = users.user_id";
$resultSeller = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approve Sellers</title>
    <link rel="stylesheet" href="approveSeller.css">
</head>
<body>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/Admin/adminNavbar.php'; ?>

<h2>Pending Sellers</h2>

<?php

if ($resultSeller->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Store Name</th>
                <th>Contact Number</th>
                <th>Action</th>
            </tr>";
    
    while ($row = $resultSeller->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['usernames']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['storeName']) . "</td>
                <td>" . htmlspecialchars(decrypt_address($row['contact'])) . "</td>
                <td>
                    <form method='POST' action='approveSellerAction.php'>
                        <input type='hidden' name='seller_id' value='" . htmlspecialchars($row['seller_id']) . "'>
                        <button class='button' type='submit'>Approve</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No sellers found.</p>";
}

$conn->close();
?>
</body>
</html>