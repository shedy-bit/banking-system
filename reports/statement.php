<?php
session_start();
require '../config/db.php';
require '../fpdf/fpdf.php';

$user_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'"));

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'CRDB BANK - TRANSACTION STATEMENT',0,1,'C');

$pdf->SetFont('Arial','',10);
$pdf->Cell(0,8,'Account Holder: '.$user['fullname'],0,1);
$pdf->Cell(0,8,'Account Number: '.$user['account_number'],0,1);
$pdf->Ln(5);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,8,'Type',1);
$pdf->Cell(40,8,'Sender',1);
$pdf->Cell(40,8,'Receiver',1);
$pdf->Cell(30,8,'Amount',1);
$pdf->Cell(40,8,'Date',1);
$pdf->Ln();

$pdf->SetFont('Arial','',9);

$result = mysqli_query($conn,"
    SELECT * FROM transactions
    WHERE sender_account='{$user['account_number']}'
       OR receiver_account='{$user['account_number']}'
");

while($row = mysqli_fetch_assoc($result)){
    $pdf->Cell(30,8,$row['transaction_type'],1);
    $pdf->Cell(40,8,$row['sender_account'],1);
    $pdf->Cell(40,8,$row['receiver_account'],1);
    $pdf->Cell(30,8,number_format($row['amount'],2),1);
    $pdf->Cell(40,8,$row['created_at'],1);
    $pdf->Ln();
}

$pdf->Output();
