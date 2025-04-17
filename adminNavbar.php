<?php
?>
<style>
    
    html, body {
      margin: 0;
      padding: 0;
      width: 100%; /* Ensure full width */
      height: 100%; /* Ensure full height */
      display:flex;
      flex-direction:column;
      align-items:center;
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
  
    #navContainer {
      display: flex;
      align-items: center;
      background-color: #BFB9FA;
      width: 100%; /* Adjust width as needed */
      height: 80px; /* Adjust height as needed */   
    }
    #home{
        margin-left: 10px;
    }
    #name{
        margin-left: 800px;
    }
    #logout{
        height: 80px;    
    }
    #logoImg{
        margin-top: 25px;
        width: 35px;
        height: 35px;
        border-radius: 5px;
        margin-left: 100px;
    }
</style>
<div id="navContainer"> 
    <img id="logoImg" src="/inti/gadgetShop/assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Trust Toradora</button>
    <button class="button" id="wallet" onclick="window.location.href = '../wallet/adminWallet.php';">Wallet</button>
    <button class="button" id="name"><?php echo $username ?></button>
    <form action="/inti/gadgetShop/Admin/login/logout.php" method="POST">
      <button type="submit" id="logout" class="button">Log Out</button>
    </form> 
</div>

<script>     
    document.getElementById("home").addEventListener("click", function() {
        // Replace 'login.html' with the URL of your login page
        window.location.href = "/inti/gadgetShop/Admin/mainpage/adminMainpage.php";
    });
</script>

<?php //include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/adminNavbar.php';?>
