<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "natural";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if token is provided in the URL
if (isset($_GET['tokenEmail'])) {
    $tokenEmail = $_GET['tokenEmail'];

    // Search for the user with this token
    $sql = "SELECT * FROM users WHERE tokenEmail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tokenEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        // Token found, update the token to 1 (verified)
        $update_sql = "UPDATE users SET tokenEmail = 1 WHERE tokenEmail = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $tokenEmail);
        if ($update_stmt->execute()) {
            // Success, redirect to login page with a success message
            header("Location: verify.php?email=" . urlencode($email));
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        // Token not found or invalid
        echo "Invalid tokenEmail or account already verified.";
    }

    $stmt->close();
} 
if (isset($_GET['tokenBackupEmail'])) {
    $tokenBackupEmail = $_GET['tokenBackupEmail'];

    // Search for the user with this token
    $sql = "SELECT * FROM users WHERE tokenBackupEmail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tokenBackupEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $backupEmail = $row['backupEmail'];
        // Token found, update the token to 1 (verified)
        $update_sql = "UPDATE users SET tokenBackupEmail = 1 WHERE tokenBackupEmail = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $tokenBackupEmail);
        if ($update_stmt->execute()) {
            // Success, redirect to login page with a success message
            header("Location: verify.php?backupEmail=" . urlencode($backupEmail));
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        // Token not found or invalid
        echo "Invalid tokenBackupEmail or account already verified.";
    }

    $stmt->close();
} 





else {
    echo "No token provided.";
}

$conn->close();
?>