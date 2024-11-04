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
$selectNameQuery = "SELECT * FROM users";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
}
    // Get the address value from the fetched row
    $name = $row['usernames'];

  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Request Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            margin-left:550px;
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="file"] {
            padding: 3px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
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
margin-left: 400px;
}
#logOut{
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
    </style>
</head>
<body>
<div id="navContainer"> 
        <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
        <button class="button" id="home">Computer Shop</button>
        <button class="button" id="cart" onclick="window.location.href = '../product/cart.php';"><?php echo 'Shopping Cart'; ?></button>
        <button class="button" id="tracking"><?php echo 'Tracking' ?></button>
        <button class="button" id="refund" type="submit" name="refund" value="">refund</button>
        <button class="button" id="seller" type="submit" name="seller" value="">Seller Center</button>
        <button class="button" id="name"><?php echo $name ?></button>
    </div>
    <h1>Seller Application Form</h1>
    <form id="sellerRequestForm" action="../homepage/request.php" method="POST" enctype="multipart/form-data">
        <label for="storeName">Store Name:</label>
        <input type="text" id="storeName" name="storeName" required>

        <label for="description">Store Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="businessID">Business ID:</label>
        <input type="text" id="businessID" name="businessID" required>

    
        <label for="contactInfo">Contact Information (0xx-1234567):</label>
        <input type="text" id="contactInfo" name="contactInfo" required>

        <button type="submit">Submit Request</button>
    </form>
</body>
</html>


<script>
   document.getElementById("home").addEventListener("click", function() {
    // Replace 'login.html' with the URL of your login page
    window.location.href = "../homepage/mainpage.php";
});  
</script>