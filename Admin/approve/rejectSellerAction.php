<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['adminUsername'])) {
    die("Access denied");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seller_id'])) {
    //ensure the seller_id from user input is integer
    $seller_id = intval($_POST['seller_id']);
    $sqlGetUser = "SELECT user_id FROM seller WHERE seller_id = ?";
    $stmt = $conn->prepare($sqlGetUser);
    $stmt->bind_param("i", $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];

        $conn->begin_transaction();

        try {
            // Set seller status to rejected
            $sqlUpdateSeller = "UPDATE seller SET status 
            = 'rejected' WHERE seller_id = ?";
            $stmtSeller = $conn->prepare($sqlUpdateSeller);
            $stmtSeller->bind_param("i", $seller_id);
            $stmtSeller->execute();
            $conn->commit();
            header("Location: approveSeller.php?success=2");
            //use exit to avoid the unexpected behaviour and stop the script
            exit;
        } 

        //Cancels any database changes made in the current transaction
        catch (Exception $e) {
            $conn->rollback();
            echo "Error rejecting seller: " . $e->getMessage();
        }
    } else {
        echo "Seller not found.";
    }
} 
else {
    echo "Invalid request.";
}

$conn->close();
?>