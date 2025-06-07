<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo "<h1>You are not authorized to access this page</h1><h2>Please register or login.</h2>";
    exit();
}

$username = $_SESSION['username'];

// Fetch user details
$checkLogin = $conn->prepare("SELECT * FROM users WHERE usernames = ?");
$checkLogin->bind_param("s", $username);
$checkLogin->execute();
$result = $checkLogin->get_result();

if (!$result || $result->num_rows === 0) {
    echo "<h1>User not found</h1>";
    exit();
}

$row = $result->fetch_assoc();
$role = $row['role'];
$user_id = $row['user_id'];

if ($role !== 'seller') {
    $message4 = "Login failed, please submit seller application form";
    header("Location: ../../Customer/mainpage/customerMainpage.php?message4=" . urlencode($message4));
    exit();
}

// Check seller approval status
$sellerQuery = "SELECT status FROM seller WHERE user_id = ?";
$stmt2 = $conn->prepare($sellerQuery);
$stmt2->bind_param("s", $user_id);
$stmt2->execute();
$sellerResult = $stmt2->get_result();

$sellerQuery = "SELECT status FROM seller WHERE user_id = ?";
$stmt2 = $conn->prepare($sellerQuery);
$stmt2->bind_param("s", $user_id);
$stmt2->execute();
$sellerResult = $stmt2->get_result();

if (!$sellerResult || $sellerResult->num_rows === 0) {
    // No seller record found
    $message = "Login failed. Seller account not found.";
    header("Location: ../../Customer/mainpage/customerMainpage.php?message=" . urlencode($message));
    exit();
}

$sellerRow = $sellerResult->fetch_assoc();
$status = $sellerRow['status'];

if ($status === 'pending') {
    $message5 = "Login failed. Your seller application is still pending.";
    header("Location: ../../Customer/mainpage/customerMainpage.php?message5=" . urlencode($message5));
    exit();
} elseif ($status === 'rejected') {
    $message6 = "Login failed. Your seller application was rejected.";
    header("Location: ../../Customer/mainpage/customerMainpage.php?message6=" . urlencode($message6));
    exit();
} 

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Seller/sellerNavbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Mainpage</title>
    <link rel="stylesheet" href="sellerMainpage.css">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            localStorage.setItem("sellerName", "<?php echo $_SESSION['username']; ?>");
        });
    </script>
</head>
<body>
    <div>
        <p id='title'>Seller Mainpage</p>
    </div>
    <div id="container">
        <div id="messageContainer"></div>
    </div>

    <div id="chatIcon">
        <i class="fa fa-comment"></i>
    </div>

    <div id="customerList">
        <div id="customerNavbar">
            <h3 id="customerHeader">Chat with Customer</h3>
            <button id="closeCustomerList" class="close-btn">&times;</button>
        </div>
        <div id="customersContainer"></div>
    </div>

    <div id="chatPopup">
        <div id="chatHeader">
            <span>Chat with <span id="chatCustomerName"></span></span>
            <button id="closeChat">&times;</button>
        </div>
        <div id="chatBody">
            <div id="chatMessages"></div>
        </div>
        <div id="chatFooter">
            <input type="text" id="chatInput" placeholder="Type a message..." />
            <button id="sendMessage">Send</button>
        </div>
    </div>

    <script src="sellerMainpage.js"></script>
    <script src="getCustomer.js"></script>
    <script src="chat.js"></script>
</body>
</html>