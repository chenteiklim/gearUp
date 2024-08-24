
<?php

$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

session_start();
mysqli_select_db($conn, $dbname);
$selectNameQuery = "SELECT name FROM admin";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
}
    // Get the address value from the fetched row
    $name = $row['name'];

  ?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
    <style>

body{
  display:flex;
  flex-direction:column;
  align-items:center;
}
#container {
  margin-top:50px;
  background-color:white;
    width:400px;
    height:400px;
  display: flex;
  flex-direction: column;
  align-items:center;
}


.button {
    background-color: darkblue;
    color: white;
    cursor: pointer;
    width:200px;
    margin-left: 20px;
    padding-left: 30px;
    padding-right: 30px;
    padding-top: 10px;
    padding-bottom: 10px;
    font-size: 16px;
    }
    
    button:active {
      transform: scale(0.9);
      background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
    }
  
    
.btn {
    background-color: black;
    color: white;
    cursor: pointer;
    width:200px;
    margin-left: 20px;
    padding-left: 30px;
    padding-right: 30px;
    padding-top: 10px;
    padding-bottom: 10px;
    font-size: 16px;
    }
    
    .btn:active {
      transform: scale(0.9);
      background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
    }
    

    body{
        background-color: bisque;
        width: 100%;
        height: 1400px;
    }
 

    .sell{
        margin-top:80px;
    }
    
    #edit{
        margin-top:20px;
    }
    #admin{
        margin-top:20px;
    }
    
    #sales{
        margin-top:20px;
    }

    .img{
      width:50px;
      height:50px;
    }

    .navContainer{
      display:flex;
      align-items:center;
      justify-content:center;
      background-color:black;
      width:100%;
      height:60px;
    }
    
    .message-container {
      
      background-color: rgba(0, 0, 0, 0.7);
      position: fixed;
      padding-left: 120px;
      padding-right: 120px;
      padding-top: 90px;
      padding-bottom: 90px;
      color: white;
      font-size: 30px;
      display: flex;
    align-items: center;
    justify-content: center;
    }
    </style>
</head>
<div class=navContainer>
  <img class='img' src="pitStop.png" alt="" srcset="">

    <button class="button"><?php echo $name ?></button>
    <button id="logOut" class="button"><?php echo 'Log Out' ?></button>
    
</div>
<div id="container">
    <div id="messageContainer"></div>
    <div class="sell">
    <button id="sell" class="btn"><?php echo 'Sell Product' ?></button>
    </div>
    <div class="edit">
    <button id="edit" class="btn"><?php echo 'Edit Product' ?></button>
    </div>
    <div class="admin">
    <button id="admin" class="btn"><?php echo 'Sales' ?></button>
    </div>
    <div class="sales">
    <button id="sales" class="btn"><?php echo 'Daily sales' ?></button>
    </div>
</div>
 
<script>
var logOutButton = document.getElementById("logOut");

logOutButton.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "sellerLogin.html";
});

var sell= document.getElementById("sell");
sell.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "createProduct.php";
});

var edit= document.getElementById("edit");

edit.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "editProduct.php";
});

var admin= document.getElementById("admin");
admin.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "sales.php";
});


var sales= document.getElementById("sales");
sales.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "dailySales.php";
});


window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    var message = urlParams.get('message');
    
    if (message) {
        var messageContainer = document.getElementById("messageContainer");
        messageContainer.textContent = message;
        messageContainer.style.display = "block";
        messageContainer.classList.add("message-container");
        setTimeout(function() {
            messageContainer.style.display = "none";
        }, 2000);
    }
}
</script>

  
   