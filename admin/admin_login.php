<?php
session_start();
require "../config/db.php";

$error = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $admin  = $result->fetch_assoc();

    if ($admin && password_verify($pass, $admin['password'])) {

        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_name'] = $admin['fullname'];
        $_SESSION['admin_role'] = $admin['role'] ?? 'admin';

        header("Location: admin_dashboard.php");
        exit();

    } else {
        $error = "Invalid admin email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login | CRDB Online Banking</title>

<link rel="stylesheet" href="../assets/css/main.css">

<style>
/* ==============================
   ADMIN LOGIN PAGE STYLES
================================ */
body {
    background: linear-gradient(135deg, #0a7d44, #065c31);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.admin-auth-card {
    background: var(--card);
    padding: 40px;
    width: 100%;
    max-width: 420px;
    border-radius: var(--radius);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    text-align: center;
}

.admin-auth-card img {
    width: 90px;
    margin-bottom: 15px;
}

.admin-auth-card h2 {
    color: var(--primary);
    margin-bottom: 5px;
}

.admin-auth-card p {
    color: var(--muted);
    margin-bottom: 25px;
}

.field {
    text-align: left;
    margin-bottom: 15px;
}

.field label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
}

.field input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid var(--border);
    outline: none;
}

.field input:focus {
    border-color: var(--primary);
}

button.primary {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    background: var(--primary);
    color: #fff;
    border: none;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

button.primary:hover {
    background: var(--primary-dark);
}

.error-box {
    background: #fee2e2;
    color: #991b1b;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.back-home {
    display: block;
    margin-top: 18px;
    font-size: 14px;
    color: var(--muted);
    text-decoration: none;
}

.back-home:hover {
    color: var(--primary);
}
</style>
</head>

<body>

<div class="admin-auth-card">

    <img src="../assets/images/crdb-logo.png" alt="CRDB Bank">

    <h2>Admin Login</h2>
    <p>Authorized personnel only</p>

    <?php if (!empty($error)): ?>
        <div class="error-box"><?= htmlspecialchars($error) ?></div>
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

    <a href="../index.php" class="back-home">← Back to Home</a>

</div>

</body>
</html>
