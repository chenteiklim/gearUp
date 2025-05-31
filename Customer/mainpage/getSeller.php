<?php
header('Content-Type: application/json'); // Ensure JSON response

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

$query = "SELECT user_id, usernames FROM users WHERE role = 'seller'";
$result = $conn->query($query);

$customers = [];
while ($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

echo json_encode($customers);
?>