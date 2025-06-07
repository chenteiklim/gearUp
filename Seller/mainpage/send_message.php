<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/pusher.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $message = $_POST['message'];
    $senderName = $_POST['senderName'];    // Seller
    $receiverName = $_POST['receiverName']; // Customer

    // Validate users
    $stmt = $conn->prepare("SELECT role FROM users WHERE usernames = ?");
    $stmt->bind_param("s", $senderName);
    $stmt->execute();
    $result = $stmt->get_result();
    $sender = $result->fetch_assoc();
    if (!$sender) {
        echo "Sender not found!";
        exit();
    }
    $sender_role = $sender['role'];

    $stmt = $conn->prepare("SELECT role FROM users WHERE usernames = ?");
    $stmt->bind_param("s", $receiverName);
    $stmt->execute();
    $result = $stmt->get_result();
    $receiver = $result->fetch_assoc();
    if (!$receiver) {
        echo "Receiver not found!";
        exit();
    }

        $chat_room = ($senderName < $receiverName) ? "{$senderName}_{$receiverName}" : "{$receiverName}_{$senderName}";

    // Save message
    $stmt = $conn->prepare("INSERT INTO messages (chat_room, senderName, receiverName, senderRole, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $chat_room, $senderName, $receiverName, $sender_role, $message);
    if ($stmt->execute()) {
            echo "Message sent!";
        } else {
            echo "Error saving message: " . $stmt->error;
        }
}
?>