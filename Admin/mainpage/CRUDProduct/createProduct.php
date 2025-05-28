<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();
$username=$_SESSION['adminUsername'];

if (isset($_POST['submit'])) {
  $productName = $_POST['productName'];
  $productImage = $_FILES['productImage']['name'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];

  $targetDir = "C:/xampp/htdocs/gadgetShop/assets/";
  if (!is_dir($targetDir)) {
      mkdir($targetDir, 0777, true);
  }
  $targetFile = $targetDir . basename($productImage);

  // Allowed file types
  $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
  $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  if (in_array($fileExtension, $allowedTypes)) {
    if (move_uploaded_file($_FILES['productImage']['tmp_name'], $targetFile)) {
        // Display the uploaded image
        $imageUrl = "/inti//gadgetShop/assets/" . basename($productImage);
        // Insert the product info into the database here
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
}

$nextProductIDQuery = "SELECT MAX(product_id) AS max_id FROM products";
$result = $conn->query($nextProductIDQuery);
$row = $result->fetch_assoc();
$maxProductID = $row['max_id'];
$nextProductID = $maxProductID + 1;

// Insert the product with the custom incrementing value
$insertProduct = "INSERT INTO products (product_id, product_name, image, price, stock, sellerName, status) VALUES ('$nextProductID', '$productName', '$productImage', '$price', '$stock', '$username', 0)";
    
        // Execute the SQL statement
        if ($conn->query($insertProduct) === TRUE) {
          header("Location: createProduct.php?success=1");
          exit(); // Stop further script execution        } else {
            echo "Error: " . $mysqli->error;
        }
    }
  ?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trust Toradora</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
    <style>
form{
  height:320px;

}
  
.container {
  margin-top:20px;
  width: 400px;
  height:550px;
  background-color:white;
  display: flex;
  flex-direction: column;
  gap:5px;
}
    
 
/* Apply to all field containers */
#nameContainer,
.stockContainer,
.imageContainer,
.priceContainer
{
  flex-direction: column; /* Align label and input vertically */
  gap: 5px; /* Add spacing between label and input */
  margin-bottom: 10px; 
  margin-top:30px;
  width: 250px;
}

/* Ensure input fields take full width */
input[type="file"],
input[type="text"],
input[type="number"] {
  margin-top:10px;
  width: 200px;
  height:40px;
}

#createProduct{
  background-color: #BFB9FA;
  color: black;
  padding: 14px 20px;
  border: none;
  cursor: pointer;
  width: 120px;
  margin-top:10px;
  margin-left:40px;
}


#title{
  font-size:20px;
  margin-top:30px;
  margin-left:40px;
}

.content{
  margin-left:80px;
  width: 480px;
}
 
html, body {
        margin: 0;
        padding: 0;
        width: 100%; /* Ensure full width */
        height: 100%; /* Ensure full height */
        background-color: #add8e6;
        display:flex;
        flex-direction:column;
        align-items:center;
    }
    
    #navContainer {
      display: flex;
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
        #home{
            margin-left: 10px;
            width:160px;
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
    
    /* Hide the up/down arrows in number input */
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield; /* Firefox */
}
.stockContainer {
  display: flex;
  flex-direction: column; /* Ensure label and input are stacked */
  gap: 5px;
  margin-bottom: 10px;
  margin-top: 30px;
  width: 250px; /* Ensure uniform width like other fields */
}   
    </style>
</head>

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/Admin/adminNavbar.php';?>

<div class="container">
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div id="successMessage" style="color: black; font-weight: bold; margin-top: 10px; margin-left: 100px;">
        Product created successfully!
    </div>
<?php endif; ?>
  <div class='content'>
  <div id='title'>
    Sell Product
  </div>
  <form action="createProduct.php" method="post" enctype="multipart/form-data">
      <div id="nameContainer">
        <label for="productName">Product Name</label>
        <input type="text" placeholder="Enter Product Name" name="productName" required>
      </div>
      <div class="stockContainer">
        <label for="stock">Stock</label>
        <input type="number" placeholder="Enter Stock" name="stock" required>
      </div>
      
    <div class="imageContainer">
      <label for="productImage">Product Image </label>
      <input type="file" name="productImage" required>
    </div>
    <div class="priceContainer">   
      <label for="price">Price (RM)</label>
      <input type="number" placeholder="Enter price" name="price" required>
    </div>
    
    <input id="createProduct" type="submit" name="submit" value="Sell">
      
  </form>
  </div>
</div>
<script>
  
  document.addEventListener("DOMContentLoaded", function () {
    var successMessage = document.getElementById("successMessage");

    if (successMessage) {
        setTimeout(function () {
            successMessage.style.display = "none";
        }, 10000); // Hide after 10 seconds

        // Remove "?success=1" from the URL after 10 seconds
        setTimeout(function () {
            var newUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        }, 10000);
    }
});
</script>

  
   