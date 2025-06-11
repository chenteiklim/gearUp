<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['adminUsername'])) {
    exit('Access Denied');
}
$username=$_SESSION['adminUsername'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php';

$stmt = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $adminUserId = $row['user_id'];
} else {
    exit('Admin user not found');
}


include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/encryption_helper.php';


// Fetch current user data
$stmt = $conn->prepare("SELECT usernames, email, role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $adminUserId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    exit('User not found');
}

$user = $result->fetch_assoc();

// Handle form submission (update own profile info)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProfile'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Validation
    if (empty($username) || empty($email)) {
        $error = 'Username and email cannot be empty.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {

        $updateQuery = "UPDATE users SET usernames=?, email=?, WHERE user_id=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssi", $username, $email, $currentUserId);

        if ($stmt->execute()) {
            $success = "Profile updated successfully.";
            // Refresh user info after update
            $user['usernames'] = $username;
            $user['email'] = $email;
        } else {
            $error = "Failed to update profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Profile</title>
    <style>
        body { margin-left: 300px; padding: 20px; background: #f8f9fa; font-family: Arial, sans-serif; }
        form { background: white; padding: 20px; border-radius: 6px; max-width: 500px; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #007bff; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; }
        button:hover { background: #0056b3; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>

<h1>My Profile</h1>


<form method="POST" action="profile.php">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['usernames']) ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>


    <label for="role">Role</label>
    <input type="text" id="role" value="<?= htmlspecialchars($user['role']) ?>" disabled>

    <button type="submit" name="updateProfile">Update Profile</button>
</form>

</body>
</html>