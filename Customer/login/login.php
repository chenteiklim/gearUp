<?php
$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";  

$conn = new mysqli($servername, $Username, $Password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

session_start();

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $_SESSION['username'] = $username;
  $password = $_POST['passwords'];

  // Select database
  mysqli_select_db($conn, $dbname); 

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
    $backupEmailCode = $row['backupEmailCode']; 

    if ($emailCode != 1) {
      // If the primary email token is not equal to 1, redirect to a specific page
      header("Location: login.html?success=1");
      exit(); // Stop further script execution

    } elseif ($backupEmailCode != 1) {
        // If the backup email token is not equal to 1, redirect to another specific page
        header("Location: login.html?success=2");
        exit(); // Stop further script execution

    } elseif (!password_verify($password, $hashed_password)) {
      header("Location: login.html?success=3");
      exit(); // Stop further script execution
    } 
   
    else {
        // If all checks pass, proceed to the main page
        header("Location: ../homepage/mainpage.php");
        exit(); // Ensure that further code execution is stopped after the redirection
    }
  } 
  else {
    // No user found with the provided email
    header("Location: login.html?success=3");
    exit();
  }

  $stmt->close();
}

$conn->close();
?>