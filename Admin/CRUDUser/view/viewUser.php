<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>Access Denied</h1><p>Please login to access this page.</p>";
    exit;
}

$username = $_SESSION['adminUsername'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/encryption_helper.php';

function decrypt_address($encrypted_address) {
    global $encryption_key, $encryption_iv;
    return openssl_decrypt($encrypted_address, 'aes-256-cbc', $encryption_key, 0, $encryption_iv);
}

// Get filters
$search = $_GET['search'] ?? '';
$roleFilter = $_GET['role'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$query = "
    SELECT 
        u.user_id, u.usernames, u.email, u.address, u.role, u.created_at,
        u.status AS user_status, u.delete_request,
        s.sellerName, s.storeName, s.description, s.contact,
        s.status AS seller_status
    FROM users u
    LEFT JOIN seller s ON u.user_id = s.user_id
    WHERE u.role != 'admin'
";

// Apply filters
$conditions = [];
$params = [];
$types = '';

if (!empty($search)) {
    $conditions[] = "(u.usernames LIKE ? OR u.email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= 'ss';
}

if (!empty($roleFilter)) {
    $conditions[] = "u.role = ?";
    $params[] = $roleFilter;
    $types .= 's';
}

if (!empty($statusFilter)) {
    $conditions[] = "u.status = ?";
    $params[] = $statusFilter;
    $types .= 's';
}

if ($conditions) {
    $query .= " AND " . implode(" AND ", $conditions);
}

$stmt = $conn->prepare($query);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Users</title>
    <style>
        body { font-family: Arial, sans-serif; }
        #container { margin-left: 350px; margin-top: 50px; }
        .item-container {
            background-color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            width: 420px;
            font-size: 16px;
        }
        #userContainer {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
            gap: 20px;
            margin-left: 30px;
        }
        .row { margin: 5px 0; }
        form button, .filter-form select, .filter-form input[type="text"] {
            margin-top: 8px;
            padding: 6px 10px;
            font-size: 15px;
        }
        #edit { background-color: #007bff; color: white; border: none; }
        #delete { background-color: rgb(179, 40, 16); color: white; border: none; }
        #view { background-color: rgb(40, 165, 73); color: white; border: none; }
        .filter-form { margin-left: 30px; margin-bottom: 20px; }
        #toggleSeller { margin-top: 5px; cursor: pointer; color: #007bff; text-decoration: underline; background: none; border: none; }
    </style>
    <script>
        function toggleSellerDetails(id) {
            const el = document.getElementById('sellerDetails-' + id);
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</head>
<body>

<div id="container"> 
    <h1>Manage Users</h1>

    <!-- Filters -->
    <form class="filter-form" method="GET">
        <input type="text" name="search" placeholder="Search username or email" value="<?= htmlspecialchars($search) ?>">
        <select name="role">
            <option value="">All Roles</option>
            <option value="customer" <?= $roleFilter === 'customer' ? 'selected' : '' ?>>Customer</option>
            <option value="seller" <?= $roleFilter === 'seller' ? 'selected' : '' ?>>Seller</option>
        </select>
        <select name="status">
            <option value="">All Statuses</option>
            <option value="registered" <?= $statusFilter === 'registered' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
        <button type="submit">Filter</button>
    </form>

    <!-- User List -->
    <div id="userContainer">
        <?php foreach ($users as $user): ?>
            <div class="item-container">
                <div class='row'><strong>Username:</strong> <?= htmlspecialchars($user['usernames']) ?></div>
                <div class='row'><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></div>
                <div class='row'><strong>Status:</strong> <?= htmlspecialchars($user['user_status']) ?></div>
                <div class='row'><strong>Created:</strong> <?= htmlspecialchars($user['created_at']) ?></div>
                <div class='row'><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></div>
                <div class='row'><strong>Address:</strong> <?= htmlspecialchars(decrypt_address($user['address'])) ?></div>

                <?php if ($user['role'] === 'seller'): ?>
                    <button id="toggleSeller" onclick="toggleSellerDetails(<?= $user['user_id'] ?>)">Toggle Seller Info</button>
                    <div id="sellerDetails-<?= $user['user_id'] ?>" style="display:none; margin-top:10px;">
                        <div class='row'><strong>Seller Name:</strong> <?= htmlspecialchars($user['sellerName']) ?></div>
                        <div class='row'><strong>Store Name:</strong> <?= htmlspecialchars($user['storeName']) ?></div>
                        <div class='row'><strong>Description:</strong> <?= htmlspecialchars($user['description']) ?></div>
                        <div class='row'><strong>Contact:</strong> <?= htmlspecialchars($user['contact']) ?></div>
                        <div class='row'><strong>Seller Status:</strong> <?= htmlspecialchars($user['seller_status']) ?></div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="editUser.php">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                    <button id="edit" type="submit">Edit</button>
                </form>

                <?php if ($user['delete_request'] == 1): ?>
                    <div style="color: red; font-weight: bold;">Requested Deletion</div>
                    <form method="POST" action="deleteUser.php" onsubmit="return confirm('Deactivate this user?');">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                        <button id="delete" type="submit" name="del">Deactivate</button>
                    </form>
                <?php endif; ?>

                <?php if ($user['user_status'] === 'inactive'): ?>
                    <form method="POST" action="activateUser.php" onsubmit="return confirm('Reactivate this user?');">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                        <button style="background-color:green;color:white;">Activate</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>