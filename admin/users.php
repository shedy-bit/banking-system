<?php
require '../config/db.php';
$result = mysqli_query($conn,"SELECT * FROM users");
?>

<h2>Registered Users</h2>

<table border="1">
<tr><th>ID</th><th>Name</th><th>Email</th><th>Balance</th></tr>
<?php while($u=mysqli_fetch_assoc($result)): ?>
<tr>
<td><?= $u['id'] ?></td>
<td><?= $u['fullname'] ?></td>
<td><?= $u['email'] ?></td>
<td><?= number_format($u['balance'],2) ?></td>
</tr>
<?php endwhile; ?>
</table>
