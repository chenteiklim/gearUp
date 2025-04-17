

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
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Trust Toradora</button>
    <button class="button" id="name"><?php echo $username ?></button>

    <form action="../login/logout.php" method="POST">
    <button type="submit" id="logout" class="button">Log Out</button>
    </form> 
</div>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/riderNavbar.php';?>
