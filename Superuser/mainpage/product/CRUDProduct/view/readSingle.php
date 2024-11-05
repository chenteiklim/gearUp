
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

$selectNameQuery = "SELECT * FROM superuser";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
}
    // Get the address value from the fetched row
    $name = $row['username'];
if (isset($_SESSION['product_id'])) {
    $product_id = $_SESSION['product_id']; // Add a semicolon here

    // Assuming you have a database connection in $connection
    $query = "SELECT * FROM products WHERE product_id = '$product_id'";
    $result = $conn->query($query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Retrieve product details from the $row array
        $product_id = $row['product_id'];
        $product_name = $row['product_name'];
        $price = $row['price'];
        $image = $row['image'];
        $stock = $row['stock'];
        $status = $row['status'];
        $imageUrl = "/inti/gadgetShop/assets/" . $image;

    } else {
        echo 'Product not found.';
    }
}

else{
  echo('Product not selected');
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
    font-size:20px;
  margin-top:20px;
  width: 300px;
  height: 450px;
  background-color:white;
  display: flex;
  flex-direction: column;
  align-items:center;
  gap:5px;
}

#nameContainer{
  margin-top: 20px;
}


    
    body{
        display:flex;
        flex-direction:column;
        align-items:center;
        background-color: bisque;
        width: 100%;
        height: 1400px;
    }

  
input[type=file],input[type=text],input[type=number]{
  padding: 12px 20px;
  width:300px;
  margin: 8px 5px;
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
  margin-top:10px;
  margin-left:300px;
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


.content{
  margin-left:40px;
  width: 480px;
}
html, body {
        margin: 0;
        padding: 0;
        width: 100%; /* Ensure full width */
        height: 100%; /* Ensure full height */
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
    #title{
        font-size: 40px;
        margin-top:30px;
    }
    #id{
        margin-top:30px;
    }
    #stockContainer{
        margin-top:10px;
    }

    #status{
        margin-top:10px;
    }
    #img{
        width: 150px;
    }
    
    </style>
</head>




<div id="navContainer"> 
    <img id="logoImg" src="../../../../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Computer Shop</button>
    <button class="button" id="name"><?php echo $name ?></button>

</div>
<div class="container">
    <div id='title'>
    <?php echo $product_name ?>
    </div>

    <div id='id'>
    <?php echo 'Product_id: ' . $product_id ?>
    </div> 
    <div id="imageContainer">
    <img id='img' src="<?php echo $imageUrl; ?>" alt="Description of image" />
    </div>
    <div id="priceContainer">   
    <?php echo 'Price: ' . $price ?>
    </div>
    <div id='stockContainer'>
      <?php echo 'Stock: ' . $stock ?>
    </div>
    <div id='status'>
      <?php echo 'Status: ' . $status ?>
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

  
   