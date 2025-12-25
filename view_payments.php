<?php
session_start();
require_once 'config.php';

// Fetch payment history
$sql = "SELECT 
            p.id as payment_id, 
            c.first_name, 
            l.amount as loan_amount, 
            p.amount_paid, 
            p.payment_date 
        FROM payments p
        JOIN loans l ON p.loan_id = l.id
        JOIN clients_info c ON l.user_id = c.id
        ORDER BY p.payment_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMEP Admin | Payment History</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

    <div class="sidebar">
        <h2>SMEP Admin</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="view_payments.php" class="active">Payment History</a>
        <a href="users.php">Manage Users</a>
        <a href="../logout.php" style="margin-top: 50px; color: #e74c3c;">Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Payment History</h1>
            <p>Track all incoming loan repayments</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Ref No.</th>
                    <th>Customer Name</th>
                    <th>Loan Amount</th>
                    <th>Amount Paid</th>
                    <th>Date Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#PAY-<?php echo $row['payment_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td>KES <?php echo number_format($row['loan_amount']); ?></td>
                        <td style="color: #27ae60; font-weight: bold;">
                            + KES <?php echo number_format($row['amount_paid']); ?>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($row['payment_date'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No payment records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>