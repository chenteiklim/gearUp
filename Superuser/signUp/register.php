<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

mysqli_select_db($conn, $dbname); 

// Check if setup has been completed by querying the database
$sqlCheck = "SELECT setup_complete FROM setup WHERE id = 1"; // Assuming only one row in setup table
$result = $conn->query($sqlCheck);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if ($row['setup_complete'] == 0) {
        // If setup is not complete, proceed to create the first admin
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $createdAt = date("Y-m-d H:i:s");

        // Admin credentials (change these values as needed)
        $adminUsername = 'admin';  // Admin username
        $adminEmail = 'abc@hotmail.com';  // Admin email
        $adminPassword = password_hash('67201saopfdf{}fahTeik@>?)(*%', PASSWORD_DEFAULT); // Securely hashed password

        // Prepare SQL query to insert the admin data into the 'superuser' table
        $sql = "INSERT INTO superuser (username, email, passwords, created_at) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        // Bind parameters to the SQL query
        $stmt->bind_param("ssss", $adminUsername, $adminEmail, $adminPassword, $createdAt);

        // Execute the query
        if ($stmt->execute()) {
            echo "Admin account created successfully.";

            // Set setup_complete to 1 to mark setup as complete
            $sqlUpdate = "UPDATE setup SET setup_complete = 1 WHERE id = 1";
            $conn->query($sqlUpdate);

        } else {
            echo "Error creating admin account: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // If setup is complete, stop further execution
        echo "Setup is already complete. This script cannot be run again.";
    }
} else {
    echo "Setup record not found.";
}

$conn->close();
?>