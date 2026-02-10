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
   HANDLE TRANSFER
============================ */
if (isset($_POST['transfer'])) {
    $receiver_account = trim($_POST['receiver_account']);
    $amount = floatval($_POST['amount']);

    // Validate receiver exists
    $receiver_q = $conn->prepare("SELECT * FROM users WHERE account_number=? LIMIT 1");
    $receiver_q->bind_param("s", $receiver_account);
    $receiver_q->execute();
    $receiver_result = $receiver_q->get_result();

    if ($receiver_result->num_rows === 0) {
        $message = "<p class='error-box'>Receiver account not found.</p>";
    } else {
        $receiver = $receiver_result->fetch_assoc();

        // Validation checks
        if ($amount <= 0) {
            $message = "<p class='error-box'>Enter a valid amount greater than 0</p>";
        } elseif ($amount > $user['balance']) {
            $message = "<p class='error-box'>Insufficient balance</p>";
        } elseif ($receiver['id'] == $user_id) {
            $message = "<p class='error-box'>You cannot transfer to your own account</p>";
        } else {
            // Deduct from sender
            $new_sender_balance = $user['balance'] - $amount;
            $stmt1 = $conn->prepare("UPDATE users SET balance=? WHERE id=?");
            $stmt1->bind_param("di", $new_sender_balance, $user_id);
            $stmt1->execute();

            // Add to receiver
            $new_receiver_balance = $receiver['balance'] + $amount;
            $stmt2 = $conn->prepare("UPDATE users SET balance=? WHERE id=?");
            $stmt2->bind_param("di", $new_receiver_balance, $receiver['id']);
            $stmt2->execute();

            // Record transaction for sender
            $type = 'transfer';
            $stmt3 = $conn->prepare("
                INSERT INTO transactions
                (user_id, sender_account, receiver_account, amount, transaction_type, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt3->bind_param("issds", $user_id, $user['account_number'], $receiver['account_number'], $amount, $type);
            $stmt3->execute();

            $message = "<p class='amount-out'>Transfer successful! Your new balance is " . number_format($new_sender_balance, 2) . "</p>";

            // Refresh sender balance
            $user['balance'] = $new_sender_balance;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transfer - CRDB Bank</title>
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
        <a href="transfer.php" class="active">Transfer</a>
        <a href="transactions.php">Transactions</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="form-card">
            <h2>Transfer Funds</h2>

            <p>Current Balance: <strong><?= number_format($user['balance'], 2) ?></strong></p>

            <?= $message ?>

            <form method="POST">
                <div class="field">
                    <label>Receiver Account Number</label>
                    <input type="text" name="receiver_account" required>
                </div>
                <div class="field">
                    <label>Amount</label>
                    <input type="number" name="amount" step="0.01" min="0.01" required>
                </div>
                <button class="primary" name="transfer">Send</button>
            </form>
        </div>

    </div>
</div>

</body>
</html>
