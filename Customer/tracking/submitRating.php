<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['username'])) {
    die("User not logged in.");
}

$username = $_SESSION['username'];

// Get user_id based on username
$stmt = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found.");
}

$userData = $result->fetch_assoc();
$user_id = $userData['user_id'];

$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ensure the necessary fields are set and not empty
    if (isset($_POST['product_id']) && isset($_POST['rating']) && isset($_POST['review'])) {
        $product_id = $_POST['product_id']; // Get product_id from POST data
        $rating = $_POST['rating'];         // Get rating from POST data
        $review = $_POST['review'];         // Get review from POST data

        // Debugging: Check the values
        echo "Product ID: $product_id<br>";
        echo "Rating: $rating<br>";
        echo "Review: $review<br>";

        // Optional: prevent duplicate ratings for the same product by the same customer
        $check = $conn->prepare("SELECT * FROM ratings WHERE product_id = ? AND user_id = ?");
        $check->bind_param("ii", $product_id, $user_id);
        $check->execute();
        $existing = $check->get_result();

        if ($existing->num_rows > 0) {
            $message= "You already rated on this product.";
                header("Location: tracking.php?message=" . urlencode($message));
                $sellerCheck->close();
                exit;
        } 
        else {
            // Prevent seller from rating own product
            $sellerCheck = $conn->prepare("
                SELECT p.product_id
                FROM products p
                JOIN seller s ON p.seller_id = s.seller_id
                JOIN users u ON s.user_id = u.user_id
                WHERE p.product_id = ? AND u.user_id = ?
            ");
            $sellerCheck->bind_param("ii", $product_id, $user_id);
            $sellerCheck->execute();
            $sellerCheck->store_result();

            if ($sellerCheck->num_rows > 0) {
                $message= "You cannot rate your own product.";
                header("Location: tracking.php?message2=" . urlencode($message2));
                $sellerCheck->close();
                exit;
            }
            $sellerCheck->close();
                
            // Insert the new rating
            $insert = $conn->prepare("INSERT INTO ratings (product_id, user_id, rating, review) VALUES (?, ?, ?, ?)");
            $insert->bind_param("iids", $product_id, $user_id, $rating, $review);
            if ($insert->execute()) {
                $message2= "Thank you for your rating!";
                header("Location: tracking.php?message3=" . urlencode($message3));

                exit;
            } else {
                echo "Error saving rating: " . $conn->error;
            }
            $insert->close();
        }
        

        $check->close();
    } else {
        echo "Error: Missing required fields.";
    }
}
?>