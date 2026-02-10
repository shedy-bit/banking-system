<?php
session_start();
require '../config/db.php';
if(!isset($_SESSION['admin_id'])) header("Location: login.php");

$users = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM users"))[0];
$transactions = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM transactions"))[0];
$money = mysqli_fetch_row(mysqli_query($conn,"SELECT SUM(balance) FROM users"))[0];
?>

<h2>Admin Dashboard</h2>

<ul>
    <li>Total Users: <b><?= $users ?></b></li>
    <li>Total Transactions: <b><?= $transactions ?></b></li>
    <li>Total Money in Bank: <b><?= number_format($money,2) ?></b></li>
</ul>

<a href="users.php">View Users</a> |
<a href="transactions.php">View Transactions</a> |
<a href="logout.php">Logout</a>
