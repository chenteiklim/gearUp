<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';
session_start();
$username=$_SESSION['username'];

$selectNameQuery = "SELECT * FROM users WHERE usernames = '$username'";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
    $seller_id = $row['seller_id'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['message']) && isset($_POST['customer_id'])) { 
    session_start(); // Ensure session is started to get user_id
    $customer_id = $_POST['customer_id'];// Sender (seller) 
    $message = trim($_POST['message']); // Sanitize message input
    $param1=0;
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, customer_id, seller_id, message, is_read, timestamp) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiiisi", $seller_id, $customer_id, $customer_id, $seller_id, $message, $param1);
        
        if ($stmt->execute()) {
            echo "Message sent successfully";
        } else {
            echo "Error sending message";
        }
  
        $stmt->close();
    } else {
        echo "Message cannot be empty";
    }
  
    exit(); // Stop further execution (important for AJAX)
  }
  
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['customer_id'])) { 
        $seller_id = $_POST['seller_id'];
  
        // Fetch chat messages
        $stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp ASC");
        $stmt->bind_param("iiii", $customer_id, $seller_id, $seller_id, $customer_id);
        $stmt->execute();
        $messages = $stmt->get_result();
  
        while ($msg = $messages->fetch_assoc()) {
            echo "<p><b>" . ($msg['sender_id'] == $seller_id ? "You" : "Customer") . ":</b> " . htmlspecialchars($msg['message']) . "</p>"; 
        }
  
        $stmt->close();
    }
  }
?>
