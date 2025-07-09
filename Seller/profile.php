<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['username'])) {
    exit('Access Denied');
}

$username = $_SESSION['username'];

// Check seller status
$stmt = $conn->prepare("SELECT s.status FROM users u JOIN seller s ON u.user_id = s.user_id WHERE u.usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
    die("Unauthorized access. Seller not found.");
}

$status = $result->fetch_assoc()['status'];

if ($status !== 'approved') {
    die("Unauthorized access. Seller approval required.");
}
// Get user_id from username
$stmt = $conn->prepare("SELECT user_id FROM users WHERE usernames = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) exit("User not found");
$row = $result->fetch_assoc();
$UserId = $row['user_id'];


// Handle form submission (update own profile info)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProfile'])) {
    $storeName = trim($_POST['storeName']);
    $description = trim($_POST['description']);

    $updateQuery = "UPDATE seller SET storeName=?, description=? WHERE user_id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssi", $storeName, $description, $UserId);

    if ($stmt->execute()) {
        $message = "Profile Updated Successfully";
        header("Location: profile.php?message=" . urlencode($message));
        exit;
    } else {
        $error = "Failed to update profile.";
    }
}

// Re-fetch updated seller info for the form
$stmt = $conn->prepare("SELECT storeName, description, contact FROM seller WHERE user_id = ?");
$stmt->bind_param("i", $UserId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) exit('Seller not found');
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>My Seller Profile</title>
    <style>
        h1{margin-left:658px;}
        #profileForm { margin-left:500px; background: white; padding: 20px; border-radius: 6px; max-width: 500px; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        #update { background: #007bff; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; }
        .message-container {
            margin:20px;
            color: green; /* or red for error */
            font-weight: bold;
            max-width: 400px;
            background-color: #f0f8ff;
            border: 1px solid #b0d4f1;
            padding: 10px 20px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Seller/sellerNavbar.php';?>

<h1>My Seller Profile</h1>

<form id='profileForm' method="POST" action="profile.php">
<div id="messageContainer"></div>

    <label for="storeName">Store Name</label>
    <input type="text" id="storeName" name="storeName" value="<?= htmlspecialchars($user['storeName']) ?>" required>

    <label for="description">Description</label>
    <input type="text" id="description" name="description" value="<?= htmlspecialchars($user['description']) ?>" required>

    <label for="contact">Contact</label>
    <input type="text" id="contact" value="<?= htmlspecialchars($user['contact']) ?>" disabled>

    <button id ='update' type="submit" name="updateProfile">Update Profile</button>
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
      }, 10000);
    }
}
</script>