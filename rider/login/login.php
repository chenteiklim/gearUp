<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['passwords'];

  // Retrieve the user's data from the database based on the provided email
  $sql = "SELECT * FROM rider WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  
 
  if ($result === false) {
    // Display SQL error message
    echo "SQL Error: " . $conn->error;
  }

  if ($result->num_rows > 0) {
    // Fetch the user's data
    $row = $result->fetch_assoc();
    $hashed_password = $row['passwords'];
    $emailCode = $row['emailCode']; 

    if ($emailCode != 1) {
      // If the primary email token is not equal to 1, redirect to a specific page
      header("Location: login.html?success=1");
      exit(); // Stop further script execution
    } 
    
    
    elseif (!password_verify($password, $hashed_password)) {
      header("Location: login.html?success=3");
      exit(); // Stop further script execution
    } 
   
    else {
      $_SESSION['riderUsername'] = $username;
      header("Location: ../mainpage/riderMainpage.php");
      exit(); 
    }
  } 
  else {
    header("Location: login.html?success=5");
    exit(); // Stop further script execution
  }

  $stmt->close();
}

$conn->close();
?>

