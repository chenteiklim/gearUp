<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gadgetShop";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the user ID from the form submission
    $user_id = $_POST['user_id'];
    $action = $_POST['action'];
    $userId = $_POST['user_id'];
    $action = $_POST['action']; // Can be 'approve' or 'reject'

    // First, check the current status in the sellerrequest table
    $stmt = $conn->prepare("SELECT status FROM sellerrequest WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $status = $row['status'];
        echo($status);
        echo($action);
        if ($status === 'rejected' && $action === 'approve' || $status === 'pending' && $action === 'approve' ) {
            $stmtUpdateUser = $conn->prepare("UPDATE users SET role = 'Seller' WHERE user_id = ?");
            $stmtUpdateUser->bind_param("i", $userId);
            // Status is already approved, reject it
            $stmtUpdateRequest = $conn->prepare("UPDATE sellerrequest SET role = 'Seller', status = 'approved' WHERE user_id = ?");
            $stmtUpdateRequest->bind_param("i", $userId);

            if ($stmtUpdateRequest->execute()) {
                echo "User request Approved successfully.";
                // Optionally redirect back to the user list
                header("Location: approve.php");
                exit();
            } else {
                echo "Error Approve user request: " . $conn->error;
            }
        } else if ($status === 'approved' && $action === 'reject') {
            
            // Status is already rejected, approve it
            $stmtUpdateUser = $conn->prepare("UPDATE users SET role = 'Customer' WHERE user_id = ?");
            $stmtUpdateUser->bind_param("i", $userId);
    
            $stmtUpdateRequest = $conn->prepare("UPDATE sellerrequest SET role = 'Customer', status = 'rejected' WHERE user_id = ?");
            $stmtUpdateRequest->bind_param("i", $userId);
    
            if ($stmtUpdateUser->execute() && $stmtUpdateRequest->execute()) {
                echo "User Rejected successfully.";
                // Optionally redirect back to the user list
                header("Location: approve.php");
                exit();
            } else {
                echo "Error rejecting user request: " . $conn->error;
            }
        } else {
            // If the action does not match the current status, handle it accordingly
            echo "No action taken. Please check the current status.";
        }
    }
     
    elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM sellerrequest WHERE user_id = ?");
        $stmt->bind_param("i", $user_id); // 'i' indicates the parameter is an integer
    
        if ($stmt->execute()) {
            echo "Request deleted successfully.";
            // Optionally redirect back to the user list
            header("Location: approve.php"); // Replace with your user list page
            exit(); // Terminate script after redirection
        } else {
            echo "Error deleting user: " . $conn->error;
        }
    
        $stmt->close(); // Close the prepared statement
        // Code to delete user
    }
  
}

$conn->close(); // Close the database connection
?>