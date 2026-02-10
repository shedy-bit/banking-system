<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRDB Online Banking System</title>

    <!-- Main upgraded CSS -->
    <link rel="stylesheet" href="assets/css/main.css">

    <style>
        /* =========================
           HOME PAGE STYLES
        ========================== */
        body.home-page {
            background: var(--bg);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .home-card {
            background: var(--card);
            padding: 40px;
            border-radius: var(--radius);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
            width: 100%;
            max-width: 440px;
        }

        .home-card h1 {
            color: var(--primary);
            margin-bottom: 8px;
        }

        .home-card p {
            color: var(--muted);
            margin-bottom: 30px;
        }

        .home-links a {
            display: block;
            padding: 12px;
            margin-bottom: 12px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            background: var(--primary);
            color: #fff;
            transition: 0.3s;
        }

        .home-links a:hover {
            background: var(--primary-dark);
        }

        /* Admin button style */
        .admin-btn {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .admin-btn:hover {
            background: var(--primary);
            color: #fff;
        }

        footer {
            margin-top: 20px;
            text-align: center;
            font-size: 13px;
            color: var(--muted);
        }
    </style>
</head>

<body class="home-page">

    <div class="home-card">
        <h1>CRDB Online Banking</h1>
        <p>Secure • Fast • Reliable banking platform</p>

        <div class="home-links">
            <a href="register.php">Create Account</a>
            <a href="login.php">User Login</a>
            <a href="admin/admin_login.php" class="admin-btn">Login as Admin</a>
        </div>

        <footer>
            © <?= date('Y') ?> CRDB Online Banking System
        </footer>
    </div>

</body>
</html>
