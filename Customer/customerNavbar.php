<?php
$username = $_SESSION['username'] 
?>
<style>
html, body {
    background-color: white;
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
}



#navContainer {
    display: flex;
    background-color: #e8e8e8;
    align-items: center;
    width: 100%;
    height: 80px;
}

#logoImg {
    width: 35px;
    height: 35px;
    border-radius: 5px;
    margin-left: 50px;
}

#name {
    margin-left: 250px;
}

.navButton {
    background-color: #e8e8e8;
    width: 150px;
    color: black;
    cursor: pointer;
    padding: 10px 30px;
    font-size: 14px;
    border: none;
}
.button {
    background-color: black;
    color: white;
    cursor: pointer;
    padding: 10px 30px;
    font-size: 14px;
    border: none;
}
.button:hover {
    transform: scale(0.9);
    background: #222;
}
</style>

<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="">

    <!-- Always go to mainpage only -->
    <button class="navButton" id="home"
        onclick="window.location.href = '/inti/gearUp/Customer/mainpage/customerMainpage.php';">
        GearUp
    </button>

        <button class="navButton" onclick="window.location.href = '../product/cart.php';">Shopping Cart</button>
        <button class="navButton" onclick="window.location.href = '../tracking/tracking.php';">Tracking</button>
        <button class="navButton" onclick="window.location.href = '../sellerRequest/sellerRequest.php';">Seller Request</button>
        <button class="navButton" id="sellerCenter">Seller Center</button>
        <button class="navButton" onclick="window.location.href = '../wallet/wallet.php';">Wallet</button>
        <button class="navButton" id="name" onclick="window.location.href = '/inti/gearUp/Customer/profile/profile.php';">
            <?php echo htmlspecialchars($username); ?>
        </button>
        <form action="../login/logout.php" method="POST" style="display:inline;">
            <button type="submit" class="navButton">Log Out</button>
        </form>    
  
</div>

<script>
document.getElementById("sellerCenter").addEventListener("click", function () {
    window.location.href = "../../Seller/mainpage/sellerMainpage.php";
});
</script>