
<!DOCTYPE html>
<html>

<?php
$order_id = $_POST['order_id'];
$order_status = $_POST['order_status'];
$user_id = $_POST['user_id'];
?>

<head>
    <title>Update Order Status</title>
</head>
<body>
    <h1>Update Order Status</h1>
    <?php
    // Display the order_id here
    echo "<p>Order ID: $order_id</p>";
    echo "<p>User ID: $user_id</p>";
    ?>
    <form method="post" action="editOrderStatus.php">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <?php echo "<div>Old Status: $order_status<div>"; ?>
        <label for="new_status">New Status:</label>
        <input type="text" id="new_status" name="new_status" required><br><br>
        
        <input type="submit" value="Update">
    </form>
</body>
</html>






