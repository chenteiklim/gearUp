<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['username'])) {
    echo "<h1>You are not authorized to access this page</h1><h2>Please register or login.</h2>";
    exit();
}

$username = $_SESSION['username'];

// Fetch user details
$checkLogin = $conn->prepare("SELECT * FROM users WHERE usernames = ?");
$checkLogin->bind_param("s", $username);
$checkLogin->execute();
$result = $checkLogin->get_result();

if (!$result || $result->num_rows === 0) {
    echo "<h1>User not found</h1>";
    exit();
}

$row = $result->fetch_assoc();
$role = $row['role'];
$user_id = $row['user_id'];

if ($role !== 'seller') {
    $message4 = "Login failed, please submit seller application form";
    header("Location: ../../Customer/mainpage/customerMainpage.php?message4=" . urlencode($message4));
    exit();
}

// Get seller status
$sellerQuery = "SELECT status FROM seller WHERE user_id = ?";
$stmt2 = $conn->prepare($sellerQuery);
$stmt2->bind_param("s", $user_id);
$stmt2->execute();
$sellerResult = $stmt2->get_result();

if (!$sellerResult || $sellerResult->num_rows === 0) {
    $message = "Login failed. Seller account not found.";
    header("Location: ../../Customer/mainpage/customerMainpage.php?message=" . urlencode($message));
    exit();
}

$sellerRow = $sellerResult->fetch_assoc();
$status = $sellerRow['status'];
$isPendingSeller  = ($status === 'pending');
$isRejectedSeller = ($status === 'rejected');
$isApprovedSeller = ($status === 'approved');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard Preview</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .banner {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        .section-title {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }
        .dashboard-preview {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .card {
            flex: 1;
            background: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .card h3 {
            margin: 0;
            font-size: 18px;
        }
        .card p {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }
        .disabled-btn {
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: not-allowed;
            opacity: 0.6;
        }
        .tips {
            margin-top: 40px;
        }
        .tips ul {
            padding-left: 20px;
        }
        .faq a {
            display: block;
            margin-top: 8px;
            color: #007bff;
            text-decoration: none;
        }
        .faq a:hover {
            text-decoration: underline;
        }
        .go-mainpage-btn {
    padding: 12px 25px;
    background: #28a745;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.3s;
}
.go-mainpage-btn:hover {
    background: #218838;
}
 
 #navContainer {
   display: flex;
   align-items: center;
   background-color: #E6E6FA;

   width: 100%; /* Adjust width as needed */
   height: 80px; /* Adjust height as needed */   
 }
#logoName {
  background-color: #e8e8e8;
  padding-left: 30px;
  padding-right: 30px;
  padding-top: 10px;
  padding-bottom: 10px;
  font-size: 14px;
  border: none;
  }

#logoImg{
  margin-left: 100px;
  width: 50px;
  height: 50px;
}
   .navButton {
          letter-spacing: 1.0px;
        background-color:#E6E6FA;
        width: 150px;
        color: black;
        cursor: pointer;
        padding-left: 30px;
        padding-right: 30px;
        padding-top: 10px;
        padding-bottom: 10px;
        font-size: 16px;
        border: none;
}
#logout{
    margin-left:1000px;
}

    </style>
</head>
<body>
<div id="navContainer"> 
  <img id="logoImg" src="/inti/gearUp/assets/logo.jpg" alt="" srcset="">
  <button class="navButton" id="home">GearUp</button>
      <form action="/inti/gearUp/Seller/login/logout.php" method="POST">
        <button class="navButton" type="submit" id="logout">Log Out</button>
    </form>

</div>

<?php if ($isPendingSeller): ?>
  <div class="banner">
    📢 Hi <?= htmlspecialchars($username) ?>! Your seller application is pending.
    Here’s what your dashboard will look like once approved.
  </div>

<?php elseif ($isRejectedSeller): ?>
  <div class="banner" style="background-color: #f8d7da; color: #721c24;">
    ❌ Hi <?= htmlspecialchars($username) ?>, your seller application was rejected.
    <br>
  </div>

<?php elseif ($isApprovedSeller): ?>
  <div style="text-align: center; margin-top: 30px;">
    <p style="font-weight: bold; color: #28a745;">
      🎉 Your seller account has been approved!
    </p>
    <a href="../mainpage/sellerMainpage.php" class="go-mainpage-btn">
      🚀 Go to Your Full Dashboard
    </a>
  </div>
<?php endif; ?>

<div class="container">
    <div class="section-title">📊 Dashboard Preview</div>
    <div class="dashboard-preview">
        <div class="card">
            <h3>Total Sales</h3>
            <p>RM 0.00</p>
        </div>
        <div class="card">
            <h3>Top 5 best selling product</h3>
            <p>Quantity sold: 0</p>
        </div>
        <div class="card">
            <h3>Advance Sales Analytic</h3>
        </div>
    </div>

    <div class="section-title" style="margin-top: 30px;">⚙️ Tools (Disabled)</div>
    <button class="disabled-btn" disabled>Manage Product</button>
    <button class="disabled-btn" disabled>Manage Product</button>
    <button class="disabled-btn" disabled>Order Detail</button>
    <button class="disabled-btn" disabled>Wallet</button>

    <div class="tips">
        <div class="section-title">💡 Tips to Become a Successful Seller</div>
        <ul>
            <li>Upload clear photos of your products</li>
            <li>Provide detailed and honest descriptions</li>
            <li>Respond to customers promptly</li>
            <li>Ship items quickly and reliably</li>
        </ul>
    </div>

    <div class="faq">
        <div class="section-title">❓ Frequently Asked Questions</div>
        <a href="#">How long does approval take?</a>
        <a href="#">Why was my seller application rejected?</a>
        <a href="#">Can I edit my application after submitting?</a>
        <a href="#">How do I contact support?</a>
    </div>
</div>

</body>
</html>