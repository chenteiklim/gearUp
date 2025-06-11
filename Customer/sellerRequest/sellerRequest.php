<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';


session_start();
$username = $_SESSION['username'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Customer/customerNavbar.php';

  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Request Form</title>
    <style>
       
        h1 {
            margin-left:550px;
            color: #333;
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
#sellerRequestForm {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border: 1px solid #ddd;
    max-width: 400px;
    margin: 40px auto;
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

      
    
        <label for="contactInfo">Contact Information (0xx-1234567):</label>
        <input type="text" id="contactInfo" name="contactInfo" required>

        <button class='button' type="submit">Submit Request</button>
    </form>
</body>
</html>


