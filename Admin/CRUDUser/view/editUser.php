<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/db_connection.php';
session_start();

if (!isset($_SESSION['adminUsername'])) {
    exit('Access Denied');
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gearUp/encryption_helper.php';

function decrypt_address($encrypted_address) {
    global $encryption_key, $encryption_iv;
    return openssl_decrypt($encrypted_address, 'aes-256-cbc', $encryption_key, 0, $encryption_iv);
}

function encrypt_address($plain_address) {
    global $encryption_key, $encryption_iv;
    return openssl_encrypt($plain_address, 'aes-256-cbc', $encryption_key, 0, $encryption_iv);
}

$user_id = $_POST['user_id'] ?? $_GET['user_id'] ?? null;
if (!$user_id) {
    exit('User ID is required');
}

// Get current logged-in user role (assumed saved in session)
$currentUserRole = $_SESSION['role'] ?? 'admin'; // default to admin if not set

$error = '';
$success = '';

// Fetch user data to prefill form (do this first to have $user available)
$stmt = $conn->prepare("SELECT usernames, email, address, role FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    exit('User not found');
}

$user = $result->fetch_assoc();
$decrypted_address = decrypt_address($user['address']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);

    // For role, only accept role change if current user is superuser
    if ($currentUserRole === 'superuser') {
        $role = trim($_POST['role']);
    } else {
        // Force role to current user's role from DB to prevent unauthorized change
        $role = $user['role'];
    }

    // Simple validation
    if (empty($username) || empty($email) || empty($role)) {
        $error = 'Please fill in all required fields (username, email, role).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        $encrypted_address = encrypt_address($address);

        $updateQuery = "UPDATE users SET usernames=?, email=?, address=?, role=? WHERE user_id=?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssssi", $username, $email, $encrypted_address, $role, $user_id);

        if ($stmt->execute()) {
            $success = "User updated successfully.";
            header("Location: viewUser.php?msg=" . urlencode($success));
            exit;
        } else {
            $error = "Failed to update user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit User</title>
    <style>
        body { margin-left: 300px; padding: 20px; background: #f8f9fa; font-family: Arial, sans-serif; }
        form { background: white; padding: 20px; border-radius: 6px; max-width: 500px; }
        label { display: block; margin: 10px 0 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #007bff; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; }
        button:hover { background: #0056b3; }
        .error { color: red; margin-bottom: 10px; }
        .success { color: green; margin-bottom: 10px; }
        a { text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h1>Edit User</h1>

<?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form method="POST" action="editUser.php">
    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">

    <label for="username">Username</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['usernames']) ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label for="address">Address</label>
    <textarea id="address" name="address" rows="3"><?= htmlspecialchars($decrypted_address) ?></textarea>

    <label for="role">Role</label>
    <?php if ($currentUserRole === 'superuser'): ?>
        <select id="role" name="role" required>
            <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
            <option value="seller" <?= $user['role'] === 'seller' ? 'selected' : '' ?>>Seller</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="superuser" <?= $user['role'] === 'superuser' ? 'selected' : '' ?>>Superuser</option>
            <!-- add other roles if needed -->
        </select>
    <?php else: ?>
        <input type="text" value="<?= htmlspecialchars($user['role']) ?>" disabled>
        <input type="hidden" name="role" value="<?= htmlspecialchars($user['role']) ?>">
    <?php endif; ?>

    <button type="submit" name="updateUser">Save Changes</button>
</form>

<p><a href="viewUser.php">Back to User List</a></p>

</body>
</html>