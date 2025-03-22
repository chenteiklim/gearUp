
<?php
$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



mysqli_select_db($conn, $dbname); 

session_start();
if (!isset($_SESSION['username'])) {
  echo "<h1>This Website is Not Accessible</h1>";
  echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
  exit;  // Stop further execution of the script
}    

mysqli_select_db($conn, $dbname);
$username=$_SESSION['username'];


?>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="refundRequestForm.css">
</head>

<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Trust Toradora</button>

    <button class="button" id="name"><?php echo $username ?></button>
    <form action="../login/logout.php" method="POST">
    <button type="submit" id="logout" class="button">Log Out</button>
    </form> 
</div>
<form id= 'formContainer' action="refund.php" method="POST">
  <div id='container'>
      <div id="orders">
        <label for="orders_id">Orders ID:</label>
        <input type="number" name="orders_id" value="<?php echo isset($_POST['orders_id']) ? $_POST['orders_id'] : ''; ?>" readonly required>
    </div>
    <div id="productName">
      <label for="productName">Product Name:</label>
      <input type="text" name="productName" value="<?php echo isset($_POST['product_name']) ? $_POST['product_name'] : ''; ?>" readonly required>
      </div>
   
  <div id='textArea'>
    <label id ='reason' for="reason">Reason:</label>
    <textarea name="reason" required></textarea>
  </div>
    
  <div id='upload'>
    <label id='uploadImage' for="proof">Upload Image/Video of Product:</label>
    <input type="file" name="proof" accept="image/*,video/*" required>          
  </div>
  
    <button class='button' id="refundBtn"  type="submit">Request Refund</button>
  </div>
</form>