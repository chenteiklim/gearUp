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
    echo($user_id);
    $action = $_POST['action'];

    // First, check the current status in the sellerrequest table
    $stmt = $conn->prepare("SELECT status FROM sellerrequest WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $status = $row['status'];
        echo($status);
        echo($action);
        if ($status === 'rejected' && $action === 'approve' || $status === 'pending' && $action === 'approve' ) {
            $stmtUpdateUser = $conn->prepare("UPDATE users SET role = 'Seller' WHERE user_id = ?");
            $stmtUpdateUser->bind_param("i", $user_id);
            // Status is already approved, reject it
            $stmtUpdateRequest = $conn->prepare("UPDATE sellerrequest SET role = 'Seller', status = 'approved' WHERE user_id = ?");
            $stmtUpdateRequest->bind_param("i", $user_id);

            if ($stmtUpdateUser->execute() && $stmtUpdateRequest->execute()) {
                echo "User request Approved successfully.";
                // Optionally redirect back to the user list
                   header("Location: approve.php");
                   exit();
            } else {
                echo "Error Approve user request: " . $conn->error;
            }
        } 
        
        else if ($status === 'approved' && $action === 'reject') {
            
            // Status is already rejected, approve it
            $stmtUpdateUser = $conn->prepare("UPDATE users SET role = 'Customer' WHERE user_id = ?");
            $stmtUpdateUser->bind_param("i", $user_id);
    
            $stmtUpdateRequest = $conn->prepare("UPDATE sellerrequest SET role = 'Customer', status = 'rejected' WHERE user_id = ?");
            $stmtUpdateRequest->bind_param("i", $user_id);
    
            if ($stmtUpdateUser->execute() && $stmtUpdateRequest->execute()) {
                echo "User Rejected successfully.";
                // Optionally redirect back to the user list
                 header("Location: approve.php");
                 exit();
            } else {
                echo "Error rejecting user request: " . $conn->error;
            }
        } 

        else if($action === 'pending'){
            $stmtUpdateUser = $conn->prepare("UPDATE users SET role = 'Customer' WHERE user_id = ?");
            $stmtUpdateUser->bind_param("i", $user_id);
    
            $stmtUpdateRequest = $conn->prepare("UPDATE sellerrequest SET role = 'Customer', status = 'pending' WHERE user_id = ?");
            $stmtUpdateRequest->bind_param("i", $user_id);
    
            if ($stmtUpdateUser->execute() && $stmtUpdateRequest->execute()) {
                echo "User pending successfully.";
                // Optionally redirect back to the user list
                 header("Location: approve.php");
                 exit();
            } else {
                echo "Error rejecting user request: " . $conn->error;
            }

        }
     
        else if ($action === 'delete') {
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
        else{
            echo('No action taken');
        }  
    }
    else{
        echo('no user_id found');
    }
}
else{
    echo('No form is submitted');
}


$conn->close(); // Close the database connection
?>