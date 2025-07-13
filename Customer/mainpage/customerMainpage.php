<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['username'])) {
?>
    <!DOCTYPE html>
    <html>
    <head><title>Access Denied</title></head>
    <body>
        <h1>This Website is Not Accessible</h1>
        <p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>
    </body>
    </html>
<?php
    exit;
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Customer/customerNavbar.php';

$username = $_SESSION['username'];
$selectedSeller = $_GET['seller'] ?? '';

$selectNameQuery = "SELECT user_id FROM users WHERE usernames = ?";
$stmt = $conn->prepare($selectNameQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

$searchTerm = $_GET['search'] ?? '';

if (!empty($searchTerm)) {
    $selectRowsQuery = "
        SELECT p.*, s.sellerName AS sellerName, s.storeName AS storeName
        FROM products p
        JOIN seller s ON p.seller_id = s.seller_id
        WHERE p.product_name LIKE ?
        ORDER BY p.product_id ASC
    ";
    $stmt = $conn->prepare($selectRowsQuery);
    $likeSearch = "%" . $searchTerm . "%";
    $stmt->bind_param("s", $likeSearch);
    $stmt->execute();
    $selectRowsResult = $stmt->get_result();
} else {
    $selectRowsQuery = "
        SELECT p.*, s.sellerName AS sellerName, s.storeName AS storeName
        FROM products p
        JOIN seller s ON p.seller_id = s.seller_id
        ORDER BY p.product_id ASC
    ";
    $selectRowsResult = $conn->query($selectRowsQuery);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="customerMainpage.css">
</head>
<body>
<div id="messageContainer"></div>
<form class="search-bar" method="GET" action="">
    <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" />
    <button type="submit"><i class="fa fa-search"></i></button>
</form>

<?php if (!empty($searchTerm)): ?>
    <div style="text-align: right; margin-right: 200px; margin-bottom: 10px;">
        <a href="customerMainpage.php" style="color: #3498db; text-decoration: none; font-weight: bold;">
            🔄 Show All Products
        </a>
    </div>
<?php endif; ?>

<?php if ($selectRowsResult->num_rows === 0): ?>
    <p style="margin-left: 300px;">No products found for "<?= htmlspecialchars($searchTerm) ?>".</p>
<?php endif; ?>

<div id="container">
<?php while ($row = $selectRowsResult->fetch_assoc()): ?>
    <div class="product">
        <div class="imageContainer">
            <img class="item" src="/inti/gearUp/assets/<?= $row['image'] ?>" alt="">
        </div>
        <div class="productDetails">
            <div class="product_name"><?= htmlspecialchars($row['product_name']) ?></div>
            <div class="price">
                <div class="unit">RM</div>
                <div><?= number_format($row['price'], 2) ?></div>
            </div>
            <button class="chatBtn" 
                data-seller="<?= htmlspecialchars($row['sellerName']) ?>" 
                data-store="<?= htmlspecialchars($row['storeName']) ?>">
                💬 Chat with <?= htmlspecialchars($row['storeName']) ?>
            </button>                
               
            <form action="" method="post">
                <button id="view" class="button" type="submit" name="view" value="<?= $row['product_id'] ?>">View</button>
            </form>
        </div>
    </div>
<?php endwhile; ?>
</div>

<div id="chatPopup">
    <div id="chatHeader">
        <span>Chat with <span id="chatSellerName"></span></span>
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
<script>
    const senderName = <?= json_encode($username) ?>;
    let receiverName = null;

    document.addEventListener("DOMContentLoaded", function () {
        const chatButtons = document.querySelectorAll(".chatBtn");
        const chatPopup = document.getElementById("chatPopup");
        const chatSellerName = document.getElementById("chatSellerName");
        const closeChatBtn = document.getElementById("closeChat");
        const chatMessagesContainer = document.getElementById("chatMessages");
        const chatInput = document.getElementById("chatInput");
        const sendMessageButton = document.getElementById("sendMessage");

        // Load messages
        function loadMessages() {
            if (!senderName || !receiverName) return;
            const requestURL = `fetchMessage.php?senderName=${encodeURIComponent(senderName)}&receiverName=${encodeURIComponent(receiverName)}`;
            fetch(requestURL)
                .then(response => response.json())
                .then(messages => {
                  chatMessagesContainer.innerHTML = messages.length
                    ? messages.map(msg => {
                        const isYou = msg.senderName === senderName;
                        const displayName = isYou ? "You" : msg.senderName;
                        const alignment = isYou ? "right" : "left"; // optional for styling
                        return `<div class="chat-message ${alignment}"><strong>${displayName}:</strong> ${msg.message}</div>`;
                    }).join("")
                    : "<p>No messages found.</p>";
                    chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
                })
                .catch(error => console.error("Error fetching messages:", error));
        }

       // Inside button click that opens chat
chatButtons.forEach(button => {
    button.addEventListener("click", function () {
        receiverName = this.getAttribute("data-seller");
        chatSellerName.innerText = this.getAttribute("data-store");
        chatPopup.style.display = "block";
        loadMessages();

        // Add this to auto-refresh like seller side
        setInterval(loadMessages, 2000);
    });
});

        // Send message
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

        // Close popup
        closeChatBtn.addEventListener("click", function () {
            chatPopup.style.display = "none";
        });
    });
</script>
<script src="customerMainpage.js"></script>
</body>
</html>

<?php
if (isset($_POST['view'])) {
    $_SESSION['product_id'] = $_POST['view'];
    echo '<script>window.location.href = "../product/product.php";</script>'; 
    exit;
}
?>
