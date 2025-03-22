
<?php

$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
session_start();
if (!isset($_SESSION['username'])) {
  echo "<h1>This Website is Not Accessible</h1>";
  echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
  exit;  // Stop further execution of the script
}
    

mysqli_select_db($conn, $dbname);
$username=$_SESSION['username'];

$selectNameQuery = "SELECT * FROM users WHERE usernames = '$username'";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];

}

    $maxIdQuery = "SELECT MAX(product_id) AS max_id FROM products";
    $maxIdResult = $conn->query($maxIdQuery);
    
    if ($maxIdResult && $maxIdResult->num_rows > 0) {
        $row = $maxIdResult->fetch_assoc();
        $maxId = $row['max_id'];
    }
    
    // Query to retrieve all rows in ascending order
    $selectRowsQuery = "SELECT * FROM products ORDER BY product_id ASC";
    $selectRowsResult = $conn->query($selectRowsQuery);
    
    $rows = []; // Initialize an empty array to store the rows
    
    if ($selectRowsResult && $selectRowsResult->num_rows > 0) {
        while ($row = $selectRowsResult->fetch_assoc()) {
            $rows[] = $row; // Add each row to the array
        }
    }
    
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['sellerName'])) {
  $sellerName = $_POST['sellerName'];

  $stmt = $conn->prepare("SELECT seller_id FROM users WHERE usernames = ?");
  $stmt->bind_param("s", $sellerName);
  $stmt->execute();
  $stmt->bind_result($seller_id);
  $stmt->fetch();

  echo $seller_id ? $seller_id : '';

  $stmt->close();
  exit(); // Prevent further page rendering for AJAX
}
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['message']) && isset($_POST['seller_id'])) {
  session_start(); // Ensure session is started to get user_id
  $customer_id = $user_id; // Sender (buyer)
  $seller_id = $_POST['seller_id']; // Receiver (seller)
  $message = trim($_POST['message']); // Sanitize message input
  $param1=0;
  if (!empty($message)) {
      $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, customer_id, seller_id, message, is_read, timestamp) VALUES (?, ?, ?, ?, ?, ?, NOW())");
      $stmt->bind_param("iiiisi", $customer_id, $seller_id, $customer_id, $seller_id, $message, $param1);
      
      if ($stmt->execute()) {
          echo "Message sent successfully";
      } else {
          echo "Error sending message";
      }

      $stmt->close();
  } else {
      echo "Message cannot be empty";
  }

  exit(); // Stop further execution (important for AJAX)
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST['seller_id'])) {
      $seller_id = $_POST['seller_id'];
      $customer_id = $user_id ?? 0; // Make sure session user ID is set

      // Fetch chat messages
      $stmt = $conn->prepare("SELECT * FROM messages WHERE 
          (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
          ORDER BY timestamp ASC");
      $stmt->bind_param("iiii", $customer_id, $seller_id, $seller_id, $customer_id);
      $stmt->execute();
      $messages = $stmt->get_result();

      while ($msg = $messages->fetch_assoc()) {
          echo "<p><b>" . ($msg['sender_id'] == $customer_id ? "You" : "Seller") . ":</b> " . htmlspecialchars($msg['message']) . "</p>";
      }

      $stmt->close();
      exit(); // Stop further execution (important for AJAX)
  }
}
  ?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
   <link rel="stylesheet" href="mainpage.css">
</head>
<body>

<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Trust Toradora</button>
    <button class="button" id="cart" onclick="window.location.href = '../product/cart.php';"><?php echo 'Shopping Cart'; ?></button>
    <button class="button" id="tracking"><?php echo 'Tracking' ?></button>
    <button class="button" id="refund" type="submit" name="refund" value="">refund</button>
    <button class="button" id="seller" type="submit" name="seller" value="">Seller Request</button>
    <button class="button" id="sellerCenter" type="submit" name="sellerCenter" value="">Seller Center</button>
    <button class="button" id="name"><?php echo $username ?></button>
    <form action="../login/logout.php" method="POST">
      <button type="submit" id="logOut" class="button">Log Out</button>
    </form>    
</div>
<div id="messageContainer"></div>

</div>
<div id="container">

  <?php
  
$productHTML = '';

  // Loop through the array of rows
  foreach ($rows as $index => $row) {
    $product_id = $row['product_id'];
    $product_name = $row['product_name'];
    $price = $row['price'];
    $image = $row['image'];
    $stock = $row['stock'];
    $status = $row['status'];
    $sellerName = $row['sellerName'];
    $imageUrl = "/inti/gadgetShop/assets/" . $image;
    $soldText = ($status > 0) ? '<div class="status">' . $status . ' sold</div>' : '';
    
    $newProduct2 = '
    <div class="product">
      <div class="imageContainer">
        <img class="item" src="' . $imageUrl . '" alt="">
      </div>
      <div class="productDetails">
        <div class="product_name">' . $product_name . '</div>
        <div class="price">
          <div class="unit">RM</div>
          <div>' . $price . '</div>
        </div>
        <div class="stock">' . ($stock > 0 ? $stock . ' stock available' : 'Out of stock') . '</div>
        ' . $soldText . '
        <form action="" method="post">
          <button class="button" type="submit" name="view" value="' . $product_id . '">View</button>
        </form>
        
        <!-- Chat Button -->
        <div class="chat">
          <input type="hidden" id="seller_id" name="seller_id">
          <button id="chat" class="button chat-button" data-seller="' . htmlspecialchars($sellerName) . '" onclick="openChat(this)">
              Chat with ' . htmlspecialchars($sellerName) . '
          </button>
        </div>
      </div>
    </div>';
  
  $productHTML .= $newProduct2;
}
echo $productHTML;
  ?>
  <div id="chatIcon">
    <i class="fa fa-comment"></i>
</div>
<!-- Chat Popup Window -->
<div id="chatPopup">
    <div id="chatHeader">
        <span>Live Chat</span>
        <button id="closeChat">&times;</button>
    </div>
    <div id="chatBody">
        <p>Welcome! How can we help you?</p>
        <div id="chatMessages"></div>
    </div>
    <div id="chatFooter">
        <input type="text" id="chatInput" placeholder="Type a message..." />
        <button id="sendMessage">Send</button>
    </div>
</div>
<?php
  if (isset($_POST['view'])) {
    $_SESSION['product_id'] = $_POST['view'];
    
    if (!empty($_SESSION['product_id'])) {
       echo '<script>window.location.href = "../product/product.php";</script>'; 
    } else {
       echo "Product ID not found in the session.";
    }
    exit;
  }
?>
<script>
  window.onload = function() {

  var urlParams = new URLSearchParams(window.location.search);
  const message = urlParams.get('message');
  const message2 = urlParams.get('message2');
  const message3 = urlParams.get('message3');
  const message4 = urlParams.get('message4');


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

  if (message2) {
    var messageContainer = document.getElementById("messageContainer");
    messageContainer.textContent = decodeURIComponent(message2); // Decode the URL-encoded message
    messageContainer.style.display = "block";
    messageContainer.classList.add("message-container");
    
    setTimeout(function() {
      messageContainer.style.display = "none";
      messageContainer.classList.remove("message-container");
      
      // Clear the message from the URL
      const url = new URL(window.location);
      url.searchParams.delete('message2');
      window.history.replaceState({}, document.title, url);
    }, 3000);
  }



  if (message3) {
    var messageContainer = document.getElementById("messageContainer");
    messageContainer.textContent = decodeURIComponent(message3); // Decode the URL-encoded message
    messageContainer.style.display = "block";
    messageContainer.classList.add("message-container");
    
    setTimeout(function() {
      messageContainer.style.display = "none";
      messageContainer.classList.remove("message-container");
      
      // Clear the message from the URL
      const url = new URL(window.location);
      url.searchParams.delete('message3');
      window.history.replaceState({}, document.title, url);
    }, 10000);
  }
  
  if (message4) {
    var messageContainer = document.getElementById("messageContainer");
    messageContainer.textContent = decodeURIComponent(message4); // Decode the URL-encoded message
    messageContainer.style.display = "block";
    messageContainer.classList.add("message-container");
    
    setTimeout(function() {
      messageContainer.style.display = "none";
      messageContainer.classList.remove("message-container");
      
      // Clear the message from the URL
      const url = new URL(window.location);
      url.searchParams.delete('message4');
      window.history.replaceState({}, document.title, url);
    }, 10000);
  }
}


var tracking = document.getElementById("tracking");

tracking.addEventListener("click", function() {
// Perform the navigation action here
window.location.href = "../tracking/tracking.php";
});


var seller = document.getElementById("seller");

seller.addEventListener("click", function() {
// Perform the navigation action here
window.location.href = "seller.php";
});

function openChat(button) {
    let sellerName = button.getAttribute("data-seller");

    console.log("Opening chat for seller:", sellerName); // Debugging

    fetchSellerId(sellerName); // Call AJAX function
}
document.getElementById("sellerCenter").addEventListener("click", function() {

  window.location.href = "../../Seller/mainpage/mainpage.php";
});
document.querySelectorAll(".chat-button").forEach(button => {
    button.addEventListener("click", function () {
        var sellerName = this.getAttribute("data-seller"); // Get seller name from button

        var chatPopup = document.getElementById("chatPopup");
        var chatHeader = document.getElementById("chatHeader");
        var chatBody = document.getElementById("chatBody");
        var chatFooter = document.getElementById("chatFooter");

        // Set the chat title dynamically
        chatHeader.innerHTML = `<span>Chat with ${sellerName}</span> <button id="closeChat">&times;</button>`;

        // Show the chat popup
        chatPopup.style.display = "flex";
        setTimeout(() => {
            chatBody.style.display = "block";
            chatFooter.style.display = "flex";
        }, 200);

        // Close chat when clicking the close button
        document.getElementById("closeChat").addEventListener("click", function () {
            chatPopup.style.display = "none";
        });

        // Fetch the seller_id and load messages
        fetchSellerId(sellerName);
    });
});

// Function to get seller_id via AJAX
function fetchSellerId(sellerName) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "mainpage.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    console.log("Sending request to mainpage.php with sellerName:", sellerName);

    xhr.onload = function () {

        if (this.status === 200) {
            let seller_id = this.responseText.trim();
            if (seller_id) {
                console.log("Seller ID found:", seller_id);
                document.getElementById("seller_id").value = seller_id;
                loadMessages(seller_id); 
            } else {
                console.error("Error: Seller not found.");
            }
        } else {
            console.error("HTTP Error:", this.status);
        }
    };

    xhr.onerror = function () {
        console.error("Request failed - Check Network tab in DevTools.");
    };

    xhr.send("sellerName=" + encodeURIComponent(sellerName));
}
// Function to load chat messages via AJAX
function loadMessages(seller_id) {
    console.log("Fetching messages for seller_id:", seller_id); // Debug

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "mainpage.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.status === 200) {
            console.log("Chat messages received:", this.responseText); // Debug
            document.getElementById("chatBody").innerHTML = this.responseText;
        } else {
            console.error("Error loading chat messages.");
        }
    };

    xhr.onerror = function () {
        console.error("Request failed.");
    };

    xhr.send("seller_id=" + encodeURIComponent(seller_id));
}
document.getElementById("sendMessage").addEventListener("click", function (e) {
    e.preventDefault(); // Prevent form submission

    let chatInput = document.getElementById("chatInput");
    let chatMessages = document.getElementById("chatBody");
    let seller_id = document.getElementById("seller_id").value;

    if (chatInput.value.trim() !== "") {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "mainpage.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (this.status === 200) {
                console.log("Server response:", this.responseText); // Debugging

                if (this.responseText.includes("Message sent successfully")) {
                    let newMessage = document.createElement("p");
                    newMessage.innerHTML = "<b>You:</b> " + chatInput.value;
                    chatMessages.appendChild(newMessage);
                    chatInput.value = ""; // Clear input field
                    chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll to the latest message
                } else {
                    alert("Error: " + this.responseText);
                }
            }
        };

        xhr.send("seller_id=" + encodeURIComponent(seller_id) + "&message=" + encodeURIComponent(chatInput.value));
    }
});
</script>
  
</body>
  </html>
   