<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $senderName = $_GET['senderName'] ?? '';
    $receiverName = $_GET['receiverName'] ?? '';
    if (empty($senderName) || empty($receiverName)) {
        echo json_encode(["error" => "Invalid request: sender or receiver missing"]);
        exit;
    }
    // Both possible chat_room orders
    $chat_room1 = $senderName . "_" . $receiverName;
    $chat_room2 = $receiverName . "_" . $senderName;
    // Prepare SQL with OR condition to accept both chat_room orders
    $stmt = $conn->prepare("SELECT senderName, message, timestamp FROM messages WHERE chat_room = ? OR chat_room = ? ORDER BY timestamp ASC");
    if (!$stmt) {
        echo json_encode(["error" => "SQL prepare error: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("ss", $chat_room1, $chat_room2);
    if (!$stmt->execute()) {
        echo json_encode(["error" => "SQL execution error: " . $stmt->error]);
        exit;
    }
    $result = $stmt->get_result();
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode($messages);
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>