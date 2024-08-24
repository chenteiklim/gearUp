<?php
$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['forgetPassword'])) {
    header("Location: verify.html");
    exit(); 
  }
session_start();
if (isset($_POST['submit'])) {

  mysqli_select_db($conn, $dbname); 
  $sql = "SELECT email, password FROM admin WHERE email = 'chenteik_99@hotmail.com' AND password = 'wizard12183'";
  $result = $conn->query($sql);
  if ($result === false) {
    // Display SQL error message
    echo "SQL Error: " . $conn->error;
  }
  if ($result->num_rows > 0) {
    header("Location: adminHomepage.php");
      // email exists and password exists, proceed with the login
      exit(); // Ensure that further code execution is stopped after the redirection
  } 

  else {
        header("Location: sellerLogin.html?success=1");
        exit();
  }
  
}
?>