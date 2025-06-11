<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['adminUsername'])) {
    echo "<h1>Access Denied</h1>";
    echo "<p>Please login to access this page.</p>";
    exit;
}

$username = $_SESSION['adminUsername'];
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/Admin/adminSidebar.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/encryption_helper.php';

function decrypt_address($encrypted_address) {
    global $encryption_key, $encryption_iv;
    return openssl_decrypt($encrypted_address, 'aes-256-cbc', $encryption_key, 0, $encryption_iv);
}
// Fetch all users from users table
$selectUserQuery = "
    SELECT 
        user_id,
        usernames,
        email,
        address,
        role
    FROM users WHERE role != 'admin'
";
$stmt = $conn->prepare($selectUserQuery);
$stmt->execute();
$result = $stmt->get_result();
$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Users</title>
    <style>
        #container {
            margin-left: 300px;
            margin-top: 50px;
        }

        .item-container {
            background-color: white;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            width: 300px;
        }

        #userContainer {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            margin-left: 30px;
        }

        .row {
            margin: 5px 0;
        }

        #edit {
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }

        #delete {
            margin-top: 5px;
            background-color: red;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }

        h1 {
            margin-left: 30px;
        }
    </style>
</head>
<body>

<div id="container"> 
    <h1>Manage Users</h1>
    <div id="userContainer">
        <?php foreach ($users as $user): ?>
            <div class="item-container">
              

                <div class='row'><strong>Username:</strong> <?= htmlspecialchars($user['usernames']) ?></div>
                <div class='row'><strong>User ID:</strong> <?= htmlspecialchars($user['user_id']) ?></div>
                <div class='row'><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></div>
                <div class='row'><strong>Address:</strong> <?= htmlspecialchars(decrypt_address($user['address'])) ?></div>
                <div class='row'><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></div>

                <form method="POST" action="editUser.php">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                    <button id="edit" type="submit" name="editUser">Edit User</button>
                </form>

              
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>