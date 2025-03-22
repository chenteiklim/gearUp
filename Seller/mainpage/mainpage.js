document.addEventListener("DOMContentLoaded", function () {
    console.log(seller_id);
    console.log(seller_name);
    const chatIcon = document.getElementById("chatIcon");
    const chatPopup = document.getElementById("chatPopup");
    const closeChat = document.getElementById("closeChat");
    const customerListContainer = document.getElementById('customerList');
    const chatMessagesContainer = document.getElementById('chatMessages');
    const chatFooter = document.getElementById('chatFooter');
    const chatInput = document.getElementById('chatInput');
    const sendMessageButton = document.getElementById('sendMessage');
    chatFooter.style.display = "none"; // Hide footer initially

    // Open chat when clicking the chat icon
    chatIcon.addEventListener("click", function () {
        chatPopup.style.display = "block";
    });

    // Close chat when clicking the close button
    closeChat.addEventListener("click", function () {
        chatPopup.style.display = "none";
    });

    // Render customer list
    if (typeof customerList !== 'undefined') {
        customerList.forEach(function(customer) {
            const customerDiv = document.createElement('div');
            customerDiv.classList.add('customer-item');
            customerDiv.innerHTML = customer.customer_name;

            customerDiv.addEventListener('click', function() {
                openChatWithCustomer(customer.customer_id, customer.customer_name);
            });

            customerListContainer.appendChild(customerDiv);
        });
    } else {
        console.log('Customer list is undefined');
    }

    function openChatWithCustomer(customer_id, customer_name) {
        customerListContainer.style.display = "none";
        chatFooter.style.display = "block"; // Show chat input
        document.getElementById("chatHeader").innerHTML = "Chat with " + customer_name;

        // Store customer_id globally
        window.currentCustomerId = customer_id;
        
        loadMessages(customer_id, customer_name);
    }

    function loadMessages(customer_id, customer_name, seller_name) {
        fetch(`fetch_messages.php?customer_id=${customer_id}`)
            .then(response => response.json())
            .then(messages => {
                chatMessagesContainer.innerHTML = messages.length 
                    ? messages.map(msg => {
                        // Determine sender name dynamically
                        const sender = msg.sender_id === seller_id ? seller_name : customer_name;
                        return `<div class='chat-message'><strong>${sender}:</strong> ${msg.message}</div>`;
                    }).join("")
                    : "<p>No messages found.</p>";
            })
            .catch(error => console.error("Error fetching messages:", error));
    }

    sendMessageButton.addEventListener("click", function () {
        const message = chatInput.value.trim();
        const customer_id = window.currentCustomerId; // Use globally stored ID

        if (message === "" || !customer_id) return;

        fetch("sendMessage.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `message=${encodeURIComponent(message)}&customer_id=${customer_id}&seller_id=${seller_id}`
        })
            .then(response => response.text())
            .then(result => {
                console.log(result);
                chatInput.value = "";
                loadMessages(customer_id, customer_name, seller_name);
            })
            .catch(error => console.error("Error sending message:", error));
    });
});