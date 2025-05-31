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

    // ✅ Generate consistent chat room
    $names = [$senderName, $receiverName];
    sort($names); // Ensure consistent order
    $chat_room = implode("_", $names);

    // Save message
    $stmt = $conn->prepare("INSERT INTO messages (chat_room, senderName, receiverName, senderRole, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $chat_room, $senderName, $receiverName, $sender_role, $message);

    if ($stmt->execute()) {
        // Send real-time notification
        $pusher->trigger($chat_room, 'new-message', [
            'sender_name' => $senderName,
            'message' => $message,
            'sender_role' => $sender_role
        ]);
        echo "Message sent!";
    } else {
        echo "Error saving message: " . $stmt->error;
    }
}
?>