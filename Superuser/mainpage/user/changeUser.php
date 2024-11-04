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
    $userId = $_POST['user_id'];
    echo($user_id);
    $action = $_POST['action'];
    

    if ($action === 'change') {
        // Prepare the SQL statement to update the user's role
        $stmt = $conn->prepare("UPDATE users SET role = 'Seller' WHERE user_id = ?");
        $stmt->bind_param("i", $userId); // 'i' indicates the parameter is an integer
        
        if ($stmt->execute()) {
            echo "User updated successfully.";
            // Redirect back to the user list page
            header("Location: viewC.php"); // Replace with your user list page
            exit(); // Terminate script after redirection
        } else {
            echo "Error updating user: " . $conn->error;
        }
        
        $stmt->close(); // Close the prepared statement
    }
     
    else if ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $userId); // 'i' indicates the parameter is an integer
    
        if ($stmt->execute()) {
            echo "User deleted successfully.";
            header("Location: viewC.php"); // Replace with your user list page
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