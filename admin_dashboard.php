<?php
session_start();
require_once 'config.php'; 

// 1. Search Logic (Fixed with SQL Security)
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_query = "";
if (!empty($search)) {
    $safe_search = $conn->real_escape_string($search);
    $search_query = " WHERE clients_info.first_name LIKE '%$safe_search%' ";
}

// 2. FIXED SQL: We rename loans.id to 'loan_id' so it doesn't clash with clients_info.id
$sql = "SELECT loans.id AS loan_id, clients_info.first_name, loans.amount, loans.status 
        FROM loans 
        JOIN clients_info ON loans.user_id = clients_info.id 
        $search_query
        ORDER BY loans.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMEP Admin | Loan Management</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

    <div class="sidebar">
        <h2>SMEP Admin</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="view_payments.php">Payment History</a>
        <a href="users.php">Manage Users</a>
        <a href="../logout.php" style="margin-top: 50px; color: #e74c3c;">Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Loan Applications</h1>
            <p>Review and manage customer loan requests</p>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div id="status-msg" style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 4px;">
                <strong>Success!</strong> The loan status has been updated.
            </div>
            <script>
                setTimeout(() => { document.getElementById('status-msg').style.display = 'none'; }, 3000);
            </script>
        <?php endif; ?>

        <div style="margin-bottom: 20px;">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by customer name..." value="<?php echo htmlspecialchars($search); ?>" style="padding: 10px; width: 300px; border-radius: 4px; border: 1px solid #ccc;">
                <button type="submit" style="padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;">Search</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['loan_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td>KES <?php echo number_format($row['amount']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($row['status']); ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if($row['status'] == 'Pending'): ?>
                                <a href="manage_loans.php?id=<?php echo $row['loan_id']; ?>&action=approve" 
                                   class="btn-approve" 
                                   onclick="return confirm('Approve loan for <?php echo $row['first_name']; ?>?')">Approve</a>
                                
                                <a href="manage_loans.php?id=<?php echo $row['loan_id']; ?>&action=reject" 
                                   class="btn-reject" 
                                   onclick="return confirm('Reject this loan application?')">Reject</a>
                            <?php else: ?>
                                <span style="color: #95a5a6; font-style: italic;">Processed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;">No loan records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>