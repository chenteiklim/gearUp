
<?php

$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gearUp";

$conn = new mysqli($servername, $Username, $Password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

session_start();
mysqli_select_db($conn, $dbname);
$username=$_SESSION['username'];

if (isset($_POST['editSingle'])) {
  $product_id = $_POST['product_id'] ?? null; // Retrieve again from form
  $selectProductQuery = "SELECT product_name FROM products WHERE product_id = ?";
  $stmt = $conn->prepare($selectProductQuery);
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($row = $result->fetch_assoc()) {
      $product_name = $row['product_name'];
  } else {
      echo "Product not found.";
  }
}



// Check if the "Confirm Edit" button was clicked
if (isset($_POST['confirmEdit'])) {
    $product_id = $_POST['product_id'] ?? null; // Retrieve again from form
    $product_name = $_POST['productName'] ?? null;
    $price = $_POST['price'] ?? null;
    $stock = $_POST['stock'] ?? null;
    $productImage = $_FILES['productImage']['name'] ?? null;
    $updateFields = [];
    $params = [];
    $paramTypes = "";

    if (!empty($product_name)) {
        $updateFields[] = "product_name = ?";
        $params[] = $product_name;
        $paramTypes .= "s";
    }

    if (!empty($price)) {
        $updateFields[] = "price = ?";
        $params[] = $price;
        $paramTypes .= "d";
    }

    if (!empty($stock)) {
        $updateFields[] = "stock = ?";
        $params[] = $stock;
        $paramTypes .= "i";
    }

    if (!empty($productImage)) {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/inti/gearUp/assets/";
        $targetFile = $targetDir . basename($productImage);
        
        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $targetFile)) {
            $updateFields[] = "image = ?";
            $params[] = $productImage;
            $paramTypes .= "s";
        } else {
            echo "Image upload failed.";
            exit();
        }
    }

    if (!empty($updateFields)) {
        $sql = "UPDATE products SET " . implode(", ", $updateFields) . " WHERE product_id = ?";
        $params[] = $product_id;
        $paramTypes .= "i";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($paramTypes, ...$params);

        if ($stmt->execute()) {
          header("Location: ../view/viewProduct.php");
          exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "No changes made.";
    }
}
?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
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




<div id="navContainer"> 
    <img id="logoImg" src="../../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Computer Shop</button>
    <button class="button" id="name"><?php echo $username ?></button>
</div>
<div class="container">
  <div class="content">
 
<div id='title'>
  Edit <?php echo $product_name ?>
</div>

  <form action="editProduct.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($_POST['product_id'] ?? ''); ?>">
  <div id="nameContainer">
        <label for="productName">Product Name</label>
        <input type="text" placeholder="Enter Product Name" name="productName">
      </div>
      <div class="stockContainer">
        <label for="stock">Stock</label>
        <input type="number" placeholder="Enter Stock" name="stock">
      </div>
      
    <div class="imageContainer">
      <label for="productImage">Product Image </label>
      <input type="file" name="productImage">
    </div>
    <div class="priceContainer">   
      <label for="price">Price (RM)</label>
      <input type="number" placeholder="Enter price" name="price">
    </div>
    
    <input id="createProduct" type="submit" name="confirmEdit" value="Edit Product">
      
  </form>
  </div>
</div>
 
<script>
   var homeButton = document.getElementById("home");
  homeButton.addEventListener("click", function(event) {
    // Perform the navigation action here
    event.preventDefault()
    window.location.href = "../mainpage/mainpage.php";
  });
</script>

  
   