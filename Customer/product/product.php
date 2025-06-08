<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';

session_start();
$product_id = $_SESSION['product_id'];

$sql = "SELECT products.*, seller.storeName, seller.sellerName 
        FROM products 
        JOIN seller ON products.seller_id = seller.seller_id 
        WHERE products.product_id = '$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the user ID from the result
    $row = $result->fetch_assoc();
    $seller_id=$row['seller_id'];
    $product_name = $row['product_name'];
    $price = $row['price'];
    $image= $row['image'];
    $stock=$row['stock'];
}
else{

}
$imageUrl = "/inti/gearUp/assets/" . $image;
/* Fetch Ratings for Each Product Inside the Loop  */

$selectRatingsQuery = "SELECT rating FROM ratings WHERE product_id = ?";
$ratingStmt = $conn->prepare($selectRatingsQuery);
$ratingStmt->bind_param("i", $row['product_id']);
$ratingStmt->execute();
$ratingResult = $ratingStmt->get_result();
$ratingCount = $ratingResult->num_rows;

$averageRating = 0;
if ($ratingCount > 0) {
    $totalRating = 0;
    while ($ratingRow = $ratingResult->fetch_assoc()) {
        $totalRating += $ratingRow['rating'];
    }
    $averageRating = $totalRating / $ratingCount;
}
$ratingStmt->close();
          
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Homepage</title>

<link rel="stylesheet" href="product.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></link>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Customer/customerNavbar.php';?>

</head>
<body>


</style>
    <div id="container">
        <div id=imageContainer>
            <div>
                <img class="img" src="<?php echo $imageUrl; ?>" alt="">
            </div>
               <!-- Display Rating -->
            <div class="productRating">
                <?php
                if ($ratingCount > 0) {
                    // Display average rating in stars
                    for ($i = 1; $i <= 5; $i++) {
                        // Add 'selected' class if the rating is greater than or equal to the current star number
                        echo '<span class="star ' . ($i <= $averageRating ? 'selected' : '') . '">&#9733;</span>';
                    }
                    echo " ($ratingCount ratings)";
                } else {
                    echo "No ratings yet.";
                }
                ?>
            </div>
        </div>        

        <div id="rightSideText">
            <div class="names"><?php echo $product_name; ?> </div>
                   
            <div class="stock"><?php echo $stock . 'stock available'; ?></div>
           <div id="seller">
                <div id="storeName">
                    Store Name: <?= htmlspecialchars($row['storeName']) ?>
                </div>
                <div id="sellerName">
                    Seller Name: <?= htmlspecialchars($row['sellerName']) ?>
                </div>
                  
            </div>
            <div id="price" class="prices"><?php echo'RM'.$price; ?></div>
            <form action="addCart.php?product_id=1" method="post">
                <div class="quantity">
                    <label for="quantity" class="quantity_label">Quantity:</label>
                    <div id="messageContainer"></div>
                    <button id="increment">+</button>
                    <input type="number" id="quantity_input" name="quantity_input" min="1" value="1">
                    <button id="decrement">-</button>
                </div>

         

           
                <div class="buyBtn">
                    <div>
                        <input id="addCartButton" class="button" type="submit" name="addCart" value="Add To Cart">
                    </div>
                

                </div>
            </form>
            <div id="messageContainer2"></div>
            <div id="messageContainer3"></div>
        </div>
    </div>
</html>
<script src="product.js"></script>
