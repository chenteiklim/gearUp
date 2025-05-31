console.log('hello world')
document.addEventListener("DOMContentLoaded", function () {
    const chatMessagesContainer = document.getElementById("chatMessages");
    const chatInput = document.getElementById("chatInput");
    const sendMessageButton = document.getElementById("sendMessage");
    const receiverName = localStorage.getItem("customerName");
      const senderName = localStorage.getItem("selectedSellerName");
   console.log (senderName);
        console.log(receiverName);
    // Global function to load messages
    window.loadMessages = function () {
        console.log('hello world')
      
             console.log(receiverName);

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
        const receiverName = localStorage.getItem("selectedSellerName");
        console.log(receiverName,'receiverName')
        const senderName = localStorage.getItem("customerName"); 

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
            loadMessages(receiverName);
        })
        .catch(error => console.error("Error sending message:", error));
    });
});