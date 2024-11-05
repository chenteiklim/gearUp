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
    $status = 'pending';
    // Prepare an SQL statement to insert data into sellerrequest table
    $stmt = $conn->prepare("INSERT INTO sellerrequest (storeName, user_id, description, businessID, contact, username, email, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $storeName, $user_id, $description, $businessID, $contact, $username, $email, $role, $status);

    // Execute the statement
    if ($stmt->execute()) {
        $message3 = "Seller Application Form Submitted Successfully";
        header("Location: mainpage.php?message3=" . urlencode($message3));
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
