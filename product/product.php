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
$product_id = $_SESSION['product_id'];
$email=$_SESSION['email'];
mysqli_select_db($conn, $dbname);
// Execute the query
$selectNameQuery = "SELECT usernames FROM users WHERE email = '$email'";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
}
    // Get the address value from the fetched row
    $usernames = $row['usernames'];

$sql = "SELECT * FROM products WHERE product_id = '$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the user ID from the result
    $row = $result->fetch_assoc();
    $product_name = $row['product_name'];
    $price = $row['price'];

    $image= $row['image'];
    $stock=$row['stock'];
    $status = $row['status'];
}
$imageUrl = "/gadgetShop/assets/" . $image;


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="logo.jpg" type="image/jpg">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Homepage</title>

<link rel="stylesheet" href="product.css">

</head>
<body>
<div id="container">
  <div id="bigTitle">
        <img id="logoImg" src="../assets/icon.png" alt="" srcset="">
        <div id="fanime">Authentic</div>  
        <div id="help">Get help</div>  
        <div id="signUp">Log Out</div>
</div>
<div>  
<img id="image" src="<?php echo $imageUrl; ?>" alt="">
            <div class="names"><?php echo $product_name; ?> </div>

            
            <div id="status" class="status"><?php echo $status.'sold' ; ?></div>
            <div class="stock"><?php echo $stock . 'stock available'; ?></div>
        
        <div id="price-<?php echo 0; ?>" class="prices"><?php echo'RM'.$price; ?></div>
        <form action="order.php?product_id=1" method="post">
            <div class="quantity">
                <label for="quantity" class="quantity_label">Quantity:</label>
                <div id="messageContainer"></div>
                <button id="increment">+</button>
                <input type="number" id="quantity_input" name="quantity_input" min="1" value="1">
                <button id="decrement">-</button>
            </div>
            <div class="buyBtn">
                <div>
                    <input id="addCartButton" class="button" type="submit" name="addCart" value="Add To Cart">
                </div>
                <div>
                    <input id="buyNowButton" class="button" type="submit" name="addOrder" value="Buy Now">
                </div>

            </div>
        </form>
        <div id="messageContainer2"></div>
        <div id="messageContainer3"></div>

  </div>
    </div>
</div>
</div>

<script>
const quantity_input=document.getElementById('quantity_input');  
const incrementButton = document.getElementById('increment');

incrementButton.addEventListener('click', function(event) {
    event.preventDefault();
    currentValue = parseInt(quantity_input.value)
    quantity_input.value=currentValue+1
});
const decrementButton = document.getElementById('decrement');

decrementButton.addEventListener('click', function(event) {
    if (currentValue>1){
        event.preventDefault();
        let currentValue = parseInt(quantity_input.value)
        quantity_input.value = currentValue - 1;
    }
    else{
        event.preventDefault();
    }
})



  document.getElementById("Cart").addEventListener("click", function() {
    window.location.href = "cart.php";
  });

  document.getElementById("buyNowButton").addEventListener("click", function(event) {
    event.preventDefault();
    window.location.href = "cart.php";
  });

  document.getElementById('back').addEventListener('click', function(e) {
    e.preventDefault();
    window.location.href = 'mainpage.php';
  })

  window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    var message = urlParams.get('message');
    var message3 = urlParams.get('message3');
    
    if (message) {
        var messageContainer = document.getElementById("messageContainer");
        messageContainer.textContent = message;
        messageContainer.style.display = "block";
        messageContainer.classList.add("message-container");
        setTimeout(function() {
            messageContainer.style.display = "none";
        }, 3000);
    }
     
    if (message3) {
        var messageContainer3 = document.getElementById("messageContainer3");
        messageContainer3.textContent = message;
        messageContainer3.style.display = "block";
        messageContainer3.classList.add("message-container");
        setTimeout(function() {
            messageContainer3.style.display = "none";
        }, 3000);
    }
    
    if (urlParams.get("redirect") === "true") {
        var messageContainer2 = document.getElementById("messageContainer2");
        messageContainer2.textContent = "Your cart is empty";
        messageContainer2.style.display = "block";
        messageContainer2.classList.add("message-container");
        setTimeout(function() {
            messageContainer2.style.display = "none";
        }, 3000);
    }
};
  
  
</script>

  
    