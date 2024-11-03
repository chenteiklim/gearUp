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
$selectNameQuery = "SELECT * FROM seller";
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
}
    $name = $row['usernames'];


$selectProductQuery = "SELECT * FROM products";

// Execute the query
$result = $conn->query($selectProductQuery);

$products = array(); // Initialize an empty array to store the products

if ($result->num_rows > 0) {
    // Loop through the result and retrieve each product as an array
    while ($row = $result->fetch_assoc()) {
        $product = array(
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
        );

        // Add the product array to the products array
        $products[] = $product;
    }
  }

  if (isset($_POST['submit'])) {
    $product_id = $_POST['product_id'];
    // Store the product ID in the session
    $_SESSION['product_id'] = $product_id;
    header("Location: readSingle.php");
  }
  
  
  ?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <style>
    html, body {
            margin: 0;
            padding: 0;
            width: 100%; /* Ensure full width */
            height: 100%; /* Ensure full height */
        }
        body{
          background-color: bisque;
        }
    
    #navContainer {
        display: flex;
        background-color: black;
        width: 100%; /* Adjust width as needed */
        height: 80px; /* Adjust height as needed */
        
        /* Ensure it remains visible within the container */
      
      }
    .button {
        background-color: black;
        color: white;
        cursor: pointer;
        padding-left: 30px;
        padding-right: 30px;
        padding-top: 10px;
        padding-bottom: 10px;
        font-size: 12px;
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
    
    button:hover{
        transform: scale(0.9);
        background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
    }

      #container{
        background-color: 	#CDCDCD;
      }
      
      #productContainer{
        margin-left: 20px;
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
        margin-top: 10px;
        flex-wrap: wrap;
        width: 1510px;
        height:auto;
      }

    </style>
</head>

<div id="navContainer"> 
    <img id="logoImg" src="../../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Computer Shop</button>
    <button class="button" id="name"><?php echo $name ?></button>
</div>



<div id="container">
  <div id="productContainer">
  <?php
  foreach ($products as $product) {
    $product_id = $product['product_id'];
    $product_name = $product['product_name'];
    // ... (retrieve other product attributes as needed)

    // Generate the button HTML dynamically
    echo '<form action="readProduct.php" method="post">
    <h3>' . $product_name . '</h3>
    <input type="hidden" name="product_id" value="' . $product_id . '">
    <button class="button" type="submit" name="submit">View product</button>
  </form>';

  }
  ?>
  </div>
</div>

<script>
  var homeButton = document.getElementById("home");
  homeButton.addEventListener("click", function(event) {
    // Perform the navigation action here
    event.preventDefault()
    window.location.href = "../../mainpage/mainpage.php";
  });
</script>
