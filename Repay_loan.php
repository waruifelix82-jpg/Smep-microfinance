<?php
session_start();
require_once 'config.php';

$user_id = $_SESSION['user_id'];

// Fetch the loan data
$query = "SELECT id, total_payable FROM loans WHERE user_id = ? AND status = 'Approved' LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$loan = $result->fetch_assoc();

// If no loan is found, show a user-friendly message instead of crashing
if (!$loan) {
    echo "<div style='text-align:center; margin-top:50px; font-family:Arial;'>
            <h2>No Active Loan Found</h2>
            <p>You currently do not have an approved loan to repay.</p>
            <a href='dashboard.php'>Return to Dashboard</a>
          </div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Repay Loan | SMEP</title>
    <link rel="stylesheet" href="Repay_loan.css">
</head>
<body>
    <div class="repay-container">
        <h2>Repay Your Loan</h2>
        <hr>
        <p>Current Balance: <strong>KES <?php echo number_format($loan['total_payable'], 2); ?></strong></p>
        
        <form action="submit_payment.php" method="POST">
            <input type="hidden" name="loan_id" value="<?php echo $loan['id']; ?>">
            
            <label>Amount to Pay (KES):</label>
            <input type="number" name="amount_paid" max="<?php echo $loan['total_payable']; ?>" step="0.01" required>
            
            <label>M-Pesa Transaction Ref:</label>
            <input type="text" name="transaction_ref" placeholder="e.g. RBT5678XYZ" required style="text-transform: uppercase;">
            
            <button type="submit" class="btn-primary">Confirm Payment</button>
        </form>
    </div>
</body>
</html>