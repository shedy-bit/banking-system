<?php
session_start();
require 'config/db.php';

/* ============================
   USER AUTH CHECK
============================ */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
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

$message = "";

/* ============================
   HANDLE WITHDRAWAL
============================ */
if (isset($_POST['withdraw'])) {
    $amount = floatval($_POST['amount']);

    if ($amount <= 0) {
        $message = "<p class='error-box'>Enter a valid amount greater than 0</p>";
    } elseif ($amount > $user['balance']) {
        $message = "<p class='error-box'>Insufficient balance</p>";
    } else {
        // Deduct from user balance securely
        $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $stmt->bind_param("di", $amount, $user_id);
        $stmt->execute();

        // Record transaction
        $type = 'withdraw';
        $stmt2 = $conn->prepare("
            INSERT INTO transactions
            (user_id, sender_account, receiver_account, amount, transaction_type, created_at)
            VALUES (?, ?, 'BANK', ?, ?, NOW())
        ");
        $stmt2->bind_param("isds", $user_id, $user['account_number'], $amount, $type);
        $stmt2->execute();

        $message = "<p class='amount-out'>Withdrawal successful! Your new balance is " . number_format($user['balance'] - $amount, 2) . "</p>";

        // Refresh user balance
        $user['balance'] -= $amount;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Withdraw - CRDB Bank</title>
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
        <a href="withdraw.php" class="active">Withdraw</a>
        <a href="transfer.php">Transfer</a>
        <a href="transactions.php">Transactions</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="form-card">
            <h2>Withdraw Funds</h2>

            <p>Current Balance: <strong><?= number_format($user['balance'], 2) ?></strong></p>

            <?= $message ?>

            <form method="POST">
                <div class="field">
                    <label>Amount</label>
                    <input type="number" name="amount" step="0.01" min="0.01" required>
                </div>
                <button class="primary" name="withdraw">Withdraw</button>
            </form>
        </div>

    </div>
</div>

</body>
</html>
