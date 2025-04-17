<?php
header('Content-Type: application/json'); // Ensure JSON response

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

$query = "SELECT seller_id, usernames FROM users WHERE role = 'seller'";
$result = $conn->query($query);

$sellers = [];
while ($row = $result->fetch_assoc()) {
    $sellers[] = $row;
}

echo json_encode($sellers);
?>