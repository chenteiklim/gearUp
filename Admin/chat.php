<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

// Admin access check
if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>Access Denied</h1><p>You must be logged in as admin.</p>";
    exit;
}
$username =$_SESSION['adminUsername'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View All Chats</title>
    <style>
        #container {
            margin-left: 300px;
            padding: 20px;
        }
        .chat-box {
            background: #f9f9f9;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 6px;
        }
        .chat-header {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .message {
            margin-left: 20px;
            margin-bottom: 5px;
        }
        .timestamp {
            color: #999;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<div id="container">
    <h1>📨 All Chat Messages</h1>

    <?php
    $stmt = $conn->prepare("SELECT * FROM messages ORDER BY chat_room, timestamp ASC");
    $stmt->execute();
    $result = $stmt->get_result();

    $currentChatRoom = "";
    while ($row = $result->fetch_assoc()) {
        if ($currentChatRoom !== $row['chat_room']) {
            if ($currentChatRoom !== "") {
                echo "</div>"; // close previous chat-box
            }
            $currentChatRoom = $row['chat_room'];
            echo "<div class='chat-box'>";
            echo "<div class='chat-header'>Chat Room: " . htmlspecialchars($currentChatRoom) . "</div>";
        }

        echo "<div class='message'><strong>" . htmlspecialchars($row['senderName']) . " (" . htmlspecialchars($row['senderRole']) . ")</strong>: " . htmlspecialchars($row['message']) . " <span class='timestamp'>[" . $row['timestamp'] . "]</span></div>";
    }
    if ($currentChatRoom !== "") {
        echo "</div>"; // close last chat-box
    }
    ?>
</div>
</body>
</html>