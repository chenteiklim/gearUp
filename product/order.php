<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
mysqli_select_db($conn, $dbname);
$updateQuery = "";
$updateQuery3 = "";
$updateResult="";

if (isset($_POST['addCart'])) {
    session_start();
    $email = $_SESSION['email'];
    $product_id = $_SESSION['product_id'];

    if (isset($_SESSION['orders_id'])) {
        $order_id=$_SESSION['orders_id'];
        echo $order_id;
    }
    else if (isset($_SESSION['order_id'])) {
        $order_id=$_SESSION['order_id'];
        echo $order_id;
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
            else{
                echo 'order_id not 0';
            }

            // Use the $maxOrderId as needed
            echo "Max Order ID: " . $order_id;
        }
    } 
    
    $selectUserQuery = "SELECT * FROM users WHERE email = '$email'";
    // Execute the query
    $result = $conn->query($selectUserQuery);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $address = $row['address'];
        $contact= $row['contact'];
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
        $email=$_SESSION['email'];
        $total_price=$quantity*$price;
    }
    $existingQuantity = 0; // Initialize the variable

    $tableName = "cart" . $order_id . "_" . $user_id;
    $query = "SHOW TABLES LIKE '$tableName'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
   
    // Check if the product already exists in the cart
    $checkQuery2 = "SELECT quantity FROM cart" . $order_id . "_" . $user_id . " WHERE product_id = $product_id";
    $checkResult2 = $conn->query($checkQuery2);

    if ($checkResult2 && $checkResult2->num_rows > 0) {
        $row3 = $checkResult2->fetch_assoc();
        $existingQuantity = $row3['quantity'];
    }

    if ($existingQuantity > 0) {
        // Calculate the new quantity by adding the existing quantity to the selected quantity
        $newQuantity = $existingQuantity + $quantity;
        // Update the quantity in the existing cart record
        $newTotalPrice = $newQuantity * $price;
        // Update the quantity and total price in the existing cart record
        $updateQuery = "UPDATE cart" . $order_id . "_" . $user_id . " SET quantity = $newQuantity, total_price = $newTotalPrice WHERE product_id = $product_id";
        $updateQuery3 = "UPDATE orders SET quantity = $newQuantity, total_price = $newTotalPrice WHERE product_id = $product_id";
        $updateResult = $conn->query($updateQuery);
        $conn->query($updateQuery3);
    } 
    }
    else if ($existingQuantity == 0) {
        $tableName = "cart" . $order_id . $user_id;
        $query = "SHOW TABLES LIKE '$tableName'";
        $result = $conn->query($query);

        // Create the cart$order_id table if it doesn't exist
        $createTableQuery = "CREATE TABLE IF NOT EXISTS cart" . $order_id . "_" . $user_id . " (
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
        total_price DECIMAL(10, 2),
        contact VARCHAR(255)
    )";
    $conn->query($createTableQuery);
    }
    if ($updateResult !== true){
        $insertcart = "INSERT INTO cart" . $order_id . "_" . $user_id . " (user_id,order_id,product_id,quantity,name,email,address,product_name,price,image,total_price,contact) VALUES ('$user_id','$order_id','$product_id','$quantity','$name','$email','$address','$product_name','$price','$image','$total_price','$contact')";
        $insertorders = "INSERT INTO orders (user_id,order_id,product_id,quantity,name,email,address,product_name,price,image,total_price,contact,order_status) VALUES ('$user_id','$order_id','$product_id','$quantity','$name','$email','$address','$product_name','$price','$image','$total_price','$contact','cart')";

        $_SESSION['order_id']=$order_id;
        if ( $conn->query($insertcart) && $conn->query($insertorders)) {
            $successMessage = "Added to cart successfully!";
            header("Location: product.php?message=" . urlencode($successMessage)); 
        }
        else{
            echo'error update order';
        }
    }
    else{
        $successMessage = "Added to cart successfully!";
         header("Location: product.php?message=" . urlencode($successMessage)); 
     }
     exit();
    
}
  
    





?>

