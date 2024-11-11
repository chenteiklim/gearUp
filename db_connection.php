<?php
$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";
// Create a database connection
$conn = new mysqli($servername, $Username, $Password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// to include connection
//include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShopOld/db_connection.php';
?>