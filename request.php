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
$product3_id=$_SESSION['product3_id'];
mysqli_select_db($conn, $dbname);

$selectQuery = "SELECT * FROM products WHERE product_id = $product3_id";
$result = $conn->query($selectQuery);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $image = $row['image'];
    $product_name=$row['product_name'];
    $price = $row['price'];
} else {
    echo 'Image not found';
}


if (isset($_POST['request'])) {
$sql = "UPDATE orders SET order_status = 'refund' WHERE product_id = $product3_id AND email='$email'";
// Execute query
if ($conn->query($sql) === true) {
    header("Location: receipt.php");
} 
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

.text{
    margin-top:20px;
    font-size:28px;
}

.text2{
    margin-top:20px;
    font-size:20px;
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
    <div class="text">Sorry for your inconvenience. </div>
    <div class="text2">Note: refund can only be made within 3 month</div>
    <img class="image" src="<?php echo $image;?>" alt="" srcset="">
    <div class="text2"><?php echo $product_name; ?></div>
    <div class="text2"><?php echo "RM" . $price; ?></div>
    <form action="request.php" method="post">
        <button id="request" class="button" type="submit" name="request">request refund</button>
    </form>
</div>

<script>
var back = document.getElementById("back");

back.addEventListener("click", function() {
  // Perform the navigation action here
  window.location.href = "refund.php";
});
</script>