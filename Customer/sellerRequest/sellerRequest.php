<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Customer/customerNavbar.php';


session_start();
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
            max-width: 300px;
            margin: auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input, textarea {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="file"] {
            padding: 3px;
        }

#sellerRequestForm{
    margin-top:20px;
    margin-left:550px;
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


    </style>
</head>
<body>
    <h1>Seller Application Form</h1>
    <form id="sellerRequestForm" action="request.php" method="POST" enctype="multipart/form-data">
        <label for="storeName">Store Name:</label>
        <input type="text" id="storeName" name="storeName" required>

        <label for="description">Store Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <label for="businessID">Business ID (optional):</label>
        <input type="text" id="businessID" name="businessID">

    
        <label for="contactInfo">Contact Information (0xx-1234567):</label>
        <input type="text" id="contactInfo" name="contactInfo" required>

        <button class='button' type="submit">Submit Request</button>
    </form>
</body>
</html>


