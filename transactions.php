<?php
session_start();
require 'config/db.php';

/* ============================
   USER AUTH CHECK
============================ */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ============================
   FETCH USER INFO
============================ */
$user_q = $conn->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
$user_q->bind_param("i", $user_id);
$user_q->execute();
$user_result = $user_q->get_result();
$user = $user_result->fetch_assoc();

/* ============================
   FILTER HANDLING
============================ */
$typeFilter = "";
$filterType = "";

if (!empty($_GET['type'])) {
    $filterType = $_GET['type'];
    $allowedTypes = ['deposit', 'withdraw', 'transfer'];
    if (in_array($filterType, $allowedTypes)) {
        $typeFilter = "AND transaction_type='$filterType'";
    }
}

/* ============================
   FETCH TRANSACTIONS
============================ */
$trx_sql = "
    SELECT *
    FROM transactions
    WHERE user_id = ?
    $typeFilter
    ORDER BY created_at DESC
";

$stmt = $conn->prepare($trx_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$trx_q = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction History - CRDB Bank</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

<div class="layout">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <img src="assets/images/crdb-logo.png" class="logo">
        <h3>CRDB BANK</h3>
        <a href="dashboard.php">Dashboard</a>
        <a href="deposit.php">Deposit</a>
        <a href="withdraw.php">Withdraw</a>
        <a href="transfer.php">Transfer</a>
        <a href="transactions.php" class="active">Transactions</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <h2>Transaction History</h2>

        <!-- Filter by type -->
        <div class="filters">
            <a href="transactions.php" class="<?= $filterType===''?'active':'' ?>">All</a>
            <a href="transactions.php?type=deposit" class="<?= $filterType==='deposit'?'active':'' ?>">Deposit</a>
            <a href="transactions.php?type=withdraw" class="<?= $filterType==='withdraw'?'active':'' ?>">Withdraw</a>
            <a href="transactions.php?type=transfer" class="<?= $filterType==='transfer'?'active':'' ?>">Transfer</a>
        </div>

        <div class="table-container">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Sender</th>
                    <th>Receiver</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>

                <?php if(mysqli_num_rows($trx_q) > 0): ?>
                    <?php while ($trx = mysqli_fetch_assoc($trx_q)): ?>
                    <tr>
                        <td><?= $trx['id'] ?></td>
                        <td><?= ucfirst($trx['transaction_type']) ?></td>
                        <td><?= htmlspecialchars($trx['sender_account'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($trx['receiver_account'] ?: '-') ?></td>
                        <td class="<?= $trx['transaction_type']=='deposit'?'amount-in':($trx['transaction_type']=='withdraw'?'amount-out':'amount-out') ?>">
                            <?= number_format($trx['amount'], 2) ?>
                        </td>
                        <td><?= date("d M Y, H:i", strtotime($trx['created_at'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;padding:20px;">
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
