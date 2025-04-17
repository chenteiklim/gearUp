<?php
header('Content-Type: application/json'); // Ensure response is JSON
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $customerName = $_GET['senderName'] ?? ''; // Previously "seller"
    $sellerName = $_GET['receiverName'] ?? ''; // Previously "customer"

    if (empty($customerName) || empty($sellerName)) {
        echo json_encode(["error" => "Invalid request: sender or receiver missing"]);
        exit;
    }

    // Debug: Log request
    error_log("Fetching messages for: Customer: $customerName, Seller: $sellerName");

    // Generate chat room identifier
    $chat_room = ($customerName < $sellerName) ? "{$customerName}_{$sellerName}" : "{$sellerName}_{$customerName}";

    // Fetch messages from the database
    $stmt = $conn->prepare("SELECT senderName, message, timestamp FROM messages WHERE chat_room = ? ORDER BY timestamp ASC");
    if (!$stmt) {
        echo json_encode(["error" => "SQL prepare error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("s", $chat_room);
    if (!$stmt->execute()) {
        echo json_encode(["error" => "SQL execution error: " . $stmt->error]);
        exit;
    }

    $result = $stmt->get_result();
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    if (empty($messages)) {
        error_log("No messages found for chat room: $chat_room");
    }

    echo json_encode($messages);
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>