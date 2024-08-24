
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
// Execute the query

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
            'price' => $row['price'],
            'image' => $row['image'],
            'stock' => $row['stock'],
        );

        // Add the product array to the products array
        $products[] = $product;
    }
  }

  if (isset($_POST['submit'])) {
    $product_id = $_POST['product_id'];
    // Store the product ID in the session
    $_SESSION['product_id'] = $product_id;
    header("Location: edit.php");
  }
  
  
  ?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
    <style>

  
#container {
    width:100%;
  display: flex;
  flex-direction: column;
  align-items:center;
}


.button {
    background-color: black;
    color: white;
    cursor: pointer;
    width: 200px;
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
    
    body{
        background-color: bisque;
        
    }


#navContainer{
        width:100%;
        display:flex;
        align-items:center;
        justify-content:center;
        background-color: black;
    }
    
    form {
        border: 3px solid #f1f1f1;
        margin-top:30px;
    }

input[type=text],[type=number]{
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

#editProduct{
  margin-left:30px;
  margin-top:15px;
  background-color: blueviolet;
  color: white;
  padding: 14px 20px;
  border: none;
  cursor: pointer;
  width: 120px;
}

#register{
  background-color: blueviolet;
  color: white;
  padding: 14px 20px;
  border: none;
  cursor: pointer;
  width: 120px;
}

#reset{
  background-color: blue;
  color: white;
  padding: 10px 16px;
  margin-top: 10px;
  border: none;
  cursor: pointer;
  width: 160px;
  border-radius: 5%;
}

.img{
  width:50px;
  height:50px;
}
#title{
  font-size:20px;
  margin-top:30px;
  margin-left:200px;
}
#firstForm{
  margin-left:200px;
  margin-right:200px;
}
    </style>
</head>

<div id="navContainer"> 

    <!-- Your form fields here -->
    <img class='img' src="pitStop.png" alt="" srcset="">

    <button id="back" class="button"><?php echo 'Back' ?></button>

</div>

<div id='title'>
    Select which product you want to edit
</div>

<form action="editProduct.php" id="firstForm" method="post">
  <div class="container">
    <div id="nameContainer">
      <?php
      foreach ($products as $product) {
        $product_id = $product['product_id'];
        $product_name = $product['product_name'];
        // ... (retrieve other product attributes as needed)
    
        // Generate the button HTML dynamically
        echo '<form action="editProduct.php" method="post">
        <h3>' . $product_name . '</h3>
        <input type="hidden" name="product_id" value="' . $product_id . '">
        <button class="button" type="submit" name="submit">Edit product</button>
      </form>';
    
    }
      ?>
    </div>
   
</form>
 
<script>

var back= document.getElementById("back");

back.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "adminHomepage.php";
});
</script>

  
   