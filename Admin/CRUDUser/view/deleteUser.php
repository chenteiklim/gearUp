<?php
// Include DB connection
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['adminUsername'])) {
    echo "Access Denied. Only admins can perform this action.";
    exit;
}

// Validate user ID from POST
if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
    $userIdToSoftDelete = $_POST['user_id'];

    // Check if the user exists and has requested deletion
    $stmtCheck = $conn->prepare("SELECT delete_request FROM users WHERE user_id = ?");
    $stmtCheck->bind_param("i", $userIdToSoftDelete);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "User not found.";
        exit;
    }

    if ($user['delete_request'] != 1) {
        echo "User did not request account deletion.";
        exit;
    }

    // Perform soft delete by setting status to 'inactive'
    $stmtSoftDelete = $conn->prepare("UPDATE users SET status = 'inactive', delete_request = 0 WHERE user_id = ?");
    $stmtSoftDelete->bind_param("i", $userIdToSoftDelete);

    if ($stmtSoftDelete->execute()) {
        // Optionally also set seller status to 'inactive' if the user is a seller
        $stmtUpdateSeller = $conn->prepare("UPDATE seller SET status = 'inactive' WHERE user_id = ?");
        $stmtUpdateSeller->bind_param("i", $userIdToSoftDelete);
        $stmtUpdateSeller->execute();

        header("Location: viewUser.php?message=" . urlencode("User marked as inactive."));
        exit;
    } else {
        echo "Failed to mark user as inactive.";
    }

} else {
    echo "Invalid request.";
}
?>