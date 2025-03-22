<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

$rider_id = 8;  // You can change this dynamically if needed
$sql = "SELECT latitude, longitude FROM rider WHERE rider_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $rider_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["latitude" => null, "longitude" => null]);
}

$stmt->close();
$conn->close();
?>