<?php
session_start();
require "config/db.php";

$error = "";

if (isset($_POST['register'])) {

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $account  = rand(10000000, 99999999);
    $balance  = 0;

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already registered";
    } else {

        $sql = "INSERT INTO users 
                (fullname, email, password, account_number, balance, created_at)
                VALUES 
                ('$fullname', '$email', '$password', '$account', '$balance', NOW())";

        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
            exit();
        } else {
            $error = mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Account | CRDB Online Banking</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/main.css">
</head>

<body>

<div class="auth-wrapper">

    <div class="auth-card">

        <!-- Logo -->
        <img src="assets/images/crdb-logo.png" class="auth-logo" alt="CRDB Bank">

        <h2>Create Account</h2>
        <p class="auth-subtitle">Open your secure online banking account</p>

        <?php if ($error): ?>
            <div class="error-box"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">

            <div class="field">
                <label>Full Name</label>
                <input type="text" name="fullname" placeholder="John Doe" required>
            </div>

            <div class="field">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="john@example.com" required>
            </div>

            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="********" required>
            </div>

            <button class="primary" name="register">
                Create Account
            </button>

        </form>

        <p class="auth-footer">
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </div>

</div>

</body>
</html>
