<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['username'])) {
?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Access Denied</title>
    </head>
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

// Fetch user ID
$selectNameQuery = "SELECT user_id FROM users WHERE usernames = ?";
$stmt = $conn->prepare($selectNameQuery);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();
$selectRowsQuery = "
    SELECT p.*, s.sellerName AS sellerName
    FROM products p
    JOIN seller s ON p.seller_id = s.seller_id
    ORDER BY p.product_id ASC
";
$selectRowsResult = $conn->query($selectRowsQuery);

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

            <!-- Chat button with seller username in data attribute -->
            <button class="chatBtn" data-seller="<?= htmlspecialchars($row['sellerName']) ?>">
                💬 Chat with <?= htmlspecialchars($row['sellerName']) ?>
            </button>               
             
            <form action="" method="post">
                <button id="view" class="button" type="submit" name="view" value="<?= $row['product_id'] ?>">View</button>
            </form>
</div>
    </div>
<?php endwhile; ?>
</div>



<!-- Chat Popup Window -->
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
document.addEventListener("DOMContentLoaded", function () {
    // Set customer username in localStorage
    localStorage.setItem("customerName", "<?= $username ?>");

    // References
    const chatButtons = document.querySelectorAll(".chatBtn");
    const chatPopup = document.getElementById("chatPopup");
    const chatSellerName = document.getElementById("chatSellerName");
    const closeChatBtn = document.getElementById("closeChat");
    const chatMessagesContainer = document.getElementById("chatMessages");
    const chatInput = document.getElementById("chatInput");
    const sendMessageButton = document.getElementById("sendMessage");

    // Define message loader
    window.loadMessages = function () {
        const senderName = localStorage.getItem("selectedSellerName");   // seller
        const receiverName = localStorage.getItem("customerName");       // customer

        console.log('Loading messages...');
        console.log('Sender (seller):', senderName);
        console.log('Receiver (customer):', receiverName);

        if (!senderName || !receiverName) return;

        const requestURL = `fetchMessage.php?senderName=${encodeURIComponent(senderName)}&receiverName=${encodeURIComponent(receiverName)}`;

        fetch(requestURL)
            .then(response => response.json())
            .then(messages => {
                chatMessagesContainer.innerHTML = messages.length
                    ? messages.map(msg => `<div><strong>${msg.senderName}:</strong> ${msg.message}</div>`).join("")
                    : "<p>No messages found.</p>";
            })
            .catch(error => console.error("Error fetching messages:", error));
    };

    // Chat button click setup
    chatButtons.forEach(button => {
        button.addEventListener("click", function () {
            const sellerName = this.getAttribute("data-seller");

            localStorage.setItem("selectedSellerName", sellerName);
            chatSellerName.innerText = sellerName;
            chatPopup.style.display = "block";

            loadMessages(); // No argument needed now
        });
    });

    // Send message
    sendMessageButton.addEventListener("click", function () {
        const message = chatInput.value.trim();
        const senderName = localStorage.getItem("customerName");         // customer
        const receiverName = localStorage.getItem("selectedSellerName"); // seller

        console.log("Sending message to", receiverName);

        if (!message || !senderName || !receiverName) return;

        fetch("send_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `message=${encodeURIComponent(message)}&senderName=${encodeURIComponent(senderName)}&receiverName=${encodeURIComponent(receiverName)}`
        })
        .then(response => response.text())
        .then(result => {
            console.log(result);
            chatInput.value = "";
            loadMessages();
        })
        .catch(error => console.error("Error sending message:", error));
    });

    // Close chat popup
    closeChatBtn.addEventListener("click", function () {
        chatPopup.style.display = "none";
    });
});
</script>
<script src="customerMainpage.js"></script>

</body>
</html>

<?php
// Handle product view button
if (isset($_POST['view'])) {
    $_SESSION['product_id'] = $_POST['view'];
    echo '<script>window.location.href = "../product/product.php";</script>'; 
    exit;
}
?>