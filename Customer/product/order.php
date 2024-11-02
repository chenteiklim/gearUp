<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
session_start();
mysqli_select_db($conn, $dbname);
$usernames=$_SESSION['username'];
$stmt = $conn->prepare("SELECT email FROM users WHERE usernames = ?");
$stmt->bind_param("s", $usernames);
$stmt->execute();

// Get the result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the result as an associative array
    $user = $result->fetch_assoc();
    $email = $user['email']; // Access the email field

} else {
    echo "No user found with that username.";
}
$stmt->close();

$updateCartTable = "";
$updateOrderTable = "";

if (isset($_POST['addCart'])) {
    $product_id = $_SESSION['product_id'];

    if (isset($_SESSION['orders_id'])) {
        $order_id=$_SESSION['orders_id'];
    }
    else if (isset($_SESSION['order_id'])) {
        $order_id=$_SESSION['order_id'];
    }
    else{          
        $maxOrderIdQuery = "SELECT MAX(order_id) AS max_order_id FROM `orders` WHERE email = '$email'";
        $result = $conn->query($maxOrderIdQuery);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $order_id = 0;
            if($order_id==0){
                $order_id=1;
            }
        }
    } 
    
    $selectUserQuery = "SELECT * FROM users WHERE email = '$email'";
    // Execute the query
    $result = $conn->query($selectUserQuery);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $usernames = $row['usernames'];
        $address = $row['address'];
        $user_id = $row['user_id'];
        $_SESSION['user_id']=$user_id;
    }
  

    $sql = "SELECT * FROM products WHERE product_id = " . $product_id;
    $result = $conn->query($sql);
    // Check if the query was successful
    if ($result && $result->num_rows > 0) {
        // Fetch the row from the result set
        $row = $result->fetch_assoc();
        // Retrieve the name and price from the row
        $product_name = $row['product_name'];
        $price = $row['price'];
        $image=$row['image'];
        $quantity = $_POST['quantity_input'];
        $_SESSION['quantity'] = $quantity;
        $total_price=$quantity*$price;
    }
  // Initialize the variable
$existingQuantity = 0; 

// Construct the table name
$tableName = "cart" . $order_id . "_" . $user_id;

// Check if the cart table exists
$query = "SHOW TABLES LIKE '$tableName'";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    // Check if the product already exists in the cart
    $checkExist = "SELECT quantity FROM $tableName WHERE product_id = $product_id";
    $checkResult = $conn->query($checkExist);

    if ($checkResult && $checkResult->num_rows > 0) {
        $row3 = $checkResult->fetch_assoc();
        $existingQuantity = $row3['quantity'];
    }

    // Calculate new quantity and total price if the product exists
    if ($existingQuantity > 0) {
        $newQuantity = $existingQuantity + $quantity;
        $newTotalPrice = $newQuantity * $price;

        // Update the cart and orders tables
        $updateQuery = "UPDATE $tableName SET quantity = $newQuantity, total_price = $newTotalPrice WHERE product_id = $product_id";
        $conn->query($updateQuery);
        
        $updateOrderQuery = "UPDATE orders SET quantity = $newQuantity, total_price = $newTotalPrice WHERE product_id = $product_id";
        $conn->query($updateOrderQuery);
        $message2 = "Cart added successfully";
        header("Location: ../homepage/mainpage.php?message2=" . urlencode($message2));
    }
    //if want purchase new product
    else{
        $insertcart = "INSERT INTO $tableName (user_id, order_id, product_id, quantity, name, email, address, product_name, price, image, total_price) VALUES ('$user_id', '$order_id', '$product_id', '$quantity', '$usernames', '$email', '$address', '$product_name', '$price', '$image', '$total_price')";
        $conn->query($insertcart); // Execute the insert query
        // Insert data into the orders table
        $insertorders = "INSERT INTO orders (user_id, order_id, product_id, quantity, name, email, address, product_name, price, image, total_price, order_status) VALUES ('$user_id', '$order_id', '$product_id', '$quantity', '$usernames', '$email', '$address', '$product_name', '$price', '$image', '$total_price', 'cart')";
        $conn->query($insertorders); // Execute the insert query   
        $message2 = "Cart added successfully";
        header("Location: ../homepage/mainpage.php?message2=" . urlencode($message2));
    }
    
} 
else {
    // Create the cart table if it doesn't exist
    $createTableQuery = "CREATE TABLE IF NOT EXISTS $tableName (
        id INT(11) PRIMARY KEY AUTO_INCREMENT,
        user_id INT(11),
        order_id INT(11),
        product_id INT(11),
        quantity INT(11),
        name VARCHAR(255),
        email VARCHAR(255),
        address VARCHAR(255),
        product_name VARCHAR(255),
        price DECIMAL(10, 2),
        image VARCHAR(255),
        total_price DECIMAL(10, 2)
    )";
    $conn->query($createTableQuery);
    $insertcart = "INSERT INTO $tableName (user_id, order_id, product_id, quantity, name, email, address, product_name, price, image, total_price) VALUES ('$user_id', '$order_id', '$product_id', '$quantity', '$usernames', '$email', '$address', '$product_name', '$price', '$image', '$total_price')";
    $conn->query($insertcart); // Execute the insert query

    // Insert data into the orders table
    $insertorders = "INSERT INTO orders (user_id, order_id, product_id, quantity, name, email, address, product_name, price, image, total_price, order_status) VALUES ('$user_id', '$order_id', '$product_id', '$quantity', '$usernames', '$email', '$address', '$product_name', '$price', '$image', '$total_price', 'cart')";
    $conn->query($insertorders); // Execute the insert query

    header("Location: ../homepage/mainpage.php?message=" . urlencode($message2));
}
        
   
    
}

?>

