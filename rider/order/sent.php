<?php
    session_start(); // Start session to access $_SESSION

    if (!isset($_SESSION['riderUsername'])) {
        die("User not logged in.");
    }
    $username = $_SESSION['riderUsername'];

    include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

    echo "Username: $username <br>";

    // Fetch rider ID based on username
    $selectQuery = $conn->prepare("SELECT rider_id FROM rider WHERE username = ?");
    $selectQuery->bind_param("s", $username);
    $selectQuery->execute();
    $result = $selectQuery->get_result();

    if ($result->num_rows === 0) {
        die("Rider not found.");
    }
    $row = $result->fetch_assoc();
    $rider_id = $row['rider_id'];
    echo "Rider ID: $rider_id <br>";
    
    // Update the order status for the rider
    $stmt = $conn->prepare("UPDATE orders SET order_status = 'sent' WHERE assigned_rider = ?");
    $stmt->bind_param("i", $rider_id);
    
    if ($stmt->execute()) {
        // Now update the rider's availability
        $stmt = $conn->prepare("UPDATE rider SET currentOrder = NULL, available = 1 WHERE rider_id = ?");
        $stmt->bind_param("i", $rider_id);
    
        if ($stmt->execute()) {
            $message = "Order status updated, and rider is now available.";
            header("Location: order.php?message2=" . urlencode($message));
            exit(); // Ensure no further code executes after redirection
        } else {
            echo "Error updating rider status: " . $conn->error;
        }
    } else {
        echo "Error updating order: " . $conn->error;
    }

    // Close statements and connection
    $stmt->close();
    $selectQuery->close();
    $conn->close();
?>