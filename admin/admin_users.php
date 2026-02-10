<?php
session_start();
require "../config/db.php";

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all users
$query = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users | CRDB Admin</title>
<link rel="stylesheet" href="../assets/css/main.css">
<style>
table { width: 100%; border-collapse: collapse; margin-top: 15px; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
th { background-color: #f4f4f4; }

.button-block { background-color: orange; color: #fff; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
.button-unblock { background-color: green; color: #fff; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
.button-delete { background-color: red; color: #fff; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
.button-block:hover, .button-unblock:hover, .button-delete:hover { opacity: 0.8; }

.layout { display: flex; min-height: 100vh; font-family: Arial, sans-serif; }
.sidebar { width: 200px; background: #0a7d44; color: #fff; padding: 20px; }
.sidebar a { display: block; color: #fff; text-decoration: none; margin: 10px 0; }
.sidebar a.logout { color: #ff4d4d; }
.main { flex: 1; padding: 20px; }
.status-active { color: green; font-weight: bold; }
.status-blocked { color: red; font-weight: bold; }
</style>
</head>
<body>

<div class="layout">

<div class="sidebar">
    <h3>CRDB ADMIN</h3>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_users.php">Manage Users</a>
    <a href="admin_transactions.php">Transactions</a>
    <a href="admin_charts.php">Analytics</a>
    <a href="admin_logout.php" class="logout">Logout</a>
</div>

<div class="main">
    <h2>Manage Users</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while ($user = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['fullname']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td class="status-<?= $user['status'] === 'active' ? 'active' : 'blocked' ?>">
                    <?= ucfirst($user['status']) ?>
                </td>
                <td>
                    <!-- Block/Unblock buttons -->
                    <?php if($user['status'] === 'active'): ?>
                        <a href="admin_user_action.php?action=block&id=<?= $user['id'] ?>" class="button-block">Block</a>
                    <?php else: ?>
                        <a href="admin_user_action.php?action=unblock&id=<?= $user['id'] ?>" class="button-unblock">Unblock</a>
                    <?php endif; ?>

                    <!-- Delete button -->
                    <a href="admin_user_action.php?action=delete&id=<?= $user['id'] ?>" class="button-delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center;">No users found</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

</div>
</body>
</html>
