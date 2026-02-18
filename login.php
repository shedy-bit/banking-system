<?php
session_start();
require "config/db.php";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($q);

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | Online Banking</title>
<link rel="stylesheet" href="assets/css/main.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    

<div class="auth-wrapper">

    <div class="auth-card">

        <img src="assets/images/crdb-logo.png" class="auth-logo" alt="Bank Logo">
<body style="background-color: #94d41c;">
        <h2>Welcome Back</h2>
        <p class="auth-subtitle">Login to access your account</p>

        <?php if(isset($error)): ?>
            <div class="error-box"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button class="primary" name="login">Login</button>

        </form>

        <p class="auth-footer">
            Don’t have an account?
            <a href="register.php">Create one</a>
        </p>

    </div>

</div>

</body>
</html>
