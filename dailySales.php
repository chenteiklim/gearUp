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


mysqli_select_db($conn, $dbname);
$sql = "SELECT address,contact FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the user ID from the result
    $row = $result->fetch_assoc();
    $address = $row['address'];
    $contact = $row['contact'];
}


$sql2 = "SELECT user_id FROM users";
$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
    // Fetch the user ID from the result
    $row = $result2->fetch_assoc();
    $user_id = $row['user_id'];
}
mysqli_select_db($conn, $dbname);
$maxIdQuery = "SELECT MAX(order_id) AS max_id FROM orders";
$maxIdResult = $conn->query($maxIdQuery);

if ($maxIdResult && $maxIdResult->num_rows > 0) {
    $row9 = $maxIdResult->fetch_assoc();
    $maxId = $row9['max_id'];
}

// Query to retrieve all rows in ascending order
$selectRowsQuery = "SELECT * FROM orders ORDER BY order_id ASC";
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
$countQuery = "SELECT COUNT(*) AS total FROM orders";
$countResult = $conn->query($countQuery);

if ($countResult && $countResult->num_rows > 0) {
    $row6 = $countResult->fetch_assoc();
    $total_rows = $row6['total'];
} else {
    $total_rows = 0;
}

$selectNameQuery = "SELECT name FROM users";
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
width:1500px;
background-color: #CDCDCD;
display: flex;
flex-direction:column;
height: 100%;

 
}

.item{
    margin-left:20px;
 width:100px;
 height:100px;
}

.title{
    margin-left:40px;

    display: grid;
    grid-template-columns: repeat(13, 1fr);
    width:1400px;
    grid-gap: 3px;
    margin-top: 50px;
    margin-bottom:40px;
    font-size:18px;
   
}
.total_price{
    text-align:center;
    color:red;
}
.content{
    margin-left:40px;

    width:1400px;
    font-size:16px;
    display: grid;
    grid-template-columns: repeat(13, 1fr);
    grid-gap: 3px;
    align-items:center;
    margin-bottom: 50px;
    

}

.Product{
    font-size:20px;
    text-align:center;
}

.product_name {
    text-align:center;
    color: black;
}

.price {
    text-align:center;
    color: red;
}

.quantity {
    text-align:center;
}

#prices{
    text-align:center;
    color:red;
}
#checkOut{
    background-color:white;
    display:flex;
    font-size: 20px;
    width:1500px;
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

    #query_date {
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
        font-size:12px;
        display:flex;
        background-color: bisque;
        align-items:center;
        height: 1500px;
    }
      
    
    #navContainer{
        width:1500px;
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
<form action="adminHomepage.php" method="POST">
    <button id="logOut" class="button"><?php echo 'Log Out' ?></button>
        <button type="submit" class="back-button">Home</button>
</form>  

  
</div>
<div id="container">
    <div class='item10'>
    <div class='user-info'>
        
        </div>
    </div>

<div class='title'>
    <div class="Order_id"><?php echo 'Order_id'; ?></div>
    <div class="User_id"><?php echo 'User_id'; ?></div>
    <div class="Name"><?php echo 'Name'; ?> </div>
    <div class="Contact"><?php echo 'Contact'; ?> </div>
    <div class="Address"><?php echo 'Address'; ?> </div>
    <div class="Product"><?php echo 'Product'; ?> </div>
    <div class="product_name"><?php echo 'Product Name'; ?></div>
    <div class="price"><?php echo 'Price'; ?></div>
    <div class="quantity"><?php echo 'Quantity'; ?></div>
    <div class="total_price"><?php echo 'Total Price'; ?></div>
    <div class="order_status"><?php echo 'Order Status'; ?></div>
    <div class="purchase_date"><?php echo 'Purchase date'; ?></div>
    <button id="query_date"><?php echo 'Date'; ?> </button>
</div>

<?php

$selectNumberRows = "SELECT * FROM orders ORDER BY order_id ASC";
$selectNumberResult = $conn->query($selectRowsQuery);

$rows = []; // Initialize an empty array to store the rows

if ($selectNumberResult && $selectNumberResult->num_rows > 0) {
    while ($row = $selectNumberResult->fetch_assoc()) {
        $rows[] = $row; // Add each row to the array
    }
}

// Get the total number of rows
$total_rows = count($rows);


$date = date('Y-m-d');

if (isset($_GET['date'])) {
    $date = $_GET['date']; // Update $date with the value from the URL parameter
}




$grandTotal=0;
// Loop through the orders
for ($order_id = 1; $order_id <= $maxId; $order_id++) {
    $selectRowQuery = "SELECT * FROM orders WHERE order_id = $order_id AND date='$date'";
    $selectRowResult = $conn->query($selectRowQuery);

    if ($selectRowResult && $selectRowResult->num_rows > 0) {
        // Display order details
        while ($row = $selectRowResult->fetch_assoc()) {
            $product_id = $row['product_id'];
            $product_name = $row['product_name'];
            $user_id=$row['user_id'];
            $name = $row['name'];
            $date = $row['date'];
            $address = $row['address'];
            $price = $row['price'];
            $image = $row['image'];
            $quantity = $row['quantity'];
            $order_status = $row['order_status'];
            $total_price = $row['total_price'];
            $grandTotal += $total_price;

            
        ?>
            <div class="content">
            <div id="order_id"><?php echo $order_id;?></div>
            <div id="user_id"><?php echo $user_id; ?></div>
            <div id="name"><?php echo $name;?></div>
            <div id="Contact"><?php echo $contact;?></div>
            <div id="Address"><?php echo $address;?></div>
            <img class="item" src="<?php echo $image; ?>" alt="">
            <div class="product_name"><?php echo $product_name; ?></div>
            <div id="price"><?php echo 'RM'.$price; ?></div>
            <div id="quantity">x<?php echo $quantity; ?></div>
            <div id="total_price"><?php echo 'RM'.$total_price; ?></div> 
            <div id="order_status"><?php echo $order_status?></div> 
            <div id="order_date"><?php echo $date?></div> 
            <form action="editOrder.php" method="POST">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                <input type="hidden" name="order_status" value="<?php echo $order_status; ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <button type="submit">Edit</button>
            </form>
            </div>
        
        <?php
        }
    }
}
?>

<?php



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
                <?php
               $shippingPrice = 9 * $total_rows;
                echo "RM" . $shippingPrice;
                ?>
               </div>
           </div>
<?php

    $Total=$grandTotal+$shippingPrice;
?>
           <div class='row'>
               <div>
                   Total Payment
               </div>
               <div class='row4'>
                   <?php echo "RM $Total"?>
               </div>
           </div>
          
           </form>  
   </div>
   <button onclick="toggleContent()">Toggle Content</button>
<script>
  function toggleContent() {
    var content = document.getElementById('checkOut');
    if (content.style.opacity === '0') {
      content.style.opacity = '1';
    } else {
      content.style.opacity = '0';
    }
  
    document.addEventListener('click', function(event) {
    var checkOut = document.getElementById('checkOut');
    var checkOutBtn = document.getElementById('checkOutbtn');
    if (event.target !== checkOut && event.target !== checkOutBtn && !checkOut.contains(event.target)) {
      checkOut.style.display = 'none';
    }
  });
  }

  
var query_date = document.getElementById("query_date");

query_date.addEventListener("click", function() {
  // Perform the navigation action here
  
  window.location.href = "date.php";
});
</script>
</div>

<div class='payment'>
     Payment Method
     <button id="checkOutbtn" class="button"><?php echo 'Online Banking' ?></button>
</div>
</div>
