<div class="sidebar">
  <div id= 'logo'>
     <img id="logoImg" src="/inti/gearUp/assets/whiteLogo.jpg" alt="Logo" />
  <a href="/inti/gearUp/Admin/mainpage/adminMainpage.php">GearUp</a>
  </div>
 
 <a href="/inti/gearUp/Admin/profile.php"><?php echo htmlspecialchars($username); ?></a>

  <button class="dropdown-btn">Manage Product &#x25BC;</button>
  <div class="dropdown-container">
    <a href="/inti/gearUp/Admin/mainpage/CRUDProduct/createProduct.php">Create Product</a>
    <a href="/inti/gearUp/Admin/mainpage/CRUDProduct/view/viewProduct.php">View Product</a>
  </div>
  
 
    <a href="/inti/gearUp/Admin/CRUDUser/view/viewUser.php">View User</a>
  <a href="/inti/gearUp/Admin/sales/sales.php">Sales</a>
    <a href="/inti/gearUp/Admin/mainpage/orderDetail.php">Order Detail</a>
  <a href="/inti/gearUp/Admin/approve/approveSeller.php">Approve Seller</a>
  <a href="/inti/gearUp/Admin/sales/refund.php">Refund</a>
  <a href="/inti/gearUp/Admin/wallet/superuserWallet.php">Wallet</a>
  <a href="/inti/gearUp/Admin/chat.php">View Chat</a>
  <a href="/inti/gearUp/Admin/transaction.php">View Transaction</a>
  <a href="/inti/gearUp/Admin/login/logout.php" style="margin-top: 20px; color:#e74c3c;">Logout</a>
</div>

<style>
   html, body {
        background-color: #f0f0f0;
     
       }
.sidebar {
  position: fixed;
  top: 0px; /* height of navbar */
  left: 0;
  width: 250px;
  height: calc(100vh);
  background-color: #2c3e50;
  padding-top: 20px;
  overflow-y: auto;
  font-family: Arial, sans-serif;
}
#logo{
  display:flex;
  align-items:center;
}

  #logoImg {
    width: 30px;
    height: 30px;
    border-radius: 5px;
    margin-left:20px;
  }
   .button {
    background-color: rgb(33, 12, 102);
    width: 150px;
    color: white;
    cursor: pointer;
    padding: 10px 30px;
    font-size: 14px;
    border: none;
  }
.sidebar a, .sidebar button {
  padding: 12px 20px;
  text-decoration: none;
  font-size: 16px;
  color: white;
  display: block;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
}
.sidebar a:hover, .sidebar button:hover {
  background-color: #34495e;
}
.dropdown-container {
  display: none;
  background-color: #34495e;
}
.dropdown-container a {
  padding-left: 40px;
  font-size: 14px;
}
</style>

<script>
  document.querySelectorAll('.dropdown-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.classList.toggle('active');
      const dropdown = btn.nextElementSibling;
      if (dropdown.style.display === 'block') {
        dropdown.style.display = 'none';
        btn.innerHTML = 'Manage Product &#x25BC;';
      } else {
        dropdown.style.display = 'block';
        btn.innerHTML = 'Manage Product &#x25B2;';
      }
    });
  });
</script>

<?php //include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php';?>
