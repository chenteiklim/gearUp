<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

if (!isset($_POST['orders_id']) || !isset($_FILES['proof'])) {
    die("Invalid request.");
}

$orders_id = $_POST['orders_id'];
$productName = $_POST['productName'];
$username = $_SESSION['username'];
$reason = $_POST['reason'] ?? ''; // Get reason if available

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
    $stmt = $conn->prepare("INSERT INTO refundRequest (orders_id, usernames, productName, reason, proof, status, date) 
                            VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
    $stmt->bind_param("issss", $orders_id, $username, $productName, $reason, $fileUrl);

    if ($stmt->execute()) {
        echo "Refund request submitted successfully!";
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