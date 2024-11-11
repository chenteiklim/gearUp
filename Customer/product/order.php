<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShopOld/db_connection.php';

session_start();
mysqli_select_db($conn, $dbname);
$username=$_SESSION['username'];

$stmt = $conn->prepare("SELECT email FROM users WHERE usernames = ?");
$stmt->bind_param("s", $username);
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


if (isset($_POST['addCart'])) {
    $product_id = $_SESSION['product_id'];
    $selectOrderQuery = "SELECT MAX(order_id) AS max_order_id FROM orders WHERE email = ?";
    $stmt = $conn->prepare($selectOrderQuery);
    
    if ($stmt) {
        // Bind the email parameter
        $stmt->bind_param("s", $email); // "s" indicates the type is string 
        
        // Execute the statement
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
    
        // Initialize order_id to 1 by default
        $order_id = 1; 
    
        // Check if there are results
        if ($result->num_rows > 0) {
            // Fetch the first result
            $row = $result->fetch_assoc();
            if ($row['max_order_id'] === NULL) {
                // max_order_id is NULL, order_id remains 1
                $_SESSION['order_id']=$order_id;
            } else if ($row['max_order_id'] > 0) {
                // Fetch the order status for the latest order
                $orderStatusQuery = "SELECT order_status FROM orders WHERE order_id = ? AND email = ?";
                $statusStmt = $conn->prepare($orderStatusQuery);
                if ($statusStmt) {
                    $statusStmt->bind_param("is", $row['max_order_id'], $email);
                    $statusStmt->execute();
                    $statusResult = $statusStmt->get_result();
    
                    if ($statusResult->num_rows > 0) {
                        echo('hi');
                        $statusRow = $statusResult->fetch_assoc();
                        // Check the order status
                        if ($statusRow['order_status'] === 'purchased') {
                            $order_id = $row['max_order_id'] + 1; // Increment order_id if purchased
                            $_SESSION['order_id']=$order_id;
                        } else {
                            $order_id = $row['max_order_id']; // Otherwise keep it the same
                        }
                    }
                    $statusStmt->close();
                }
            }
            else{
                echo('order_id=0');
            }
        } else {
            // No results found for max_order_id
            echo "No orders found for this user.";
        }
        $stmt->close();
    }
    else{
        echo('error');
    }
    
    // Output the final order_id
    echo "Final Order ID: " . $order_id;
    

    $selectUserQuery = "SELECT * FROM users WHERE email = '$email'";
    // Execute the query
    $result = $conn->query($selectUserQuery);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
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
$tableName = "cart" . $user_id;

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
        $insertcart = "INSERT INTO $tableName (user_id, order_id, product_id, quantity, name, email, address, product_name, price, image, total_price) VALUES ('$user_id', '$order_id', '$product_id', '$quantity', '$username', '$email', '$address', '$product_name', '$price', '$image', '$total_price')";
        $conn->query($insertcart); // Execute the insert query
        // Insert data into the orders table
        $insertorders = "INSERT INTO orders (user_id, order_id, product_id, quantity, name, email, address, product_name, price, image, total_price, order_status) VALUES ('$user_id', '$order_id', '$product_id', '$quantity', '$username', '$email', '$address', '$product_name', '$price', '$image', '$total_price', 'cart')";
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
    $insertcart = "INSERT INTO $tableName (user_id, order_id, product_id, quantity, name, email, address, product_name, price, image, total_price) VALUES ('$user_id', '$order_id', '$product_id', '$quantity', '$username', '$email', '$address', '$product_name', '$price', '$image', '$total_price')";
    $conn->query($insertcart); // Execute the insert query

    // Insert data into the orders table
    $insertorders = "INSERT INTO orders (user_id, order_id, product_id, quantity, name, email, address, product_name, price, image, total_price, order_status) VALUES ('$user_id', '$order_id', '$product_id', '$quantity', '$username', '$email', '$address', '$product_name', '$price', '$image', '$total_price', 'cart')";
    $conn->query($insertorders); // Execute the insert query
    $message2 = "Cart added successfully";
    header("Location: ../homepage/mainpage.php?message2=" . urlencode($message2));
}
         
}

?>

