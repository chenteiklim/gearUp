<?php
session_start(); // Start the session if it hasn't been started already

// Destroy the session
session_unset();
session_destroy();
if (empty($_SESSION)) {
    header("Location: ../login/login.html");
 } else {
    echo "Session destruction failed.";
}

// Redirect to a different page or perform any other necessary actions
// For example, you can redirect to a login page after logging out
exit();
?>
