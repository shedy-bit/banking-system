<?php
session_start();
require "../config/db.php";

/* ============================
   ADMIN AUTH CHECK
============================ */
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* ============================
   FILTER HANDLING
============================ */
$statusFilter = $_GET['status'] ?? 'all';
$where = "";

if ($statusFilter === 'active') {
    $where = "WHERE u.status = 'active'";
} elseif ($statusFilter === 'blocked') {
    $where = "WHERE u.status = 'blocked'";
} elseif ($statusFilter === 'deleted') {
    $where = "WHERE u.status = 'deleted'";
}

/* ============================
   FETCH TRANSACTIONS (FULL AUDIT)
============================ */
$sql = "
    SELECT 
        t.id,
        t.amount,
        t.transaction_type,
        t.created_at,
        t.sender_account,
        t.receiver_account,
        u.fullname,
        u.status
    FROM transactions t
    LEFT JOIN users u ON t.user_id = u.id
    $where
    ORDER BY t.created_at DESC
";

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Transactions | CRDB Admin</title>
<link rel="stylesheet" href="../assets/css/main.css">
<style>
.table-container { overflow-x:auto; }

table {
    width:100%;
    border-collapse:collapse;
    background:var(--card);
}

th, td {
    padding:12px;
    border-bottom:1px solid var(--border);
}

th {
    background:#f9fafb;
    font-weight:600;
}

.amount-in { color:#16a34a; font-weight:600; }
.amount-out { color:#dc2626; font-weight:600; }

.badge {
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.badge-active { background:#dcfce7; color:#166534; }
.badge-blocked { background:#fef3c7; color:#92400e; }
.badge-deleted { background:#fee2e2; color:#991b1b; }

.filters a {
    margin-right:10px;
    text-decoration:none;
    font-weight:600;
    color:var(--primary);
}
.filters a.active { text-decoration:underline; }

.action-btn {
    display:inline-block;
    padding:10px 16px;
    background:var(--primary);
    color:#fff;
    border-radius:8px;
    text-decoration:none;
    margin-bottom:15px;
}
.action-btn:hover { background:var(--primary-dark); }
</style>
</head>

<body>

<div class="layout">

<!-- SIDEBAR -->
<div class="sidebar">
    <h3>CRDB ADMIN</h3>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_users.php">Manage Users</a>
    <a href="admin_transactions.php" class="active">Transactions</a>
    <a href="admin_charts.php">Analytics</a>
    <a href="admin_logout.php" class="logout">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<h2>System Transactions</h2>

<div class="filters">
    <a href="?status=all" class="<?= $statusFilter==='all'?'active':'' ?>">All</a>
    <a href="?status=active" class="<?= $statusFilter==='active'?'active':'' ?>">Active</a>
    <a href="?status=blocked" class="<?= $statusFilter==='blocked'?'active':'' ?>">Blocked</a>
    <a href="?status=deleted" class="<?= $statusFilter==='deleted'?'active':'' ?>">Deleted</a>
</div>

<br>

<a href="admin_report_pdf.php" target="_blank" class="action-btn">
    Download PDF Report
</a>

<div class="table-container">
<table>
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Status</th>
    <th>Type</th>
    <th>Sender</th>
    <th>Receiver</th>
    <th>Amount</th>
    <th>Date</th>
</tr>

<?php if (mysqli_num_rows($result) > 0): ?>
<?php while ($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['fullname'] ?? 'Unknown') ?></td>

    <td>
        <?php if ($row['status'] === 'active'): ?>
            <span class="badge badge-active">Active</span>
        <?php elseif ($row['status'] === 'blocked'): ?>
            <span class="badge badge-blocked">Blocked</span>
        <?php else: ?>
            <span class="badge badge-deleted">Deleted</span>
        <?php endif; ?>
    </td>

    <td><?= ucfirst($row['transaction_type']) ?></td>
    <td><?= htmlspecialchars($row['sender_account'] ?? '-') ?></td>
    <td><?= htmlspecialchars($row['receiver_account'] ?? '-') ?></td>

    <td class="<?= $row['transaction_type']==='deposit'?'amount-in':($row['transaction_type']==='withdraw'?'amount-out':'amount-out') ?>">
        <?= number_format($row['amount'], 2) ?>
    </td>

    <td><?= date("d M Y, H:i", strtotime($row['created_at'])) ?></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="8" style="text-align:center;padding:20px;">
        No transactions found
    </td>
</tr>
<?php endif; ?>

</table>
</div>

</div>
</div>

</body>
</html>
