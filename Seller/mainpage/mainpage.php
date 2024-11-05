
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="mainpage.css">
</head>
<body>
    
<div id="navContainer"> 
<img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
<button class="button" id="home">Pit Stop</button>
<button class="button" id="CustomerCenter">Customer Center</button>
</div>

</body>

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

if (isset($_SESSION['username'])) {
  $name = $_SESSION['username'];
  
  // Use prepared statements to prevent SQL injection
  $checkLogin = $conn->prepare("SELECT * FROM users WHERE usernames = ?");
  $checkLogin->bind_param("s", $name); // "s" denotes the parameter type (string)
  $checkLogin->execute();
  
  $result = $checkLogin->get_result();
  
  if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $role = $row['role']; // Correctly access the 'role' field without extra $
      
      if ($role !== 'Seller') { // Make sure 'Seller' is in quotes
        ?>
        <div>
          <h1>Welcome!</h1>
          <p>You are not authorized to access seller-specific features. Please Press Customer Center to go back</p>
        </div>
        <script src="mainpage.js"></script>
      <?php
      }
      else{
        echo('zzz');
      ?>
      <head>
      <meta charset="UTF-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Product</title>
          <link rel="stylesheet" href="mainpage.css">


      </head>
      <div id='content'>
        <div>
        <p id='title'>Welcome to be a seller. Boost your income by providing best quality product and service. </p>
        <img id='gadget' src="../../assets/deco.png" alt="">
        </div>
        <div id="container">
          <div id="messageContainer"></div>
          <div class="view">
          <button id="view" class="btn"><?php echo 'View All Products' ?></button>
          </div>
          <div class="sell">
          <button id="sell" class="btn"><?php echo 'Create Product' ?></button>
          </div>
          <div class="edit">
          <button id="edit" class="btn"><?php echo 'Edit Product' ?></button>
          </div>
          <div class="edit">
          <button id="delete" class="btn"><?php echo 'delete Product' ?></button>
          </div>
          <div class="sales">
          <button id="sales" class="btn"><?php echo 'Sales' ?></button>
          </div>
      </div>

      </div>

      <script src="mainpage.js"></script>
      <?php
    }
  }  
  else{
    echo('no User Found');
  }
}
else{
  ?>
  <div>
  <h1>You are not authorized to access this page</h1>
  <h2>Please register or login.</h2>
  </div>
  
  <?php

} 
?>
  
   