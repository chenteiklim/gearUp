<?php
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : "Guest"; 
// Get the current page filename
$currentPage = basename($_SERVER['PHP_SELF']);

// Define homepage/main page filenames
$homePages = ['customerHomepage.php']; // Remove 'customerMainpage.php'

// Check if the current page is the homepage or main page
$isHomePage = in_array($currentPage, $homePages);
?>
<style>
    
#navContainer {
  display: flex;
  background-color: #BFB9FA;
  align-items: center;
  width: 100%; /* Adjust width as needed */
  height: 80px; /* Adjust height as needed */   
}

#logoImg{
    width: 35px;
    height: 35px;
    border-radius: 5px;
    margin-left: 50px;
}

#name{
  margin-left: 250px
}

.button {
        background-color: #BFB9FA;
        width: 150px;
        color: black;
        cursor: pointer;
        padding-left: 30px;
        padding-right: 30px;
        padding-top: 10px;
        padding-bottom: 10px;
        font-size: 14px;
        border: none;
     }
     .button:hover{
        transform: scale(0.9);
        background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
    }
 
</style>
<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="">

    <!-- Home button becomes unclickable if on homepage or main page -->
    <button class="button" id="home"
        onclick="window.location.href = '<?php echo $isHomePage ? '/inti/gadgetShop/Customer/homepage/customerHomepage.php' : '/inti/gadgetShop/Customer/mainpage/customerMainpage.php'; ?>'">
        Trust Toradora
    </button>

    <?php if ($isLoggedIn): ?>
        <button class="button" id="cart" onclick="window.location.href = '../product/cart.php';">Shopping Cart</button>
        <button class="button" id="tracking" onclick="window.location.href = '../tracking/tracking.php';">Tracking</button>
        <button class="button" id="seller" onclick="window.location.href = '../seller/request.php';">Seller Request</button>
        <button class="button" id="sellerCenter">Seller Center</button>
        <button class="button" id="wallet" onclick="window.location.href = '../wallet/wallet.php';">Wallet</button>
        <button class="button" id="name"><?php echo htmlspecialchars($username); ?></button>
        <form action="../login/logout.php" method="POST">
            <button type="submit" id="logOut" class="button">Log Out</button>
        </form>    
    <?php else: ?>
        <button id="login" class="button" onclick="">Login</button>
        <button id="register" class="button" onclick="">Register</button>
    <?php endif; ?>
</div>

<script>
    
  var tracking = document.getElementById("tracking");
  
  tracking.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "../tracking/tracking.php";
  });
  
  
  var sellerCenter = document.getElementById("sellerCenter");
  
  sellerCenter.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "../../Seller/mainpage/sellerMainpage.php";
  });

  var seller = document.getElementById("seller");
  
  seller.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "seller.php";
  });

  
  var wallet = document.getElementById("wallet");
  
  wallet.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "../wallet/wallet.php";
  });

  document.addEventListener("DOMContentLoaded", function () {
    const chatPopup = document.getElementById("chatPopup");
    const closeChat = document.getElementById("closeChat");
    
    closeChat.addEventListener("click", function () {
        chatPopup.style.display = "none"; // Hide chat window
    });
});
</script>

<?php //include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/customerNavbar.php';?>
