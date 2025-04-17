<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['passwords'];

    // Prepare SQL to fetch user data
    $sql = "SELECT passwords FROM superuser WHERE usernames = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $hashed_password = $row['passwords']; // Fetch hashed password
        
        if (password_verify($password, $hashed_password)) {
            // Correct password, set session and redirect
            $_SESSION['adminUsername'] = $username;
            header("Location: ../mainpage/adminMainpage.php");
            exit();
        } else {
            // Incorrect password
            header("Location: login.html?success=1");
            exit();
        }
    } else {
        // No user found
        header("Location: login.html?success=2");
        exit();
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>