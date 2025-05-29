<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';
session_start();

// Check admin logged in (optional but recommended)
if (!isset($_SESSION['adminUsername'])) {
    die("Access denied");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seller_id'])) {
    $seller_id = intval($_POST['seller_id']);

    // Get user_id for the seller
    $sqlGetUser = "SELECT user_id FROM seller WHERE seller_id = ?";
    $stmt = $conn->prepare($sqlGetUser);
    $stmt->bind_param("i", $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];

        // Start transaction (optional but good)
        $conn->begin_transaction();

        try {
            // Update user role
            $sqlUpdateUser = "UPDATE users SET role = 'seller' WHERE user_id = ?";
            $stmtUser = $conn->prepare($sqlUpdateUser);
            $stmtUser->bind_param("i", $user_id);
            $stmtUser->execute();

            // Update seller status
            $sqlUpdateSeller = "UPDATE seller SET status = 'approved' WHERE seller_id = ?";
            $stmtSeller = $conn->prepare($sqlUpdateSeller);
            $stmtSeller->bind_param("i", $seller_id);
            $stmtSeller->execute();

            $conn->commit();

            // Redirect or show success message
            header("Location: approveSeller.php?success=1");
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error approving seller: " . $e->getMessage();
        }
    } else {
        echo "Seller not found.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>