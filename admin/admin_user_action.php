<?php
session_start();
require "../config/db.php";

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if action and id are set
if (!isset($_GET['action'], $_GET['id'])) {
    die("Invalid request");
}

$action = $_GET['action'];
$user_id = intval($_GET['id']); // sanitize input

switch($action) {
    case 'block':
        mysqli_query($conn, "UPDATE users SET status='blocked' WHERE id=$user_id");
        break;

    case 'unblock':
        mysqli_query($conn, "UPDATE users SET status='active' WHERE id=$user_id");
        break;

    case 'delete':
        mysqli_query($conn, "DELETE FROM users WHERE id=$user_id");
        break;

    default:
        die("Invalid action");
}

// Redirect back to the users page
header("Location: admin_users.php");
exit();
