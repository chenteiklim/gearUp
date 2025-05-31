

<style>
    html, body {
        background-color: white;
        margin: 0;
        padding: 0;
        width: 100%; /* Ensure full width */
        height: 100%; /* Ensure full height */
        display:flex;
        flex-direction:column;
        align-items:center;
    }
     .button {
        background-color:rgb(33, 12, 102);
        width: 150px;
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
        align-items: center;
        background-color:#e8e8e8;
        width: 100%; /* Adjust width as needed */
        height: 80px; /* Adjust height as needed */   
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
        margin-top: 10px;
        width: 35px;
        height: 35px;
        border-radius: 5px;
        margin-left: 100px;
    }
 
</style>

<div id="navContainer"> 
<img id="logoImg" src="/inti/gearUp/assets/logo.jpg" alt="" srcset="">
<button class="navButton" id="home">GearUp</button>
<button class="navButton" id="name"><?php echo $username ?></button>
<form action="/inti/gearUp/Rider/login/logout.php" method="POST">
<button class="navButton" type="submit" id="logout" class="button">Log Out</button>
</form> 
</div>

<?php //include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Rider/riderNavbar.php';?>

<script>     

  var homeButton = document.getElementById("home");
  homeButton.addEventListener("click", function(event) {
    // Perform the navigation action here
    event.preventDefault()
    window.location.href = "/inti/gearUp/Rider/mainpage/riderMainpage.php";
  });
</script>