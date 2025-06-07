<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>This Website is Not Accessible</h1>";
    echo "<p>Sorry, but you do not have permission to access this page. Please ensure you are logged in and have registered your email.</p>";
    exit;
}

$username = $_SESSION['adminUsername'];

// Fetch sellers with user info
$sql = "SELECT seller.*, users.usernames, users.email
        FROM seller
        JOIN users ON seller.user_id = users.user_id
        WHERE users.role != 'admin'";
$resultSeller = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approve Sellers</title>
    <link rel="stylesheet" href="approveSeller.css">
</head>
<body>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php'; ?>

<div id='container'>

    <h2>Pending Sellers</h2>

    <?php
    // Show message if exists
    if (isset($_GET['message'])) {
        echo "<p style='color: green;'>" . htmlspecialchars($_GET['message']) . "</p>";
    }

    if ($resultSeller->num_rows > 0) {
       echo "<table border='1'>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Store Name</th>
            <th>Contact Number</th>
            <th>Status</th> 
            <th>Action</th>
        </tr>";

while ($row = $resultSeller->fetch_assoc()) {
    $status = $row['status']; // pending, approved, rejected

    // Human readable status label
    $statusLabel = ucfirst($status);

    echo "<tr>
            <td>" . htmlspecialchars($row['usernames']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            <td>" . htmlspecialchars($row['storeName']) . "</td>
            <td>" . htmlspecialchars($row['contact']) . "</td>
            <td><strong>" . $statusLabel . "</strong></td>  <!-- Show status here -->
            <td>";

    if ($status === 'approved') {
        echo "<button class='button approved' disabled>Approved</button>";
        echo "<form method='POST' action='rejectSellerAction.php' style='display:inline; margin-left:5px;' onsubmit=\"return confirm('Are you sure you want to reject this seller?');\">
                <input type='hidden' name='seller_id' value='" . htmlspecialchars($row['seller_id']) . "'>
                <button class='button reject'>Reject</button>
              </form>";
    } elseif ($status === 'rejected') {
        echo "<button class='button rejected' disabled>Rejected</button>";
        echo "<form method='POST' action='approveSellerAction.php' style='display:inline; margin-left:5px;' onsubmit=\"return confirm('Are you sure you want to approve this seller?');\">
                <input type='hidden' name='seller_id' value='" . htmlspecialchars($row['seller_id']) . "'>
                <button class='button'>Approve</button>
              </form>";
    } else {
        // pending
        echo "<form method='POST' action='approveSellerAction.php' style='display:inline;' onsubmit=\"return confirm('Are you sure you want to approve this seller?');\">
                <input type='hidden' name='seller_id' value='" . htmlspecialchars($row['seller_id']) . "'>
                <button class='button'>Approve</button>
              </form>";
        echo "<form method='POST' action='rejectSellerAction.php' style='display:inline; margin-left:5px;' onsubmit=\"return confirm('Are you sure you want to reject this seller?');\">
                <input type='hidden' name='seller_id' value='" . htmlspecialchars($row['seller_id']) . "'>
                <button class='button reject'>Reject</button>
              </form>";
    }

    echo "</td></tr>";
}
        echo "</table>";
    } else {
        echo "<p>No sellers found.</p>";
    }

    $conn->close();
    ?>
</div>

</body>
</html>