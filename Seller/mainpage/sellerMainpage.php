<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo "<h1>You are not authorized to access this page</h1><h2>Please register or login.</h2>";
    exit();
}

$username = $_SESSION['username'];

$senderName = $_SESSION['username'] ?? '';
$receiverName = $_GET['customer'] ?? '';

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
    header("Location: ../Seller/login/login.php?message=" . urlencode($message));
    exit();
}

$sellerRow = $sellerResult->fetch_assoc();

$userStatus = $row['status']; // From `users` table
$sellerStatus = $sellerRow['status']; // From `seller` table

if ($userStatus === 'pending' && $sellerStatus === 'pending') {
    echo "<h1>Unauthorized Access</h1><p>Both user and seller applications are still pending.</p>";
    exit();
} elseif ($userStatus === 'registered' && $sellerStatus === 'pending') {
    // Send back to seller homepage if seller not approved yet
    header("Location: ../homepage/sellerHomepage.php");
    exit();
} elseif ($sellerStatus === 'rejected') {
    $message6 = "Login failed. Your seller application was rejected.";
    header("Location: ../../Seller/homepage/sellerHomepage.php?message6=" . urlencode($message6));
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Seller/sales.php'; ?>

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
            <button id="closeChat" onclick="document.getElementById('chatPopup').style.display='none'">&times;</button>
        </div>
        <div id="chatBody">
            <div id="chatMessages"></div>
        </div>
        <div id="chatFooter">
            <input type="text" id="chatInput" placeholder="Type a message..." />
            <button id="sendMessage">Send</button>
        </div>
    </div>

<script>
const senderName = <?php echo json_encode($senderName); ?>;
const receiverName = <?php echo json_encode($receiverName); ?>;
const chatIcon = document.getElementById("chatIcon"); // Missing!

document.getElementById("chatIcon").addEventListener("click", function () {
    document.getElementById("customerList").style.display = "block";
    document.getElementById("chatPopup").style.display = "none";
});

document.getElementById("closeCustomerList").addEventListener("click", function () {
    document.getElementById("customerList").style.display = "none";
});

document.addEventListener("DOMContentLoaded", function () {
    const customersContainer = document.getElementById("customersContainer");
    const chatPopup = document.getElementById("chatPopup");
    const chatCustomerName = document.getElementById("chatCustomerName");
    const chatMessagesContainer = document.getElementById("chatMessages");
    const chatInput = document.getElementById("chatInput");
    const sendMessageButton = document.getElementById("sendMessage");

    // Load customers
    fetch("getCustomer.php")
        .then(response => response.json())
        .then(customers => {
            customers.forEach(customer => {
                const customerDiv = document.createElement("div");
                customerDiv.classList.add("customer-item");
                customerDiv.innerText = customer.usernames;
                customerDiv.dataset.customerId = customer.customer_id;

                customerDiv.addEventListener("click", function () {
                    const selectedCustomerName = customer.usernames;
                    window.location.href = `sellerMainpage.php?customer=${encodeURIComponent(selectedCustomerName)}`;
                });

                customersContainer.appendChild(customerDiv);
            });
        })
        .catch(error => console.error("Error fetching customers:", error));

    // Load and display chat messages
    window.loadMessages = function () {
        if (!senderName || !receiverName) return;

        const requestURL = `fetchMessage.php?senderName=${encodeURIComponent(senderName)}&receiverName=${encodeURIComponent(receiverName)}`;
        fetch(requestURL)
            .then(response => response.json())
            .then(messages => {
               chatMessagesContainer.innerHTML = messages.length
                ? messages.map(msg => {
                    const isYou = msg.senderName === senderName;
                    const displayName = isYou ? "You" : msg.senderName;
                    const alignment = isYou ? "right" : "left"; // optional styling
                    return `<div class="chat-message ${alignment}"><strong>${displayName}:</strong> ${msg.message}</div>`;
                }).join("")
                : "<p>No messages found.</p>";
                chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
            })
            .catch(error => console.error("Error fetching messages:", error));
    };

    sendMessageButton.addEventListener("click", function () {
        const message = chatInput.value.trim();
        if (!message || !senderName || !receiverName) return;

        fetch("send_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `message=${encodeURIComponent(message)}&senderName=${encodeURIComponent(senderName)}&receiverName=${encodeURIComponent(receiverName)}`
        })
        .then(response => response.text())
        .then(result => {
            chatInput.value = "";
            loadMessages();
        })
        .catch(error => console.error("Error sending message:", error));
    });

    // Auto-load if customer is selected
    if (receiverName) {
        chatPopup.style.display = "block";
        chatCustomerName.innerText = receiverName;
        loadMessages();
        setInterval(loadMessages, 2000); // auto-refresh
    }
});
document.addEventListener("DOMContentLoaded", function () {
    const chatPopup = document.getElementById("chatPopup");
    const closeChat = document.getElementById("closeChat");
    
    closeChat.addEventListener("click", function () {
        chatPopup.style.display = "none"; // Hide chat window
    });
});


</script>

</body>
</html>