<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gadgetShop";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Admin credentials
$adminUsername = 'admin'; // Change to your desired admin username
$adminEmail='chenteik_99@hotmail.com';
$adminPassword = password_hash('12183uopfdf{}fahTeik@>?)(*%', PASSWORD_DEFAULT); // Change to your desired password

// Insert the admin account into the database
$sql = "INSERT INTO superuser (username, email, passwords) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $adminUsername, $adminEmail, $adminPassword);

if ($stmt->execute()) {
    echo "Admin account created successfully.";
} else {
    echo "Error creating admin account: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>