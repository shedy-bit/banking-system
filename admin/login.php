<?php
session_start();
require "config/db.php";

if(isset($_POST['admin_login'])){
    $username = $_POST['admin_username'];
    $password = $_POST['admin_password'];

    $q = mysqli_query($conn, "SELECT * FROM admins WHERE username='$username'");
    $admin = mysqli_fetch_assoc($q);

    if($admin && password_verify($password, $admin['password'])){
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login | Online Banking</title>
<link rel="stylesheet" href="assets/css/main.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="auth-wrapper">

    <div class="auth-card">

        <img src="assets/images/crdb-logo.png" class="auth-logo" alt="Bank Logo">

        <h2>Admin Login</h2>
        <p class="auth-subtitle">Login to manage the system</p>

        <?php if(isset($error)): ?>
            <div class="error-box"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="field">
                <label>Username</label>
                <input type="text" name="admin_username" required>
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="admin_password" required>
            </div>

            <button class="primary" name="admin_login">Login</button>

        </form>

        <p class="auth-footer">
            <a href="login.php">Back to User Login</a>
        </p>

    </div>

</div>

</body>
</html>
