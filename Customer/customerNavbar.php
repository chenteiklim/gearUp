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
html, body {
        background-color: white;
         margin: 0;
         padding: 0;
         width: 100%; /* Ensure full width */
         height: 100%; /* Ensure full height */
       }
          
  
      
    .button {
    background-color:black;
    color: white;
    cursor: pointer;
    padding-left: 30px;
    padding-right: 30px;
    padding-top: 10px;
    padding-bottom: 10px;
    font-size: 14px;
    border: none;
}   

#navContainer {
  display: flex;
background-color:#e8e8e8;
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

.navButton {
      
        background-color:#e8e8e8;
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
      background: #222; 
  }

</style>
<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="">

    <!-- Home button becomes unclickable if on homepage or main page -->
    <button class="navButton" id="home"
        onclick="window.location.href = '<?php echo $isHomePage ? '/inti/gadgetShop/Customer/homepage/customerHomepage.php' : '/inti/gadgetShop/Customer/mainpage/customerMainpage.php'; ?>'">
        GearUp
    </button>

    <?php if ($isLoggedIn): ?>
        <button class="navButton" id="cart" onclick="window.location.href = '../product/cart.php';">Shopping Cart</button>
        <button class="navButton" id="tracking" onclick="window.location.href = '../tracking/tracking.php';">Tracking</button>
        <button class="navButton" id="seller" onclick="window.location.href = '../sellerRequest/sellerRequest.php';">Seller Request</button>
        <button class="navButton" id="sellerCenter">Seller Center</button>
        <button class="navButton" id="wallet" onclick="window.location.href = '../wallet/wallet.php';">Wallet</button>
        <button class="navButton" id="notification" >Notification</button>
        <button class="navButton" id="name"><?php echo htmlspecialchars($username); ?></button>
        <form action="../login/logout.php" method="POST">
            <button type="submit" id="logOut" class="navButton">Log Out</button>
        </form>    
    <?php else: ?>
        <button id="login" class="navButton" onclick="window.location.href = '../login/login.php';">Login</button>
        <button id="register" class="navButton" onclick="window.location.href = '../signUp/register.php';">Register</button>
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

  
  var wallet = document.getElementById("wallet");
  
  wallet.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "../wallet/wallet.php";
  });

 
</script>

<?php //include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/Customer/customerNavbar.php';?>
