<?php
session_start();
if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>Access Denied</h1>";
    echo "<p>Please login to access this page.</p>";
    exit;
}
$username=$_SESSION['adminUsername'];
$senderName = $_SESSION['adminUsername'] ?? '';
$receiverName = $_GET['customer'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script>
        document.addEventListener("DOMContentLoaded", function () {
            localStorage.setItem("sellerName", "<?php echo $_SESSION['adminUsername']; ?>");
        });
    </script>
<title>Admin Dashboard</title>

<style>
  /* Reset & base */
  * {
    box-sizing: border-box;
  }
  body {
    margin: 0;
    font-family: Arial, sans-serif;
  }

  /* Main content */
  .main {
    margin-left: 250px; /* beside sidebar */
    padding: 20px;
  }
  
    
      
#customerList {
  display: none; /* add this line */
  position: fixed;
  bottom: 100px;
  right: 20px;
  width: 250px;
  height: 300px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  padding: 10px;
  overflow: hidden;
  transition: all 0.3s ease-in-out;
}

#customerList h3 {
  width: 180px;
  font-size: 16px;
  margin: 0;
  padding: 8px;
  text-align: center;
  background: #007bff;
  color: white;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}
#customerNavbar{
  display: flex;
}
.close-btn {
  margin-left: 20px;
  background: none;
  border: none;
  font-size: 28px;
  cursor: pointer;
  color: #555;
}

.close-btn:hover {
  color: red;
}
#customersContainer {
  max-height: 200px;
  overflow-y: auto;
  padding: 8px;
}

.customer-item {
  display: flex;
  align-items: center;
  padding: 8px;
  border-bottom: 1px solid #ddd;
  transition: background 0.2s;
  cursor: pointer;
}

.customer-item:hover {
  background: #f0f0f0;
}

.customerAvatar {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  margin-right: 10px;
  object-fit: cover;
  border: 2px solid #475e4d;
}

     #chat{
      background-color: #007bff;
      color: white;
      border-radius: 10px;
      width: 160px;
    }

     #chatIcon {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #007bff;
      color: white;
      padding: 15px;
      border-radius: 50%;
      cursor: pointer;
      font-size: 20px;
      box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
      transition: background 0.3s;
    }
    
    #chatIcon:hover {
      background-color: #0056b3;
    }
    
    /* Chat Popup */
    #chatPopup {
      position: fixed;
      bottom: 80px;
      right: 20px;
      width: 250px;
      background: white;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
      display: none;
      flex-direction: column;
      overflow: hidden;
      padding: 8px;

    }
    
    /* Chat Header */
    #chatHeader {
      background: #007bff;
      color: white;
      padding: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-weight: bold;
    }
    
    /* Chat Close Button */
    #closeChat {
      background: transparent;
      border: none;
      color: white;
      font-size: 18px;
      cursor: pointer;
    }
    
    /* Chat Body */
    #chatBody {
      padding: 10px;
      height: 200px;
      overflow-y: auto;
      font-size: 14px;
    }
    
    /* Chat Input */
    #chatFooter {
      display: flex;
      border-top: 1px solid #ddd;
    }
    
    #chatInput {
      flex: 1;
      padding: 10px;
      border: none;
      outline: none;
    }
    
    #sendMessage {
      background: #007bff;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
    }
    
    #sendMessage:hover {
      background: #0056b3;
    }
    .chat-message.left {
    text-align: left;
    background-color: #f1f1f1;
    padding: 6px 10px;
    border-radius: 8px;
    margin: 5px;
    max-width: 70%;
}

.chat-message.right {
    text-align: right;
    background-color: #d1e7dd;
    padding: 6px 10px;
    border-radius: 8px;
    margin: 5px auto 5px 30%;
    max-width: 70%;
}
    
</style>

</head>
<body>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php';

?>

<div class="main">
  <div id="messageContainer"></div>
  <h1>Welcome to Admin Dashboard</h1>
  <p>Select an option from the sidebar to get started.</p>
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

</body>
</html>

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
                    window.location.href = `adminMainpage.php?customer=${encodeURIComponent(selectedCustomerName)}`;
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
