<?php
session_start();
include "db_connect.php";

// 1. Security Check: Is the user logged in and is there a loan ID?
if (!isset($_SESSION['user_id']) || !isset($_GET['loan_id'])) {
    die("Access Denied. Please login.");
}

$loan_id = $_GET['loan_id'];
$user_id = $_SESSION['user_id'];

// 2. Fetch Loan and User details using a JOIN
$query = "SELECT l.*, c.first_name, c.second_name, c.phone 
          FROM loans l 
          JOIN clients_info c ON l.user_id = c.id 
          WHERE l.id = ? AND l.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $loan_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Error: Receipt not found or unauthorized access.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SMEP_Receipt_<?php echo $data['id']; ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; display: flex; justify-content: center; padding: 20px; background: #eee; }
        .receipt { background: white; width: 350px; padding: 25px; border: 1px solid #ccc; position: relative; }
        
        /* Watermark */
        .receipt::after {
            content: "SMEP OFFICIAL";
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 35px; color: rgba(0,0,0,0.05); white-space: nowrap; z-index: 0;
        }

        .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 10px; }
        .content { margin: 20px 0; z-index: 1; position: relative; }
        .footer { text-align: center; border-top: 2px dashed #000; padding-top: 10px; font-size: 12px; }
        .btn-print { margin-top: 20px; width: 100%; padding: 10px; background: #333; color: white; border: none; cursor: pointer; }
        
        @media print { .btn-print { display: none; } body { background: white; padding: 0; } }
    </style>
</head>
<body>

<div class="receipt">
    <div class="header">
        <h2 style="margin:0;">SMEP MICROFINANCE</h2>
        <p style="margin:5px 0;">Trust & Progress</p>
    </div>

    <div class="content">
        <p><strong>Receipt No:</strong> #RC-<?php echo str_pad($data['id'], 6, '0', STR_PAD_LEFT); ?></p>
        <p><strong>Date:</strong> <?php echo date("d M Y, H:i", strtotime($data['created_at'])); ?></p>
        <hr>
        <p><strong>Customer:</strong> <?php echo strtoupper($data['first_name'] . " " . $data['second_name']); ?></p>
        <p><strong>Phone:</strong> <?php echo $data['phone']; ?></p>
        <p><strong>Description:</strong> Loan Disbursement</p>
        <h3 style="text-align: right;">Ksh <?php echo number_format($data['amount'], 2); ?></h3>
    </div>

    <div class="footer">
        <p>Thank you for choosing SMEP.</p>
        <p><em>Verified Transaction</em></p>
    </div>

    <button class="btn-print" onclick="window.print()">Print or Save as PDF</button>
</div>

</body>
</html>