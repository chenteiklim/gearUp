<?php
header('Content-Type: application/json'); // Ensure JSON response

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

$query = "SELECT users.user_id, users.usernames, seller.seller_id
FROM users
JOIN seller ON users.user_id = seller.user_id
WHERE users.role = 'seller'";
$result = $conn->query($query);

$sellers = [];
while ($row = $result->fetch_assoc()) {
    $sellers[] = $row;
}

echo json_encode($sellers);
?>