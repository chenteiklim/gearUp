
<?php
$servername = "localhost";
$Username = "root";
$password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



mysqli_select_db($conn, $dbname); 

session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    
    // Query to get user information based on the username
    $selectNameQuery = "SELECT * FROM users WHERE usernames = '$username'";

    // Execute the query
    $result = $conn->query($selectNameQuery);

    // Check if there are results
    if ($result->num_rows > 0) {
        // Fetch the row from the result
        $row = $result->fetch_assoc();
        $seller_id = $row['seller_id'];
        echo $seller_id;
    } else {
        echo "No user found with that username.";
    }

    // Now, using the fetched seller_id, run the query to fetch messages for that seller
    if (isset($seller_id)) {
        // Get all customer IDs and names associated with this seller
        $selectCustomerIDsQuery = "SELECT DISTINCT m.customer_id, u.usernames AS customer_name 
        FROM messages m
        JOIN users u ON m.customer_id = u.user_id
        WHERE m.seller_id = '$seller_id' 
        ORDER BY m.timestamp ASC";
        
        // Execute the query
        $result2 = $conn->query($selectCustomerIDsQuery);
    
        // Check if there are results for the customer IDs
        if ($result2->num_rows > 0) {
            $customer_list = [];
            while ($row2 = $result2->fetch_assoc()) {
                // Store both customer_id and customer_name
                $customer_list[] = [
                    'customer_id' => $row2['customer_id'],
                    'customer_name' => $row2['customer_name']
                ];
            }
            echo '<pre>';
            var_dump($customer_list); // This will display the customer list structure and data
            echo '</pre>';
            
            // Send the customer list (with names) to the frontend as JSON
            echo "<script>var customerList = " . json_encode($customer_list) . ";</script>";
        } else {
            echo "No messages found for this seller.";
        }
    } else {
        echo('No seller found');
    }

    // Use prepared statements to prevent SQL injection
    $checkLogin = $conn->prepare("SELECT * FROM users WHERE usernames = ?");
    $checkLogin->bind_param("s", $username); // "s" denotes the parameter type (string)
    $checkLogin->execute();
    
    $result = $checkLogin->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $role = $row['role']; // Correctly access the 'role' field without extra $
        
        if ($role !== 'seller') { // Make sure 'Seller' is in quotes
          $message4 = "Login failed, please submit seller application form";
          header("Location: ../../Customer/homepage/mainpage.php?message4=" . urlencode($message4));
  
        }
        else{   
            
            ?>
            <head>
            <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Product</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
                <link rel="stylesheet" href="mainpage.css">
            </head>

            <div id="navContainer"> 
                <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
                <button class="button" id="home">Trust Toradora</button>
                <button class="button" id="customer">Customer Center</button>
                <button class="button" id="name"><?php echo $username ?></button>
                <form action="../login/logout.php" method="POST">
                <button type="submit" id="logout" class="button">Log Out</button>
                </form> 
            </div>
            <div>

            </div>
            <div id='content'>
            <div>
                <p id='title'>Seller Mainpage </p>
                <img id='gadget' src="../../assets/deco.png" alt="">
            </div>
            <div id="container">
                <div id="messageContainer"></div>                  
            
                <div class='product'>
                    <button id="product" class="button"><?php echo 'Manage Product' ?></button>
                        <div class="dropdowns">
                            <button onclick="location.href='../CRUDProduct/createProduct.php'">Create Product</button>
                            <button onclick="location.href='../CRUDProduct/view/viewProduct.php'">View Product</button>
                        </div>
                    </div>

                </div>

            </div>
            
            <div id="chatIcon">
    <i class="fa fa-comment"></i>
</div>

<div id="chatPopup" style="display:none;">
    <div id="chatHeader">
        <span>Live Chat</span>
        <button id="closeChat">&times;</button>
    </div>
    <div id="chatBody">
        <div id="customerList"></div>

        <div id="chatMessages">
            <!-- Chat messages will be dynamically displayed here -->
        </div>
    </div>
    <div id="chatFooter">
        <input type="text" id="chatInput" placeholder="Type a message..." />
        <button id="sendMessage">Send</button>
    </div>
    <input type="hidden" id="customerId" value="<?php echo $customer_id; ?>">
</div>

<!-- Customer list displayed here -->
<?php
        }
    }  
    else{
        echo('no User Found');
    }
}
else{
?>
  <div>
    <h1>You are not authorized to access this page</h1>
    <h2>Please register or login.</h2>
  </div>
<?php
} 

?>
<script>
    var seller_id = <?php echo json_encode($seller_id); ?>;
    var seller_name = <?php echo json_encode($username); ?>;
    console.log(seller_name)
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    console.log(seller_id);
    console.log("Inside DOMContentLoaded, Seller Name:", seller_name); // Debugging

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

    function loadMessages(customer_id, customer_name) {
        console.log(seller_name, 'asdadsadsa')
        console.log(seller_id)
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
                loadMessages(customer_id, customer_name);
            })
            .catch(error => console.error("Error sending message:", error));
    });
});
</script>


   