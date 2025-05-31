<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

session_start();
$username=$_SESSION['username'];

$selectNameQuery = "SELECT * FROM users WHERE usernames = '$username'";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
    $email = $row['email'];
    $role=$row['role'];
    $user_id=$row['user_id'];
}

$param1='pending';
   
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $storeName = $_POST['storeName'];
    $description = $_POST['description'];
    $businessID = $_POST['businessID'];
    $contact = $_POST['contactInfo'];
    $status = 'pending';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/encryption_helper.php';
    $contactEncrypted = openssl_encrypt($contact, 'AES-256-CBC', $encryption_key, 0, $encryption_iv);
// Insert into seller table
$stmt1 = $conn->prepare("INSERT INTO seller (storeName, user_id, sellerName, description, businessID, contact, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt1->bind_param("ssssss", $storeName, $user_id, $username, $description, $businessID, $contactEncrypted, $param1);

if ($stmt1->execute()) {
    // Get the inserted seller_id
    $seller_id = $conn->insert_id; 

    // Update user's role and seller_id in users table
    $stmt2 = $conn->prepare("UPDATE users SET role = ?, seller_id = ? WHERE user_id = ?");
    $role = 'seller';
    $stmt2->bind_param("sii", $role, $seller_id, $user_id);

    if ($stmt2->execute()) {
        $message3 = "Seller Application Form Submitted Successfully";
        header("Location: ../mainpage/customerMainpage.php?message3=" . urlencode($message3));
        exit();
    } else {
        echo "Error updating role: " . $stmt2->error;
    }
} else {
    echo "Error inserting seller data: " . $stmt1->error;
}

$stmt1->close();
$stmt2->close();
}
$conn->close();
?>
