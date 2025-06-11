<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['username'])) {
    exit('Access Denied');
}

$username=$_SESSION['username'];
$stmt = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $UserId = $row['user_id'];
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Customer/customerNavbar.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/encryption_helper.php';

function decrypt_address($encrypted_address) {
    global $encryption_key, $encryption_iv;
    return openssl_decrypt($encrypted_address, 'aes-256-cbc', $encryption_key, 0, $encryption_iv);
}

function encrypt_address($plain_address) {
    global $encryption_key, $encryption_iv;
    return openssl_encrypt($plain_address, 'aes-256-cbc', $encryption_key, 0, $encryption_iv);
}

// Fetch current user data
$stmt = $conn->prepare("SELECT usernames, email, address, role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $UserId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    exit('User not found');
}

$user = $result->fetch_assoc();
$decrypted_address = decrypt_address($user['address']);

// Handle form submission (update own profile info)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProfile'])) {
    $newUsername = trim($_POST['username']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    
        $encrypted_address = encrypt_address($address);

        $updateQuery = "UPDATE users SET usernames=?, email=?, address=? WHERE user_id=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssi", $newUsername, $email, $encrypted_address, $UserId);

        if ($stmt->execute()) {
           
            $message = "Profile Updated Successfully"; 
            $_SESSION['username']=$newUsername;
            
            header("Location: profile.php?message=" . urlencode($message));
            exit;

        } else {
            $error = "Failed to update profile.";
        }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Profile</title>
    <style>
        h1{margin-left:658px;}
        #profileForm { margin-left:500px; background: white; padding: 20px; border-radius: 6px; max-width: 500px; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #007bff; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; }
     
    </style>
</head>
<body>

<h1>My Profile</h1>


<form id='profileForm' method="POST" action="profile.php">
    <div id="messageContainer"></div>

    <label for="username">Username</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['usernames']) ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label for="address">Address</label>
    <textarea id="address" name="address" rows="3"><?= htmlspecialchars($decrypted_address) ?></textarea>

    <label for="role">Role</label>
    <input type="text" id="role" value="<?= htmlspecialchars($user['role']) ?>" disabled>

    <button type="submit" name="updateProfile">Update Profile</button>
</form>

</body>
</html>
<script>
    window.onload = function() {

    var urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
  
    if (message) {
      var messageContainer = document.getElementById("messageContainer");
      messageContainer.textContent = decodeURIComponent(message); // Decode the URL-encoded message
      messageContainer.style.display = "block";
      messageContainer.classList.add("message-container");
      
      setTimeout(function() {
        messageContainer.style.display = "none";
        messageContainer.classList.remove("message-container");
        
        // Clear the message from the URL
        const url = new URL(window.location);
        url.searchParams.delete('message');
        window.history.replaceState({}, document.title, url);
      }, 3000);
    }
}
</script>