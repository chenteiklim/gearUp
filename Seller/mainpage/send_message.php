<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/pusher.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $message = $_POST['message'];
    $senderName = $_POST['senderName'];  // Now refers to the seller
    $receiverName = $_POST['receiverName']; // Now refers to the customer

    // Generate chat room identifier
    $chat_room = ($senderName < $receiverName) ? "{$senderName}_{$receiverName}" : "{$receiverName}_{$senderName}";

    // Get sender role from users table (Now 'seller' instead of 'customer')
    $stmt = $conn->prepare("SELECT role FROM users WHERE usernames = ?");
    $stmt->bind_param("s", $senderName);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "User not found!";
        exit();
    }

    // Previously, `sender_role` could be 'customer' or 'seller', now ensure it is correct
    $sender_role = ($user['role'] === 'customer') ? 'seller' : 'customer'; 

    // Insert message into database
    $stmt = $conn->prepare("INSERT INTO messages (chat_room, senderName, receiverName, senderRole, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $chat_room, $senderName, $receiverName, $sender_role, $message);
    
    if ($stmt->execute()) {
        // Notify Pusher
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