<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

// Check if the delete request was sent
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $product_id = $_POST['product_id'];

    // Delete the product from the database
    $deleteQuery = "DELETE FROM products WHERE
     product_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // Redirect back with success message
        header("Location: viewProduct.php?success=Product Deleted");
        exit();
    } else {
        // Redirect back with error message
        header("Location: viewProduct.php?error=Failed to delete product");
        exit();
    }
}
else{
    echo ('hello world');
}
?>