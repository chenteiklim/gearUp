<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['adminUsername'])) {
    echo "Access Denied";
    exit;
}

if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    // Reactivate user
    $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE user_id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Also reactivate seller if exists
        $stmtSeller = $conn->prepare("UPDATE seller SET status = 'active' WHERE user_id = ?");
        $stmtSeller->bind_param("i", $userId);
        $stmtSeller->execute();

        header("Location: viewUser.php?message=" . urlencode("User reactivated successfully."));
        exit;
    } else {
        echo "Failed to reactivate user.";
    }
} else {
    echo "Invalid request.";
}
?>