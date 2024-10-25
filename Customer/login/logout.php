<?php
session_start(); // Start the session if it hasn't been started already

// Clear all session variables
unset($_SESSION['order_id']);
unset($_SESSION['orders_id']);
unset($_SESSION['user_id']);
unset($_SESSION['username']);
unset($_SESSION['address']);
unset($_SESSION['email']);
unset($_SESSION['backupEmail']);

    
// Destroy the session
session_unset();
session_destroy();
if (empty($_SESSION)) {
    header("Location: ../userLogin/login.html");
 } else {
    echo "Session destruction failed.";
}

// Redirect to a different page or perform any other necessary actions
// For example, you can redirect to a login page after logging out
exit();
?>
