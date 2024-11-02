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
$usernames=$_SESSION['username'];
mysqli_select_db($conn, $dbname);

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
$imageUrl = "/inti/gadgetShop/assets/" . $image;


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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>

</head>
<body>

<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Computer Shop</button>
    <button class="button" id="cart" onclick="window.location.href = '../product/cart.php';"><?php echo 'Shopping Cart'; ?></button>
    <button class="button" id="tracking"><?php echo 'Tracking' ?></button>
    <button class="button" id="refund" type="submit" name="refund" value="">refund</button>
    <button class="button" id="name"><?php echo $usernames ?></button>
    <form action="../userLogin/logout.php" method="POST">
      <button type="submit" id="logOut" class="button">Log Out</button>
    </form>    
</div>


</style>
    <div id="container">
        <div>
            <img class="keyboard" src="<?php echo $imageUrl; ?>" alt="">
        </div>
        <div>
            <div class="names"><?php echo $product_name; ?> </div>
            <div id="status" class="status"><?php echo $status.'sold' ; ?></div>
            <div class="stock"><?php echo $stock . 'stock available'; ?></div>
           
            <div id="price" class="prices"><?php echo'RM'.$price; ?></div>
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
                

                </div>
            </form>
            <div id="messageContainer2"></div>
            <div id="messageContainer3"></div>
        </div>
    </div>
<script src="product.js"></script>
</html>
