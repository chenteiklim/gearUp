<?php
$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



session_start();
$product_ids = $_SESSION['product_ids'];
$order_id = $_SESSION['order_id'];



if (isset($_POST['submit'])) {
  $clickDate = date("Y-m-d");
  // Format the date in the desired format "Y-m-d"
  $formattedDate = date("Y-m-d", strtotime($clickDate));
  $email=$_POST['email'];
  $_SESSION['email'] = $email;
  $passwords = $_POST['password'];

  mysqli_select_db($conn, $dbname);
 



  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
 
  if ($result === false) {
    // Display SQL error message
    echo "SQL Error: " . $conn->error;
  }

  if ($result->num_rows > 0) {
    // email exists and password exists, proceed with the login
    $row = $result->fetch_assoc();
    $user_id = $row['user_id']; 
    $address = $row['address'];
    $email=$row['email'];
    $hashed_password = $row['passwords'];
    $tableName = "cart" . $user_id;

    if (!password_verify($passwords, $hashed_password)) {
      header("Location: maybankLogin.php");
      exit(); // Stop further script execution
    } 
    else{
    
      $sql_delete_cart = "DELETE FROM $tableName WHERE email = '$email'";
      $conn->query($sql_delete_cart);
      $sql = "UPDATE orders SET order_status = 'purchased', date = '$formattedDate' WHERE order_id = $order_id";
      // Execute query
        if ($conn->query($sql) === true) {
            echo "Row updated successfully.";
        } 
        else{
          echo"error update row";
        }
        $product_ids_str = implode(',', $product_ids);
        $quantities = $_SESSION['quantities'];
        $sql_select = "SELECT product_id, stock,status FROM products WHERE product_id IN ($product_ids_str)";

        // Execute the query
        $result = $conn->query($sql_select);

        // Check if the query was successful
        $statuses = array();
    
        // Fetch the rows from the result
        while ($row = $result->fetch_assoc()) {
          $product_id = $row['product_id'];
          $stock = $row['stock'];
          $status = $row['status'];
          $statuses[$product_id] = $status;
          $quantity = $quantities[$product_id];
          
          // Perform any operations with the retrieved stock value here
          // For example, you can update the stock and status values and then save them back to the database
          $updated_stock = $stock - $quantity;
          $updated_status = $status + $quantity; // Assuming $status is the current status value
          
          // Update the stock and status values in the database
          $sql_update = "UPDATE products SET stock = ?, status = ? WHERE product_id = ?";
          $stmt = $conn->prepare($sql_update);
          $stmt->bind_param('iii', $updated_stock, $updated_status, $product_id);
          $stmt->execute();
        }
    
        // Check if the execution was successful
        if ($stmt->affected_rows > 0) {
            $orders_id = $order_id + 1;
            $_SESSION['orders_id'] = $orders_id;
            echo $orders_id;
            header("Location: success.html");
        } 
        else{
          echo "error update row";
        } 
    }
  }
  else {
      // email doesn't exist, display an error message
      echo "Invalid email or password. Please try again.";
  } 
}
?>