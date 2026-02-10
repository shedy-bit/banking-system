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
   FETCH DAILY TRANSACTION COUNTS
============================ */
$sql = "
    SELECT 
        DATE(created_at) AS tx_date,
        COUNT(*) AS total
    FROM transactions
    GROUP BY DATE(created_at)
    ORDER BY tx_date ASC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

$dates = [];
$totals = [];

while ($row = mysqli_fetch_assoc($result)) {
    $dates[]  = $row['tx_date'];
    $totals[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Transaction Analytics | CRDB Admin</title>

<link rel="stylesheet" href="../assets/css/main.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.chart-box {
    background: var(--card);
    padding: 25px;
    border-radius: var(--radius);
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}
</style>
</head>

<body>

<div class="layout">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h3>CRDB ADMIN</h3>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_users.php">Manage Users</a>
        <a href="admin_transactions.php">Transactions</a>
        <a href="admin_charts.php" class="active">Analytics</a>
        <a href="admin_logout.php" class="logout">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <h2>Daily Transactions Overview</h2>

        <div class="chart-box">
            <canvas id="txChart"></canvas>
        </div>

    </div>
</div>

<script>
const ctx = document.getElementById('txChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($dates) ?>,
        datasets: [{
            label: 'Daily Transactions',
            data: <?= json_encode($totals) ?>,
            borderWidth: 3,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true }
        }
    }
});
</script>

</body>
</html>
