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
      text-align: center;
      margin-top: 30px;
      color: #333;
    }

    #sellerRequestForm {
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
      border: 1px solid #ddd;
      max-width: 500px;
      margin: 40px auto;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #444;
    }

    input[type="text"],
    textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      transition: border-color 0.3s;
    }

    input[type="text"]:focus,
    textarea:focus {
      border-color: #9999ff;
      outline: none;
    }

    input[type="file"] {
      margin-bottom: 20px;
    }

    #request {
      background-color: black;
      color: white;
      padding: 10px 30px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s;
      margin: 0 auto;
      display: block;
    }

    #request:hover {
      background-color: #222;
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

  <button class="button" id="request" type="submit">Submit Request</button>
</form>

</body>
</html>