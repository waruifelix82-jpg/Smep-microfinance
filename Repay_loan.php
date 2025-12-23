<?php
session_start();
require_once 'config.php'; // Your database connection

// 1. Get the latest approved loan for this user
$user_id = $_SESSION['user_id'];
$query = "SELECT id, total_payable FROM loans WHERE user_id = ? AND status = 'Approved' LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$loan = $result->fetch_assoc();

if (!$loan) {
    echo "You do not have an active approved loan to repay.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Repay Loan | SMEP</title>
    <link rel="stylesheet" href="Repay_loan.css"> </head>
<body>
    <div class="repay-container" style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2>Repay Your Loan</h2>
        <p>Current Balance: <strong>KES <?php echo number_format($loan['total_payable']); ?></strong></p>
        
        <form action="submit_repayment.php" method="POST">
            <input type="hidden" name="loan_id" value="<?php echo $loan['id']; ?>">
            
            <label>Amount to Pay (KES):</label>
            <input type="number" name="amount_paid" required style="width:100%; padding:10px; margin: 10px 0;">
            
            <label>M-Pesa Transaction Ref:</label>
            <input type="text" name="transaction_ref" placeholder="e.g. RBT5678XYZ" required style="width:100%; padding:10px; margin: 10px 0;">
            
            <button type="submit" class="btn-primary" style="width:100%; border:none; padding:10px; cursor:pointer;">Confirm Payment</button>
        </form>
    </div>
</body>
</html>