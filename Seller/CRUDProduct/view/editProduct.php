
<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';


session_start();
$username=$_SESSION['username'];
$product_name = ''; // Initialize early to avoid undefined warning
$product_id = $_POST['product_id'] ?? null;

if ($product_id) {
    $selectProductQuery = "SELECT product_name FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($selectProductQuery);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $product_name = $row['product_name'];
    } else {
        $product_name = "Unknown Product";
    }
}
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
  margin-left: 500px;
  margin-top:40px;
  width: 400px;
  height:550px;
  background-color:white;
  display: flex;
  flex-direction: column;
  gap:5px;
}
  #editForm{
    margin-left:100px
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

#createProduct {
  background-color: #3498db;
  color: white;
  margin-left: 20px;
  padding: 12px 24px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  font-size: 14px;
  margin-top: 10px;
}

#createProduct:hover {
    background-color: #2980b9;
}

#title{
  font-size:20px;
  margin-top:30px;
  margin-left:150px;
}

.content{
  margin-left:80px;
  width: 480px;
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

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Seller/sellerNavbar.php';?>

 <div class='container'>

<div id='title'>
  Edit <?php echo $product_name ?>
</div>

  <form id='editForm' action="editProduct.php" method="post" enctype="multipart/form-data">
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

  
   