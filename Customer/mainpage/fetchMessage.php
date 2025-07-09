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

    $chat_room1 = $senderName . "_" . $receiverName;
    $chat_room2 = $receiverName . "_" . $senderName;

    $stmt = $conn->prepare("
        SELECT 
            m.message, m.timestamp, 
            u.usernames AS senderUsername,
            COALESCE(s.storeName, u.usernames) AS displayName
        FROM messages m
        JOIN users u ON m.senderName = u.usernames
        LEFT JOIN seller s ON u.user_id = s.user_id
        WHERE m.chat_room = ? OR m.chat_room = ?
        ORDER BY m.timestamp ASC
    ");

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
        $messages[] = [
            'senderName' => $row['displayName'],  // use storeName if available
            'message' => $row['message'],
            'timestamp' => $row['timestamp']
        ];
    }

    echo json_encode($messages);
} else {
    echo json_encode(["error" => "Invalid request method"]);
}