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
$username=$_SESSION['username'];

mysqli_select_db($conn, $dbname);
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
   
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $storeName = $_POST['storeName'];
    $description = $_POST['description'];
    $businessID = $_POST['businessID'];
    $contact = $_POST['contactInfo'];
    $bankAccount = $_POST['accountInfo'];
    $status = 'pending';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/encryption_helper.php';
    $contactEncrypted = openssl_encrypt($contact, 'AES-256-CBC', $encryption_key, 0, $encryption_iv);
    $bankAccountEncrypted = openssl_encrypt($bankAccount, 'AES-256-CBC', $encryption_key, 0, $encryption_iv);
// Insert into seller table
$stmt1 = $conn->prepare("INSERT INTO seller (storeName, user_id, description, businessID, contact, username, email, bankAccount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt1->bind_param("ssssssss", $storeName, $user_id, $description, $businessID, $contactEncrypted, $username, $email, $bankAccountEncrypted);

if ($stmt1->execute()) {
    // Get the inserted seller_id
    $seller_id = $conn->insert_id; 

    // Update user's role and seller_id in users table
    $stmt2 = $conn->prepare("UPDATE users SET role = ?, seller_id = ? WHERE user_id = ?");
    $role = 'seller';
    $stmt2->bind_param("sii", $role, $seller_id, $user_id);

    if ($stmt2->execute()) {
        $message3 = "Seller Application Form Submitted Successfully";
        header("Location: mainpage.php?message3=" . urlencode($message3));
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
