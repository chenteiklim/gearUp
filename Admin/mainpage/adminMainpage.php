
<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';

session_start();

// Check if the session variables are set
// If email or backupEmail is not set, display an error message and exit
if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;  // Stop further execution of the script
}
$username=$_SESSION['adminUsername'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/Admin/adminNavbar.php';

?>
<head>
    <title>Product</title>
    <link rel="stylesheet" href="adminMainpage.css">


</head>
<div>

</div>
  <div>
  <p id='title'>Admin Mainpage </p>
  </div>
  <div id="container">
    <div id="messageContainer"></div>

   
    <div class='product'>
        <button id="product" class="button"><?php echo 'Manage Product' ?></button>
        <div class="dropdowns">
            <button onclick="location.href='CRUDProduct/createProduct.php'">Create Product</button>
            <button onclick="location.href='CRUDProduct/view/viewProduct.php'">View Product</button>
        </div>
    </div>

    <div class="order">
        <button id="order" class="button"><?php echo 'Sales' ?></button>
    </div>
    <div>
    <button id="assignRidersButton" class="button">Assign Riders</button>
    </div>
    <div>
    <button id="approveRiderButton" class="button">Approve Riders</button>
    </div>
    <div class="refund">
        <button id="refund" class="button"><?php echo 'Refund' ?></button>
    </div>

 
</div>
  
</div>

</div>

<script >
   
    document.getElementById("order").addEventListener("click", function() 
    {
        window.location.href = "../sales/sales.php";
    });
    document.getElementById("assignRidersButton").addEventListener("click", function() {
        fetch("assign_riders.php")
            .then(response => response.text())
            .then(data => alert("Riders assigned: " + data))
            .catch(error => alert("Error: " + error));
    });  
    document.getElementById("refund").addEventListener("click", function() 
    {
        window.location.href = "../sales/refund.php";
    });
</script>

   