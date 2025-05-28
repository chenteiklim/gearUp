<?php

session_start();

$username = $_SESSION['riderUsername'];

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/db_connection.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/encryption_helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/Rider/riderNavbar.php';

function decrypt_address($encrypted_address) {
    global $encryption_key, $encryption_iv;
    return openssl_decrypt($encrypted_address, 'aes-256-cbc', $encryption_key, 0, $encryption_iv);
}

$selectRiderIdSql = "SELECT * FROM rider WHERE username = '$username'";
$resultRiderId = $conn->query($selectRiderIdSql);
$row = $resultRiderId->fetch_assoc();
$rider_id = $row['rider_id'];

// Fetch orders
$sql = "SELECT * FROM orders WHERE assigned_rider = '$rider_id'";
$resultOrder = $conn->query($sql);




?>

<head>
    <title>Product</title>
    <link rel="stylesheet" href="order.css">
</head>

<div id="messageContainer"></div>
<table border="1">
    <tr>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Address</th>
        <th>Product Image</th>
        <th>Product Name</th>
        <th class="price-header">Price</th>
        <th>Quantity</th>
        <th>Total Price</th>
        <th class="date-header">Order Date</th>
        <th class="upload-header">Upload proof</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $resultOrder->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['order_id']) ?></td>
            <td><?= htmlspecialchars($row['usernames']) ?></td>
            <td><?= htmlspecialchars(decrypt_address($row['address'])) ?></td>
            <td>
                <?php if (!empty($row['image'])): ?>
                    <img id="img" src="/inti/gadgetShop/assets/<?= htmlspecialchars($row['image']) ?>" alt="Product Image" width="50" height="50">
                <?php else: ?>
                    No Image Available
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td  class="price-content">RM <?= htmlspecialchars($row['price']) ?></td>
            <td><?= htmlspecialchars($row['quantity']) ?></td>
            <td>RM <?= $row['price'] * $row['quantity'] ?></td>
            <td><?= htmlspecialchars($row['date']) ?></td>
            <td>
            <form method="POST" action="upload_proof.php" enctype="multipart/form-data" onsubmit="return confirmUpload(this)">
                <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['order_id']) ?>">
                <input type="file" name="proof_image" accept="image/*" required>
                <button class="upload-button <?= (!empty($row['sent_proof_image'])) ? 'disabled-button' : '' ?>" 
                    type="submit" <?= (!empty($row['sent_proof_image'])) ? 'disabled' : '' ?>>
                    <?= (!empty($row['sent_proof_image'])) ? 'Proof Uploaded' : 'Upload Proof' ?>
                </button>
            </form>
            </td>
            <td>
                <form method="POST" action="sent.php" onsubmit="return confirmSend(<?= htmlspecialchars($row['order_id']) ?>)">
                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['order_id']) ?>">
                    <button class="button sent-button <?= ($row['order_status'] == 'sent') ? 'disabled-button' : '' ?>" 
                        type="submit" <?= ($row['order_status'] == 'sent') ? 'disabled' : '' ?>>
                        Sent
                    </button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
<script src="order.js"></script>