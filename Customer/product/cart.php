<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShopOld/db_connection.php';

session_start();
$username = $_SESSION['username'];

mysqli_select_db($conn, $dbname);

$stmt = $conn->prepare("SELECT email FROM users WHERE usernames = ?");
$stmt->bind_param("s",  $username);
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

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the user ID from the result
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
}
$tableName = "cart" . $user_id;



$maxIdQuery = "SELECT MAX(order_id) AS max_id FROM orders WHERE email= '$email'";
$maxIdResult = $conn->query($maxIdQuery);
$row= $maxIdResult->fetch_assoc();
if ($row['max_id'] !== null) {
    $order_id = $row['max_id'];
    $selectRowsQuery = "SELECT * FROM $tableName WHERE email='$email' ORDER BY id ASC";
    $selectRowsResult = $conn->query($selectRowsQuery);

    $rows = []; // Initialize an empty array to store the rows

    if ($selectRowsResult && $selectRowsResult->num_rows > 0) {
        while ($row = $selectRowsResult->fetch_assoc()) {
            $rows[] = $row; // Add each row to the array
        }
    }
    $product_ids = array(); // Initialize the array before the loop

    // Loop through the array of rows
    foreach ($rows as $row) {
        $product_id = $row['product_id'];
        $product_name = $row['product_name'];
        $name = $row['name'];
        $address = $row['address'];
        $price = $row['price'];
        $image = $row['image'];

    }
    $imageUrl = "/inti/gadgetShop/assets/" . $image;


    // Check if a product deletion request was made
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
        $product_id_to_delete = $_POST['product_id'];

        // Delete the record from the database
        $stmt = $conn->prepare("DELETE FROM $tableName WHERE product_id = ?");
        $stmt->bind_param("s", $product_id_to_delete);
        $stmt->execute();
        

        if ($stmt->execute()) {
            // Deletion successful
        } else {
            // Error occurred
            echo "Error: " . $stmt->error;
        }
        $stmt->close();


        $newOrderIdQuery = "SELECT MAX(order_id) AS max_id FROM orders WHERE email = '$email'";
        $newOrderIdResult = $conn->query($newOrderIdQuery);
        
        if ($newOrderIdResult && $newOrderIdResult->num_rows > 0) {
            $row = $newOrderIdResult->fetch_assoc();
            $new_order_id = $row['max_id'];
            // Delete the record from the orders table for the specific product
            $deleteOrderQuery = "DELETE FROM orders WHERE email = '$email' AND order_id = '$new_order_id' AND product_id = '$product_id_to_delete'";
            $conn->query($deleteOrderQuery);
            $_SESSION['order_id'] = $new_order_id;
            
        }        
    }
}

if (!empty($order_id)) {
    // Prepare the select statement
    $stmt = $conn->prepare("SELECT * FROM $tableName WHERE email=? ORDER BY id ASC");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $selectRowsResult = $stmt->get_result();

    $rows = []; // Initialize an empty array to store the rows

    if ($selectRowsResult && $selectRowsResult->num_rows > 0) {
        while ($row = $selectRowsResult->fetch_assoc()) {
            $rows[] = $row; // Add each row to the array
        }
    }

    // Prepare the count statement
    $countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM $tableName WHERE email=?");
    $countStmt->bind_param("s", $email);
    $countStmt->execute();
    $countResult = $countStmt->get_result();

    if ($countResult && $countResult->num_rows > 0) {
        $row6 = $countResult->fetch_assoc();
        $total_rows = $row6['total'];
    } 
}

if (empty($rows)) {
    $message = "Your cart is empty.";
    header("Location: ../homepage/mainpage.php?message2=" . urlencode($message));
    exit(); // Always exit after a header redirect
}


?>

<head>
    <link rel="stylesheet" href="cart.css">
</head>


<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Trust Toradora</button>
    <button class="button" id="cart" onclick="window.location.href = '../product/cart.php';"><?php echo 'Shopping Cart'; ?></button>
    <button class="button" id="tracking"><?php echo 'Tracking' ?></button>
    <button class="button" id="refund" type="submit" name="refund" value="">refund</button>
    <button class="button" id="name"><?php echo $username ?></button>
    <form action="../login/logout.php" method="POST">
      <button type="submit" id="logOut" class="button">Log Out</button>
    </form>    
</div>

  
<div id="container">
<div class='title'>
    <div class="Product"><?php echo 'Product'; ?> </div>
    <div class="product_name"><?php echo 'Product Name'; ?></div>
    <div class="price"><?php echo 'Price'; ?></div>
    <div class="quantity"><?php echo 'Quantity'; ?></div>
    <div class="total_price"><?php echo 'Total Price'; ?></div>
</div>

<?php
$grandTotal=0;
// Loop through the orders
foreach ($rows as $row) { 
        $product_id = $row['product_id']; 
        $product_name = $row['product_name'];
        $image=$row['image'];
        $imageUrl = "/inti/gadgetShop/assets/" . $image;
        $name = $row['name'];
        $address = $row['address'];
        $price = $row['price'];
        $quantity = $row['quantity'];
        $total_price=$row['total_price'];
        $grandTotal += $total_price;
        $product_ids[] = $product_id;
        $quantities[$product_id] = $quantity;
    
?>  
<div class="content" id="row_<?php echo $product_id; ?>">
    <img class="item" src="<?php echo $imageUrl; ?>" alt="">
    <div class="product_name"><?php echo $product_name; ?></div>
    <div id="price"><?php echo 'RM'.$price; ?></div>
    <div id="quantity">x<?php echo $quantity; ?></div>
    <div id="total_price"><?php echo 'RM'.$total_price; ?></div>

    <form action="cart.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
        <button class='button' type="submit">Delete</button>
    </form>
</div>


<?php
} 
?>
</div>
</div>

<?php
$_SESSION['quantities'] = $quantities;
$_SESSION['product_ids'] = $product_ids;
$product_ids_string = implode(", ", $product_ids);
$quantities_string = implode(", ", $quantities);
?>
    <div class='text'>
        <form id="checkOut" action="../order/checkOut.php" method="POST">
                <div class="total">
                    <div>
                        Total:
                    </div>
                    <div id="total_prices">
                    <?php echo "RM $grandTotal"?>
                       
                        <button id="checkOutBtn" class="button"><?php echo 'Check Out' ?></button>
                       
                </div>
        </form>  
    </div>
</div>
<script src="cart.js"></script>
