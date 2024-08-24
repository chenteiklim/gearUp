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

$selectQuery = "SELECT * FROM orders WHERE email='$email'";
$result = $conn->query($selectQuery);

if ($result->num_rows > 0) {
    // Fetch the row from the result
    $row = $result->fetch_assoc();
}
    // Get the address value from the fetched row
    $order_status = $row['order_status'];

if ($order_status === 'refund') {
    // Perform actions if order_status is not equal to 'refund'

$product3_id=$_SESSION['product3_id'];
mysqli_select_db($conn, $dbname);

$selectQuery = "SELECT * FROM orders WHERE product_id = $product3_id AND email='$email'";
$result = $conn->query($selectQuery);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row['image'];
    $product_name=$row['product_name'];
    $price = $row['price'];
    $name = $row['name'];
    $email=$row['email'];
    $quantity=$row['quantity'];
    $price = $row['price'];
    $total_price=$row['total_price'];
} else {
    echo 'Image not found';
}


if (isset($_POST['request'])) {
$sql = "UPDATE orders SET order_status = 'refund' WHERE product_id = $product3_id AND email='$email'";
// Execute query
if ($conn->query($sql) === true) {

} 
}
} else {
    $message = "You have not requested a refund";
    // Append the message as a parameter to the URL
    header("Location: mainpage.php?message=" . urlencode($message));
    exit; // Terminate the script after the redirect
}
?>
<style>

body{
        display:flex;
        flex-direction:column;
        background-color: bisque;
        width: 1400px;
        height: 1400px;
    }

    #container{
        display:flex;
        flex-direction:column;
        align-items:center;
        margin-left:100px;
    }
#navContainer{
        width:1400px;
        background-color: black;
    }

    .button {
    background-color: black;
    color: white;
    cursor: pointer;
    margin-left: 20px;
    padding-left: 30px;
    padding-right: 30px;
    padding-top: 10px;
    padding-bottom: 10px;
    font-size: 12px;
    }
#back {
    background-color: black;
    color: white;
    cursor: pointer;
    padding-left: 30px;
    padding-right: 30px;
    padding-top: 10px;
    margin-top: 10px;
    padding-bottom: 10px;
    font-size: 12px;
    }
#receipt{
    margin-top:10px;
    display:flex;
    flex-direction:column;
    align-items:center;
    background-color:white;
    width:500px;
    height:700px;
}
.text{
    margin-top:20px;
    font-size:20px;
}

.text1{
    margin-top:20px;
    font-size:28px;
}

.image{
    margin-top:80px;
    width:300px;
    height:250px
}

#request{
    margin-top:80px;
    margin-right:40px;
}


</style>
<div id="navContainer"> 
    <button id="back" class="button"><?php echo 'Back' ?></button>

</div>
<div id="container">
    <div class="text1">You can claim your money from nearest pit stop store by bringing the product and this receipt</div>
    <div id="receipt">
        <div class="text1">Receipt</div>
        <div class="text"><?php echo "Name:" . $name; ?></div>
        <div class="text"><?php echo "Email:" . $email; ?></div>
        <img class="image" src="<?php echo $image;?>" alt="" srcset="">
        <div class="text"><?php echo "Product Name:" .$product_name; ?></div>
        <div class="text"><?php echo "RM" . $price; ?></div>
        <div class="text"><?php echo "Quantity:" . $quantity; ?></div>
        <div class="text"><?php echo "Total Price:" . $total_price; ?></div>


    </div>
</div>

<script>
var back = document.getElementById("back");

back.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "mainpage.php";
});
</script>