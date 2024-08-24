<?php

$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password, $dbname);

 // Check the connection
 if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the order_id is set and not empty
    if (isset($_POST['new_status']) && !empty($_POST['new_status'])) {
        $new_status = $_POST['new_status'];
        $order_id = $_POST['order_id'];
        $user_id=$_POST['user_id'];
        
        // Perform the necessary actions to edit the order with the given order_id
        // Add your logic here
        // Example: Update the order status to "Edited"
        $updateQuery = "UPDATE orders SET order_status = '$new_status' WHERE order_id = $order_id AND user_id= $user_id";
    
        // Execute the update query
        if ($conn->query($updateQuery) === TRUE) {
            echo "Order updated successfully.";
        } else {
            echo "Error updating order: " . $conn->error;
        }
        // Redirect the user back to the original page or any other appropriate page
         header('Location: sales.php');
         exit();
    }
}

// If the order_id is not set or empty, or if the request method is not POST
// Redirect the user to an error page or display an error message
header('Location: error_page.php');
exit();
?>






