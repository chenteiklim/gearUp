<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/pusher.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $message = trim($_POST['message']);
    $senderName = trim($_POST['senderName']);    // Seller
    $receiverName = trim($_POST['receiverName']); // Customer

    if (empty($message) || empty($senderName) || empty($receiverName)) {
        echo "Invalid input.";
        exit();
    }

    // Validate sender
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

    // Validate receiver
    $stmt = $conn->prepare("SELECT role FROM users WHERE usernames = ?");
    $stmt->bind_param("s", $receiverName);
    $stmt->execute();
    $result = $stmt->get_result();
    $receiver = $result->fetch_assoc();
    if (!$receiver) {
        echo "Receiver not found!";
        exit();
    }

    // Generate consistent chat room ID
    $chat_room = ($senderName < $receiverName)
        ? "{$senderName}_{$receiverName}"
        : "{$receiverName}_{$senderName}";

    // Insert message into database
    $stmt = $conn->prepare("INSERT INTO messages (chat_room, senderName, receiverName, senderRole, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $chat_room, $senderName, $receiverName, $sender_role, $message);
    if ($stmt->execute()) {
        echo "Message sent!";

        // Optional: Trigger Pusher event (if you implement real-time updates)
        // $data = ['sender' => $senderName, 'message' => $message];
        // $pusher->trigger('chat_channel', 'new_message', $data);

    } else {
        echo "Error saving message: " . $stmt->error;
    }
}
?>