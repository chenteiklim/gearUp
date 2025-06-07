<style>
    html, body {
        background-color: white;
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .button, .navButton {
        background-color: #e8e8e8;
        width: 150px;
        color: black;
        cursor: pointer;
        padding: 10px 30px;
        font-size: 14px;
        border: none;
    }

   

    #navContainer {
        display: flex;
        align-items: center;
        background-color: #e8e8e8;
        width: 100%;
        height: 80px;
        position: relative;
    }

    #home {
        margin-left: 10px;
    }

    #name {
        margin-left: auto;
        margin-right: 10px;
    }

    #logout {
        height: 80px;
    }

    #logoImg {
        margin-top: 10px;
        width: 35px;
        height: 35px;
        border-radius: 5px;
        margin-left: 100px;
    }

    /* Dropdown styles */
    .dropdown-container {
        position: relative;
    }

    .dropdowns {
        display: none;
        position: absolute;
        top: 80px;
        left: 0;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        z-index: 1000;
        flex-direction: column;
    }

    .dropdowns button {
        background-color: white;
        color: black;
        padding: 10px 20px;
        border: none;
        text-align: left;
        cursor: pointer;
        width: 180px;
    }

    .dropdowns button:hover {
        background-color: #f1f1f1;
    }
</style>
<div id="navContainer"> 
    <img id="logoImg" src="/inti/gearUp/assets/logo.jpg" alt="">

    <button class="navButton" id="home">GearUp</button>
    <button id="sales" class="navButton">Sales</button>


    <!-- Dropdown wrapper -->
    <div class="dropdown-container">
        <button id="product" class="navButton" onclick="toggleDropdown()">Manage Product</button>
        <div class="dropdowns" id="dropdownMenu">
            <button onclick="location.href='../CRUDProduct/createProduct.php'">Create Product</button>
            <button onclick="location.href='../CRUDProduct/view/viewProduct.php'">View Product</button>
        </div>
    </div>
    <button class="navButton" id="customer">Customer Center</button>

    <button class="navButton" id="name"><?php echo $username ?></button>
    
    <form action="/inti/gearUp/Customer/login/logout.php" method="POST">
        <button class="navButton" type="submit" id="logout">Log Out</button>
    </form>
</div>

<?php //include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Seller/sellerNavbar.php';?>

<script>     
    document.getElementById("customer").addEventListener("click", function() {
        // Replace 'login.html' with the URL of your login page
        window.location.href = "/inti/gearUp/Customer/mainpage/customerMainpage.php";
    });
    
  var homeButton = document.getElementById("home");
  homeButton.addEventListener("click", function(event) {
    // Perform the navigation action here
    event.preventDefault()
    window.location.href = "/inti/gearUp/Seller/mainpage/sellerMainpage.php";
  });
    function toggleDropdown() {
        const dropdown = document.getElementById("dropdownMenu");
        dropdown.style.display = (dropdown.style.display === "flex") ? "none" : "flex";
    }

    // Optional: close dropdown if clicked outside
    window.onclick = function(e) {
        const button = document.getElementById("product");
        const dropdown = document.getElementById("dropdownMenu");
        if (!button.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = "none";
        }
    }
</script>