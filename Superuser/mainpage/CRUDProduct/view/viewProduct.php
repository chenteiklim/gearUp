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
$email=$_SESSION['emailAdmin'];
$selectNameQuery = "SELECT * FROM superuser WHERE email = '$email'";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
    $username = $row['username'];
}

$selectProductQuery = "SELECT * FROM products";
$stmt = $conn->prepare($selectProductQuery);
$stmt->execute();
// Execute the query
$result = $stmt->get_result();

$products = array(); // Initialize an empty array to store the products

if ($result->num_rows > 0) {
    // Loop through the result and retrieve each product as an array
    while ($row = $result->fetch_assoc()) {
        $product = array(
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'sellerName' => $row['sellerName'],
            'price' => $row['price'],
            'stock' => $row['stock'],
            'status' => $row['status'],
            'image'=> $row['image'],
            'sellerName'=> $row['sellerName'],
        );

        // Add the product array to the products array
        $products[] = $product;
    }
  }

 
  
  ?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <style>
   
          
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

      .container{
        background-color:white;
        display:flex;
        flex-direction:column;
        align-items:center;
        padding-bottom:30px;

      }
      
      #productContainer{
        margin-left: 30px;
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 60px;
        margin-top: 10px;
        flex-wrap: wrap;
        width: 1490px;
        height:auto;
      }
            
      #navContainer {
        display: flex;
        background-color: #BFB9FA;
        align-items: center;
        width: 100%; /* Adjust width as needed */
        height: 80px; /* Adjust height as needed */   
      }

      html, body {
        background-color: #add8e6;
        margin: 0;
        padding: 0;
        width: 100%; /* Ensure full width */
        height: 100%; /* Ensure full height */
        display:flex;
        flex-direction:column;
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
    
    #img{
      width: 160px;
      height:180px;
    }

    .row{
      margin-top:15px;
    }

    #edit{
      margin-top:30px;
    }
    #delete{
      margin-top:10px;
    }

    #title{
      font-size:20px;
      font-weight:600;
    }

    </style>
</head>

<div id="navContainer"> 
    <img id="logoImg" src="../../../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Trust Toradora</button>
    <button class="button" id="name"><?php echo $username ?></button>
</div>



<div id="container">
  <div id='productContainer'>
    <?php foreach ($products as $product): ?>
      <div class="container">
        <div id='title' class='row'>
            <?php echo htmlspecialchars($product['product_name']); ?>
        </div>

        <div id='id' class='row'>
            <?php echo 'Product ID: ' . htmlspecialchars($product['product_id']); ?>
        </div> 

        <div id="imageContainer" class='row'>
            <img id='img' src="<?php echo htmlspecialchars('/inti/gadgetShop/assets/' . $product['image']); ?>" alt="Product Image" />
        </div>

        <div id="priceContainer" class='row'>   
            <?php echo 'Price: RM ' . htmlspecialchars($product['price']); ?>
        </div>

        <div id='stockContainer' class='row'>
            <?php echo 'Stock: ' . htmlspecialchars($product['stock']); ?>
        </div>

        <div id='status' class='row'>
            <?php echo 'Sold: ' . htmlspecialchars($product['status']); ?>
        </div>

        <div id='seller' class='row'>
            <?php echo 'Seller Name: ' . htmlspecialchars($product['sellerName']); ?>
        </div>

        <!-- Hidden field inside the form -->
        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">

        <!-- Submit button inside the form -->
        <form method="POST" action="editProduct.php">
          <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
          <button id="edit" class="button" type="submit" name="editSingle">Edit product</button>
        </form>

        <form method="POST" action="deleteProduct.php" onsubmit="return confirm('Are you sure you want to delete this product?');">
          <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
          <button type="submit" name="delete" class="button" style="background-color: red; color: white;">Delete</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
  var homeButton = document.getElementById("home");
  homeButton.addEventListener("click", function(event) {
    // Perform the navigation action here
    event.preventDefault()
    window.location.href = "../../mainpage.php";
  });
</script>
