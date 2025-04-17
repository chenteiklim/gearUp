
<style>
    html, body {
        background-color: #add8e6;
        margin: 0;
        padding: 0;
        width: 100%; /* Ensure full width */
        height: 100%; /* Ensure full height */
        display:flex;
        flex-direction:column;
        align-items:center;
    }
    
    #navContainer {
        display: flex;
        align-items: center;
        background-color: #BFB9FA;
        width: 100%; /* Adjust width as needed */
        height: 80px; /* Adjust height as needed */   
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
<img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
<button class="button" id="home">Trust Toradora</button>
<button class="button" id="customer">Customer Center</button>
<button class="button" id="name"><?php echo $username ?></button>
<form action="../login/logout.php" method="POST">
<button type="submit" id="logout" class="button">Log Out</button>
</form> 
</div>

<?php //include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/sellerNavbar.php';?>

<script>     
    document.getElementById("customer").addEventListener("click", function() {
        // Replace 'login.html' with the URL of your login page
        window.location.href = "../../Customer/mainpage/customerMainpage.php";
    });
</script>