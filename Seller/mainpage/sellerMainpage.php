
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';
session_start();

if (isset($_SESSION['username'])) {
$username=$_SESSION['username'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/Seller/sellerNavbar.php';

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
          header("Location: ../../Customer/mainpage/customerMainpage.php?message4=" . urlencode($message4));
  
        }
        else{   
            
            ?>
            <head>
            <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Product</title>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
                <link rel="stylesheet" href="sellerMainpage.css">
                <script>
     document.addEventListener("DOMContentLoaded", function () {
        <?php if (isset($_SESSION['username'])) { ?>
            localStorage.setItem("sellerName", "<?php echo $_SESSION['username']; ?>");
            console.log("Seller name set in localStorage:", localStorage.getItem("sellerName")); // Debugging
        <?php } else { ?>
            console.error("Customer name not found in session!");
        <?php } ?>
    });
</script>
            </head>
            <div>
                <p id='title'>Seller Mainpage </p>
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
                <div>
                    <button id="sales" class="button" onclick="location.href='sales.php'">Sales</button>
                </div>

                </div>

            </div>
            
  <div id="chatIcon">
    <i class="fa fa-comment"></i>
</div>
<div id="customerList">
    <div id="customerNavbar">
      <h3 id="customerHeader">
          Chat with Customer
      </h3>
      <button id="closeCustomerList" class="close-btn">&times;</button>
    </div>              
    <div id="customersContainer">
        <!-- Seller items will be dynamically inserted here -->
    </div>
</div>
<!-- Chat Popup Window -->
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
<?php
        }
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

<script src="sellerMainpage.js"></script>
<script src="getCustomer.js"></script>
<script src="chat.js"></script>


   