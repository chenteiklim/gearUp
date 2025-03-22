<?php
session_start(); // Start the session if it hasn't been started already

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Clear the session cookie if it exists
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');  // Expire the session cookie (set an expiration date in the past)
}

// Check if the session is empty and redirect to login page
if (empty($_SESSION)) {
    header("Location: ../../Customer/login/login.html");  // Redirect to login page
    exit();  // Stop further script execution
} else {
    echo "Session destruction failed.";  // If session still exists, show this message
}
// Redirect to a different page or perform any other necessary actions
// For example, you can redirect to a login page after logging out
exit();
?>
