<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

session_start();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['passwords'];
    $sql = "SELECT * FROM users WHERE email = ? AND role IN ('admin', 'superuser')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $hashed_password = $row['passwords']; // Fetch hashed password
        $username=$row['usernames'];
        echo $username;
        if (password_verify($password, $hashed_password)) {
            // Correct password, set session and redirect
            $_SESSION['adminUsername'] = $username;
              header("Location: ../mainpage/adminMainpage.php");
             exit();
        } else {
            // Incorrect password
            header("Location: login.html?success=1"); // wrong password
            exit();
        }
    } else {
        // No user found or not admin/superuser
        header("Location: login.html?success=2"); // user not found or not allowed
        exit();
    }   

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>