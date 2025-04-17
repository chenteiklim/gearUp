console.log('hello world');
document.addEventListener("DOMContentLoaded", function () {
    const chatMessagesContainer = document.getElementById("chatMessages");
    const chatInput = document.getElementById("chatInput");
    const sendMessageButton = document.getElementById("sendMessage");

    // Previously customerName, now it's sellerName because this is the seller's page
    const senderName = localStorage.getItem("sellerName"); 
    console.log("Sender (Seller):", senderName);

    // Previously selectedSellerName, now it's customerName (receiver)
    const receiverName = localStorage.getItem("selectedCustomerName");
    console.log("Receiver (Customer):", receiverName);

    // Global function to load messages
    window.loadMessages = function () {
        console.log('Loading messages...');
        console.log("Sender (Seller):", senderName);
        console.log("Receiver (Customer):", receiverName);

        if (!senderName || !receiverName) return;

        const requestURL = `fetchMessage.php?senderName=${encodeURIComponent(senderName)}&receiverName=${encodeURIComponent(receiverName)}`;
        console.log("Request URL:", requestURL);

        fetch(requestURL)
            .then(response => response.json())
            .then(messages => {
                chatMessagesContainer.innerHTML = messages.length
                    ? messages.map(msg => `<div><strong>${msg.senderName}:</strong> ${msg.message}</div>`).join("")
                    : "<p>No messages found.</p>";
            })
            .catch(error => console.error("Error fetching messages:", error));
    };

    // Send message
    sendMessageButton.addEventListener("click", function () {
        const message = chatInput.value.trim();
        console.log("Message:", message);

        if (!message || !senderName || !receiverName) return;

        fetch("send_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `message=${encodeURIComponent(message)}&senderName=${encodeURIComponent(senderName)}&receiverName=${encodeURIComponent(receiverName)}`
        })
        .then(response => response.text())
        .then(result => {
            console.log("Message sent response:", result);
            chatInput.value = "";
            loadMessages();
        })
        .catch(error => console.error("Error sending message:", error));
    });

    // Load messages on page load
    loadMessages();
});