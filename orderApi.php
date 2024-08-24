<?php
$servername = "localhost";
$Username = "root";
$Password = "";
$dbname = "gadgetShop";

$conn = new mysqli($servername, $Username, $Password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
mysqli_select_db($conn, $dbname); 
if ($conn){
    $sql = "SELECT order_id FROM orders";
    $result=mysqli_query($conn,$sql);
    if($result){
        header("Content-Type:JSON");
        $response=array();
        while($row=mysqli_fetch_assoc($result)){
            $order_id=$row['order_id'];
            $sql = "SELECT * FROM orders WHERE order_id = $order_id";
            $productResult = mysqli_query($conn, $sql);

            if ($productResult){
                $productRow=mysqli_fetch_assoc($productResult);
                $response[]=array(
                    'name'=>$productRow['name'],
                    'address'=> $productRow['address'],
                    'email'=>$productRow['email'],
                    'order_id' => $productRow['order_id'],
                    'product_name' => $productRow['product_name'],
                    'price' => $productRow['price'],
                    'image' => $productRow['image'],
                    'quantity' => $productRow['quantity'],
                );
            }
        }
        echo json_encode($response,JSON_PRETTY_PRINT);
    }
}
?>