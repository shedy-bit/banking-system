<?php
require '../config/db.php';

if(isset($_POST['register'])){
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($conn,
        "INSERT INTO admins (fullname,email,password)
         VALUES ('$name','$email','$password')"
    );
    header("Location: login.php");
}
?>

<form method="POST">
    <h2>Admin Registration</h2>
    <input name="fullname" placeholder="Full Name" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button name="register">Register</button>
</form>
