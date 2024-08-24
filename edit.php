
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
$product_id=$_SESSION['product_id'];
mysqli_select_db($conn, $dbname);


$selectproductName = "SELECT product_name FROM products WHERE product_id = '$product_id'";
// Execute the query
$result2 = $conn->query($selectproductName);

if ($result2->num_rows > 0) {
    // Fetch the row from the result
    $row2 = $result2->fetch_assoc();
}
    // Get the address value from the fetched row
$product_name = $row2['product_name'];


    if (isset($_POST['submits'])) {
        $product_name = $_POST['productName'];
        $productImage = $_FILES['productImage']['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        
        $targetDir = "C:/xampp/htdocs/Project/EnterpriseProject/"; // Directory where you want to store the uploaded files
        $filename = $_FILES['productImage']['name'];

        $targetFile = $targetDir . $filename;
        move_uploaded_file($_FILES['productImage']['tmp_name'], $targetFile);

        // Perform any necessary validation or sanitization of the input values here
        
        // Insert the form data into the product table
        $editProduct = "UPDATE products SET product_name = '$product_name', price = $price, stock = $stock, image='$productImage' WHERE product_id = $product_id";
    
        // Execute the SQL statement
        if ($conn->query($editProduct) === TRUE) {
          $successMessage = "edit Product successfully!";
          header("Location: adminHomepage.php?message=" . urlencode($successMessage)); 
        } else {
            echo "Error: " . $mysqli->error;
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

  
#container {
    width:1200px;
  display: flex;
  flex-direction: column;
  align-items:center;
}


.button {
    background-color: black;
    color: white;
    cursor: pointer;
    margin-left: 20px;
    padding-left: 30px;
    padding-right: 30px;
    padding-top: 10px;
    padding-bottom: 10px;
    font-size: 16px;
    }
#btn {
    background-color: black;
    color: white;
    cursor: pointer;
    padding-left: 30px;
    padding-right: 30px;
    padding-top: 10px;
    margin-top: 10px;
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
      display:flex;
      align-items:center;
      justify-content:center;
      background-color: black;
    }

    form {
      margin-top:30px;
      border: 3px solid #f1f1f1;
    }

input[type=text],[type=number]{
  width: 40%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

#createProduct{
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

#title{
  margin-left:40%;
  font-size:25px;
  margin-top:30px;
}

img{
  width:50px;
  height:50px;
}

form{
  display:flex;
 padding-left:200px;
 padding-right:200px;
  margin-left:400px;
  margin-right:400px;
}

    </style>
</head>

<div id="navContainer"> 

    <!-- Your form fields here -->
    <img class='img' src="pitStop.png" alt="" srcset="">
    <button id="back" class="button"><?php echo 'Back' ?></button>
</div>

<div id='title'>
  Edit <?php echo $product_name ?>
</div>
<form action="edit.php" method="post" enctype="multipart/form-data">
  <div class="container">
    <div id="nameContainer">
      <label for="productName"><b>New Product Name</b></label>
      
      <input type="text" placeholder="Enter Product Name" name="productName">
    </div>
    <br>
    <label for="productImage"><b>New Product Image address</b></label>
    <input type="file" name="productImage">
    <br>
    <br>
    <label for="price"><b>New Price</b></label>
    <input type="number" placeholder="Enter price" name="price">
    <div>

    <label for="stock"><b>Edited Stock</b></label>
    <input type="text" placeholder="Enter how many stock " name="stock">
    </div>
    <br>

    <input id="createProduct" type="submit" name="submits" value="Edit Product">
    <div>
</form>
 
<script>

var back= document.getElementById("back");

back.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "editProduct.php";
});
</script>

  
   