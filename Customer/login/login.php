<?php


include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';


session_start();

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['passwords'];


  // Retrieve the user's data from the database based on the provided email
  $sql = "SELECT * FROM users WHERE usernames = ?";
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
    $role = $row['role'];

    if ($emailCode != 1) {
      // If the primary email token is not equal to 1, redirect to a specific page
      header("Location: login.php?success=1");
      exit(); // Stop further script execution
    } 
    
    
    elseif (!password_verify($password, $hashed_password)) {
      header("Location: login.php?success=3");
      exit(); // Stop further script execution
    } 
   
    else {
      $_SESSION['username'] = $username;
      header("Location: ../mainpage/customerMainpage.php");
      exit(); 
    }
  } 
  else {
    header("Location: login.php?success=5");
    exit(); // Stop further script execution
  }

  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="login.css">

</head>
<body>
  
<div id="navContainer"> 
  <img id="logoImg" src="/inti/gearUp/assets/logo.jpg" alt="" srcset="">
  <button class="navButton" id="home">GearUp</button>
  <button class="navButton" id="register">Register</button>
</div>
    <div id="container">

      <div id="purple_container">
        <div id="title">
        <div id="titleText">
          Login
        </div>
        </div>
        <form action="login.php" method="post">
         
          <div id="emailContainer">
            <input type="text" placeholder="Enter Username" name="username" required autocomplete="off">
          </div>
        
          <div id="passwordContainer">
              <input type="password" id="password" placeholder="Password" name="passwords" required autocomplete="off">
              <button id="show" type="button">Show</button>
            </div>
    
        <div id="messageContainer"></div>
       
        <div id="signUpContainer">
          <input id="signUpBtn" class="button" type="submit" name="submit" value="Login">
          <p> <a id="forgotBtn" href="../forgotPwd/verifyForgotPwd.html">Forget password</a></p>
                 
        </div>
      </form>
    </div>  
</div>
<script src="login.js"></script>
  </body>
</html>
   