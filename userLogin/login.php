<?php
$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['forgetPassword'])) {
    header("Location: verify.html");
    exit(); 
  }
session_start();
if (isset($_POST['submit'])) {
  $email=$_POST['email'];
  $_SESSION['email'] = $email;
  $password = $_POST['password'];

  mysqli_select_db($conn, $dbname); 
  $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
  $result = $conn->query($sql);
  if ($result === false) {
    // Display SQL error message
    echo "SQL Error: " . $conn->error;
  }
  if ($result->num_rows > 0) {
      // email exists and password exists, proceed with the login
      header("Location: mainpage.php");
      exit(); // Ensure that further code execution is stopped after the redirection
  } 

  else {
      // email doesn't exist, display an error message
      header("Location: login.html?success=5");
  }
  
}
?>