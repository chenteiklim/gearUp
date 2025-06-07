<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    die("Unauthorized access.");
}

$usernames = $_SESSION['username'];

// Retrieve form data
$order_item_id = $_POST['order_item_id']; // Note: This input is actually order_item_id
$productName = $_POST['productName'];
$reason = $_POST['reason'];

// Get user_id from username
$stmt = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$stmt->bind_param("s", $usernames);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['user_id'];
} else {
    die("No user found with that username.");
}
$stmt->close();

// File upload handling
$targetDir = $_SERVER['DOCUMENT_ROOT'] . "/inti/gearUp/assets/"; // Store in assets folder

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$fileName = basename($_FILES["proof"]["name"]);
$targetFile = $targetDir . $fileName;
$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

$allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi']; // Allow images & videos

if (!in_array($fileType, $allowedTypes)) {
    die("Only JPG, PNG, GIF images or MP4, MOV, AVI videos are allowed.");
}

if (move_uploaded_file($_FILES["proof"]["tmp_name"], $targetFile)) {
    $fileUrl = "/inti/gearUp/assets/" . $fileName; // Relative path for database

    // Insert refund request into the database
    $stmt = $conn->prepare("
        INSERT INTO refundRequest (order_item_id, user_id, product_name, reason, proof, status, date) 
        VALUES (?, ?, ?, ?, ?, 'pending', NOW())
    ");
    $stmt->bind_param("issss", $order_item_id, $user_id, $productName, $reason, $fileUrl);

    if ($stmt->execute()) {
        // Redirect after successful submission
        header("Location: tracking.php");
        exit();
    } else {
        echo "Database error: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Error uploading file.";
}

$conn->close();
?>