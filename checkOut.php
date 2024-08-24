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
$email = $_SESSION['email'];
$order_id=$_SESSION['order_id'];
$selectNameQuery = "SELECT name FROM users WHERE email = '$email'";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
}
// Get the address value from the fetched row
$name = $row['name'];

mysqli_select_db($conn, $dbname);
$sql = "SELECT address,contact FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the user ID from the result
    $row = $result->fetch_assoc();
    $address = $row['address'];
    $contact = $row['contact'];
}


$sql2 = "SELECT user_id FROM users WHERE email = '$email'";
$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
    // Fetch the user ID from the result
    $row = $result2->fetch_assoc();
    $user_id = $row['user_id'];
}

// Query to count the total number of rows in the table
$countQuery = "SELECT COUNT(*) AS total FROM cart" . $order_id . "_" . $user_id . "  WHERE email='$email' ORDER BY user_id ASC";
$countResult = $conn->query($countQuery);

if ($countResult && $countResult->num_rows > 0) {
    $row6 = $countResult->fetch_assoc();
    $total_rows = $row6['total'];
} else {
    $total_rows = 0;
}

$selectNameQuery = "SELECT name FROM users WHERE email = '$email'";
// Execute the query
$result = $conn->query($selectNameQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
}
    // Get the address value from the fetched row
    $name = $row['name'];

    


?>

<head>
    <style>

body{
    display: flex;
    flex-direction:column;

}

#container {

width:1200px;
background-color: #CDCDCD;
display: flex;
flex-direction:column;
height: 100%;

 
}

.item{
 width:100px;
 height:100px;
}

.title{
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    width:800px;
    grid-gap: 10px;
    margin-top: 50px;
    margin-bottom:40px;
    font-size:20px;
   
}
.total_price{
    text-align:center;
    color:red;
}
.content{
    width:800px;
    font-size:20px;
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    grid-gap: 10px;
    align-items:center;
    margin-bottom: 50px;
    

}

.Product{
    font-size:20px;
    text-align:center;
}

.product_name {
    text-align:center;
    font-size:20px;
    color: black;
}

.price {
    text-align:center;
    font-size: 20px;
    color: red;
}

.quantity {
    text-align:center;
    font-size: 20px;
}

#prices{
    text-align:center;
    font-size:30px;
    color:red;
}
#checkOut{
    background-color:white;
    display:flex;
    font-size: 20px;
    width:1200px;
    margin-top:480px;
    height:400px;
    position: fixed;
}


#total_item{
    padding-left:10px;
}
#price{
    display:flex;
    align-items:center;
    justify-content:center;
    
}

#total_price{
    text-align:center;
}

#quantity{
    text-align:center;
}


button {
    background-color: black;
    color: white;
    cursor: pointer;
    margin-left: 20px;
    padding-left: 30px;
    padding-right: 30px;
    padding-top: 10px;
    padding-bottom: 10px;
    font-size: 16px;
    }
    
    button:active {
      transform: scale(0.9);
      background: radial-gradient( circle farthest-corner at 10% 20%,  rgba(255,94,247,1) 17.8%, rgba(2,245,255,1) 100.2% );
    }
    
    body{
        display:flex;
        align-items:center;
        background-color: bisque;
        width: 1400px;
        height: 1400px;
    }
      
    
    #navContainer{
        width:1200px;
        background-color: black;
    }
    
    #logOut{
        margin-left: 200px;
    }

    .total{
        margin-left:800px;
    }

    .user-info{
        display:flex;
        flex-direction:column;
    }
    .title2{
        margin-top:20px;
        font-size:22px;
        color:red;
        margin-right:500px;
    }

    .content2{
        margin-top:10px;
        font-size:18px;
        margin-right:200px;
        display:flex;
    }

    .address{
        margin-right:20px;
    }

    .item10{
        margin-left:100px
    }

    .row{
        margin-top:10px;
        display:flex;
    }

    .row2{
        margin-left:20px;
    }
    .row3{
        margin-left:82px;
    }
    .row4{
        margin-left:78px;
    }
    .text{
        margin-top:30px;
        margin-left:600px;
    }
    #checkOutbtn{
        margin-top:20px;
        margin-left:50px;
    }

    .payment{
        margin-left:100px;
    }
    </style>
</head>


<div id="navContainer"> 
<form action="mainpage.php" method="POST">
    <!-- Your form fields here -->
    <button class="button"><?php echo 'Shopping Cart'; ?></button>
    <button class="button"><?php echo 'Notification' ?></button>
    <button class="button"><?php echo $name;?></button>
    <button id="logOut" class="button"><?php echo 'Log Out' ?></button>
        <button type="submit" class="back-button">Home</button>
</form>  

  
</div>
<div id="container">
    <div class='item10'>
    <div class='user-info'>
        <div class='title2'>
            Delivery Address
        </div>
        <div class='content2'>
            <div class='address'>
                <?php echo "order_id:" . $order_id; ?>
            </div>
            <div class='address'>
                <?php echo $contact;?>
            </div>
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
$selectRowQuery1 = "SELECT * FROM cart" . $order_id . "_" . $user_id . "  WHERE email='$email' ORDER BY user_id ASC";
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
    $selectRowQuery = "SELECT * FROM cart" . $order_id . "_" . $user_id . "  WHERE product_id = $product_id AND email='$email' ORDER BY user_id ASC";
    $selectRowResult = $conn->query($selectRowQuery);

    if ($selectRowResult && $selectRowResult->num_rows > 0) {
        $row = $selectRowResult->fetch_assoc();
        $product_name = $row['product_name'];
        $name = $row['name'];
        $address = $row['address'];
        $price = $row['price'];
        $image = $row['image'];
        $quantity = $row['quantity'];
        $total_price = $row['total_price'];
        $grandTotal += $total_price;

?>  
<div class="content">
    <img class="item" src="<?php echo $image; ?>" alt="">
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
 <div id="checkOut">
    <div class='text'>
        <form action="payment.php" method="POST">
            <div class="row">
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
                    <?php echo "RM9.00"?>
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
<div class='payment'>
     Payment Method
     <button id="checkOutbtn" class="button"><?php echo 'Online Banking' ?></button>
</div>
</div>
