<?php
require "../config/db.php";

$msg = "";

if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT id FROM admins WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $msg = "Admin already exists";
    } else {
        mysqli_query($conn,
            "INSERT INTO admins (fullname, email, password)
             VALUES ('$name','$email','$pass')"
        );
        $msg = "Admin registered successfully";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Registration</title>
<link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>

<div class="auth-wrapper">
<div class="auth-card">

<img src="../assets/images/crdb-logo.png" class="auth-logo">

<h2>Admin Registration</h2>

<?php if($msg): ?>
<div class="error-box"><?= $msg ?></div>
<?php endif; ?>

<form method="POST">

<div class="field">
<label>Full Name</label>
<input type="text" name="fullname" required>
</div>

<div class="field">
<label>Email</label>
<input type="email" name="email" required>
</div>

<div class="field">
<label>Password</label>
<input type="password" name="password" required>
</div>

<button class="primary" name="register">Create Admin</button>

</form>

</div>
</div>

</body>
</html>
