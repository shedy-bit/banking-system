<?php
session_start();
require "../config/db.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM users"))['total'];
$active = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM users WHERE status='active'"))['total'];
$blocked = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM users WHERE status='blocked'"))['total'];
$transactions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM transactions"))['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>

<div class="layout">

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>CRDB ADMIN</h3>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_users.php">Manage Users</a>
    <a href="admin_transactions.php">Transactions</a>
    <a href="admin_charts.php">Analytics</a>
<a href="admin_report_pdf.php" target="_blank">Download PDF Report</a>
    <a href="admin_logout.php" class="logout">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<div class="welcome-card">
    <h2>Welcome, <?= $_SESSION['admin_name'] ?></h2>
    <p>System Administration Panel</p>
</div>

<div class="actions">

<div class="balance-card">
<h4>Total Users</h4>
<h1><?= $users ?></h1>
</div>

<div class="balance-card">
<h4>Active Users</h4>
<h1><?= $active ?></h1>
</div>

<div class="balance-card">
<h4>Blocked Users</h4>
<h1><?= $blocked ?></h1>
</div>

<div class="balance-card">
<h4>Total Transactions</h4>
<h1><?= $transactions ?></h1>
</div>

</div>

</div>
</div>

</body>
</html>
