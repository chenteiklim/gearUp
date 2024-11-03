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
$order_id=$_SESSION['order_id'];
mysqli_select_db($conn, $dbname);
// Execute the first query to get usernames (ensure $selectNameQuery is defined properly)

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
$sql = "SELECT address FROM users WHERE email = '$email'";
$result = $conn->query($sql);

// Check if the query was successful and if any rows were returned
if ($result && $result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
    // Get the address value from the fetched row
    $address = $row['address'];
} 

$sql2 = "SELECT user_id FROM users WHERE email = '$email'";
$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
    // Fetch the user ID from the result
    $row = $result2->fetch_assoc();
    $user_id = $row['user_id'];
}
$tableName = "cart" . $user_id;

// Query to count the total number of rows in the table
$countQuery = "SELECT COUNT(*) AS total FROM $tableName WHERE email='$email' ORDER BY user_id ASC";
$countResult = $conn->query($countQuery);

if ($countResult && $countResult->num_rows > 0) {
    $row6 = $countResult->fetch_assoc();
    $total_rows = $row6['total'];
} else {
    $total_rows = 0;
}

$sql = "SELECT usernames FROM users WHERE email = '$email'";
$result3 = $conn->query($sql);

// Check if the query was successful and if any rows were returned
if ($result3 && $result3->num_rows > 0) {
    // Fetch the row from the result
    $row = $result3->fetch_assoc();
    // Get the address value from the fetched row
    $usernames = $row['usernames'];
} 

    


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="checkOut.css">
</head>
<body>
    
<div id="navContainer"> 
    <img id="logoImg" src="../../assets/logo.jpg" alt="" srcset="">
    <button class="button" id="home">Computer Shop</button>
    <button class="button" id="cart" onclick="window.location.href = '../product/cart.php';"><?php echo 'Shopping Cart'; ?></button>
    <button class="button" id="tracking"><?php echo 'Tracking' ?></button>
    <button class="button" id="refund" type="submit" name="refund" value="">refund</button>
    <button class="button" id="name"><?php echo $usernames ?></button>
    <form action="../userLogin/logout.php" method="POST">
      <button type="submit" id="logOut" class="button">Log Out</button>
    </form>    
</div>
  
</div>
<div id="container">
    <div class='item10'>
    <div class='user-info'>
        <div class='title2'>
            Delivery Address
        </div>
        <div class='content2'>
           
            <div class='address'>
                <?php echo $address;?>
            </div>   
        </div>
    </div>

<div class='title'>
    <div class="Product"><?php echo 'Product'; ?> </div>
    <div class="product_name"><?php echo 'Product Name'; ?></div>
    <div class="price"><?php echo 'Price'; ?></div>
    <div class="quantity"><?php echo 'Quantity'; ?></div>
    <div class="total_price"><?php echo 'Total Price'; ?></div>
</div>

<?php

mysqli_select_db($conn, $dbname);
$selectRowQuery1 = "SELECT * FROM $tableName WHERE email='$email' ORDER BY user_id ASC";
$selectResult = $conn->query($selectRowQuery1);

if ($selectResult && $selectResult->num_rows > 0) {
    $product_ids = array(); // Initialize an empty array

    while ($row2 = $selectResult->fetch_assoc()) {
        $product_ids[] = $row2['product_id']; // Add each product_id to the array
    }
}

$grandTotal = 0;
$total_rows = count($product_ids);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    
    // Delete the record from the database
    $sql = "DELETE FROM user_$user_id WHERE product_id = '$product_id'";
    $result = $conn->query($sql);
    
    // Redirect the user back to the current page after deletion
    header("Location: cart.php");
    exit;
}

$grandTotal=0;
// Loop through the orders

foreach ($product_ids as $product_id) {
    $selectRowQuery = "SELECT * FROM $tableName  WHERE product_id = $product_id AND email='$email' ORDER BY user_id ASC";
    $selectRowResult = $conn->query($selectRowQuery);

    if ($selectRowResult && $selectRowResult->num_rows > 0) {
        $row = $selectRowResult->fetch_assoc();
        $product_name = $row['product_name'];
        $name = $row['name'];
        $address = $row['address'];
        $price = $row['price'];
        $image = $row['image'];
        $imageUrl = "/inti/gadgetShop/assets/" . $image;
        $quantity = $row['quantity'];
        $total_price = $row['total_price'];
        $grandTotal += $total_price;

?>  
<div class="content">
    <img class="item" src="<?php echo $imageUrl; ?>" alt="">
    <div class="product_name"><?php echo $product_name; ?></div>
    <div id="price"><?php echo 'RM'.$price; ?></div>
    <div id="quantity">x<?php echo $quantity; ?></div>
    <div id="total_price"><?php echo 'RM'.$total_price; ?></div> 
</div>

<?php
$Total=$grandTotal+9;
    }
}
?>
 </div>
    <div class='text'>
        <form id="checkOut" action="payment.php" method="POST">
            <div id='merchandise' class="row">
                <div>
                    Merchandise Subtotal
                </div>
                <div class='row2'>
                    <?php echo "RM $grandTotal"?>
                </div>
            </div>
            <div class="row">
            <div>
                    Shipping total
                </div>
                <div class='row3'>
                    <?php echo "RM 9.00"?>
                </div>
            </div>
            <div class='row'>
                <div>
                    Total Payment
                </div>
                <div class='row4'>
                    <?php echo "RM $Total"?>
                </div>
            </div>
                <button id="checkOutbtn" class="button"><?php echo 'Place Order' ?></button>
            </form>  

</div>
</div>
</div>
</body>
<script src="checkOut.js"></script>

</html>

