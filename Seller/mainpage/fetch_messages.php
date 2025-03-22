<?php
header('Content-Type: application/json'); // Ensure JSON response
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';
session_start();
$username=$_SESSION['username'];

if (!isset($_GET['customer_id'])) {
    echo json_encode(["error" => "No customer ID provided"]);
    exit;
}
if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];
    $messages = [];

    $query = "SELECT sender_id, message FROM messages WHERE customer_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    echo json_encode($messages);
} else {
    echo json_encode(["error" => "No customer ID provided"]);
}
?>
