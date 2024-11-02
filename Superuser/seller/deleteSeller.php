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
    $seller_id = intval($_POST['seller_id']); // Ensure to validate and sanitize

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("DELETE FROM seller WHERE seller_id = ?");
    $stmt->bind_param("i", $seller_id); // 'i' indicates the parameter is an integer

    // Execute the deletion
    if ($stmt->execute()) {
        echo "User deleted successfully.";
        // Optionally redirect back to the user list
        header("Location: viewSeller.php"); // Replace with your user list page
        exit(); // Terminate script after redirection
    } else {
        echo "Error deleting seller: " . $conn->error;
    }

    $stmt->close(); // Close the prepared statement
}

$conn->close(); // Close the database connection
?>