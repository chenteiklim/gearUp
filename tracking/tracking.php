<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gadgetShop";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



session_start();
$email=$_SESSION['email'];

mysqli_select_db($conn, $dbname);
$sql = "SELECT address FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the user ID from the result
    $row = $result->fetch_assoc();
    $address = $row['address'];
}



mysqli_select_db($conn, $dbname);
$maxIdQuery = "SELECT MAX(order_id) AS max_id FROM orders WHERE email='$email'";
$maxIdResult = $conn->query($maxIdQuery);

if ($maxIdResult && $maxIdResult->num_rows > 0) {
    $row9 = $maxIdResult->fetch_assoc();
    $maxId = $row9['max_id'];
}

// Query to retrieve all rows in ascending order
$selectRowsQuery = "SELECT * FROM orders WHERE email='$email' ORDER BY order_id ASC";
$selectRowsResult = $conn->query($selectRowsQuery);

$rows = []; // Initialize an empty array to store the rows

if ($selectRowsResult && $selectRowsResult->num_rows > 0) {
    while ($row = $selectRowsResult->fetch_assoc()) {
        $rows[] = $row; // Add each row to the array
    }
}

// Loop through the array of rows
foreach ($rows as $row) {
    $product_id = $row['product_id'];
    $product_name = $row['product_name'];
    $name = $row['name'];
    $address = $row['address'];
    $price = $row['price'];
    $image = $row['image'];
    $quantity=$row['quantity'];
    $total_price=$row['total_price'];
}


// Query to count the total number of rows in the table
$countQuery = "SELECT COUNT(*) AS total FROM orders WHERE email='$email'";
$countResult = $conn->query($countQuery);

if ($countResult && $countResult->num_rows > 0) {
    $row6 = $countResult->fetch_assoc();
    $total_rows = $row6['total'];
} else {
    $total_rows = 0;
}

$selectNameQuery = "SELECT usernames FROM users WHERE email='$email'";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
}
    // Get the address value from the fetched row
    $usernames = $row['usernames'];

    if (isset($_POST['refund'])) {
        $product3_id = $_POST['refund'];
    
        // Use the $product2_id variable as needed
        // For example, you can store it in a session variable
        $_SESSION['product3_id'] = $product3_id;
        
        if (isset($_SESSION['product3_id'])) {
          // Product ID is saved in the session
          $product3_id = $_SESSION['product3_id'];
          
       echo '<script>window.location.href = "request.php";</script>';
       
      } 
      
      else {
          // Product ID is not saved in the session
          echo "Product ID not found in the session.";
      }
    
        exit;
    }    


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="tracking.css">
</head>
<body>
    

<div id="navContainer"> 
    <img id="logoImg" src="../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Computer Shop</button>
    <button class="button" id="cart" onclick="window.location.href = '../product/cart.php';"><?php echo 'Shopping Cart'; ?></button>
    <button class="button" id="tracking"><?php echo 'Tracking' ?></button>
    <button class="button" id="refund" type="submit" name="refund" value="">refund</button>
    <button class="button" id="name"><?php echo $usernames ?></button>
    <form action="../userLogin/logout.php" method="POST">
      <button type="submit" id="logOut" class="button">Log Out</button>
    </form>    
</div>
<div id="container">
    
<div id="bigTitle">Order Status</div>

<div class='title'>
    <div class="Order_id"><?php echo 'Order_id'; ?></div>
    <div class="email"><?php echo 'Email'; ?></div>
    <div class="Address"><?php echo 'Address'; ?> </div>
    <div class="Product"><?php echo 'Product'; ?> </div>
    <div class="product_name"><?php echo 'Product Name'; ?></div>
    <div class="Pricess"><?php echo 'Price'; ?></div>
    <div class="quantity"><?php echo 'Quantity'; ?></div>
    <div class="total_price"><?php echo 'Total Price'; ?></div>
    <div class="order_status"><?php echo 'Order Status'; ?></div>
    <div class="purchase_date"><?php echo 'Purchase date'; ?></div>
</div>

<?php

$selectNumberRows = "SELECT * FROM orders WHERE email='$email' AND order_status <> 'cart' ORDER BY order_id ASC";
$selectNumberResult = $conn->query($selectRowsQuery);

$rows = []; // Initialize an empty array to store the rows

if ($selectNumberResult && $selectNumberResult->num_rows > 0) {
    while ($row = $selectNumberResult->fetch_assoc()) {
        $rows[] = $row; // Add each row to the array
    }
}

// Get the total number of rows
$total_rows = count($rows);

$grandTotal=0;
// Loop through the orders
for ($order_id = 1; $order_id <= $maxId; $order_id++) {
    $selectRowQuery = "SELECT * FROM orders WHERE order_id = $order_id AND email='$email' AND order_status <> 'cart'";
    $selectRowResult = $conn->query($selectRowQuery);

    if ($selectRowResult && $selectRowResult->num_rows > 0) {
        // Display order details
        while ($row = $selectRowResult->fetch_assoc()) {
            $product_id = $row['product_id'];
            $product_name = $row['product_name'];
            $email=$row['email'];
            $date = $row['date'];
            $address = $row['address'];
            $price = $row['price'];
            $image = $row['image'];
            $imageUrl = "/inti/gadgetShop/assets/" . $image;
            $quantity = $row['quantity'];
            $order_status = $row['order_status'];
            $total_price = $row['total_price'];
            $grandTotal += $total_price;
            $button_id = $product_id;
            
        ?>
            <div class="content">
            <div id="order_id" class='itemContent'><?php echo $order_id;?></div>
            <div id="user_id" class='itemContent'><?php echo $email; ?></div>
            <div id="Address" class='itemContent'><?php echo $address;?></div>
            <img class="item" class='itemContent' src="<?php echo $imageUrl; ?>" alt="">
            <div class="product_name" class='itemContent'><?php echo $product_name; ?></div>
            <div id="price" class='itemContent'><?php echo 'RM'.$price; ?></div>
            <div id="quantity" class='itemContent'>x<?php echo $quantity; ?></div>
            <div id="total_price" class='itemContent'><?php echo 'RM'.$total_price; ?></div> 
            <div id="order_status" class='itemContent'><?php echo $order_status?></div> 
            <div id="order_date" class='itemContent'><?php echo $date?></div> 
            <form action="" method="post">
                <button id="refunds" class="button" type="submit" name="refund" value="<?php echo $button_id ?>">refund</button>
            </form>
            </div>
        
        <?php
        }
    }
}
?>

</div>

</div>

</div>

</body>
<script src="tracking.js"></script>
</html>