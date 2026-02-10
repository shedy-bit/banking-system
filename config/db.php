<?php
// Database configuration
$host = "localhost";
$user = "root";
$password = "";
$database = "crdb_bank";

// Connect to MySQL
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
