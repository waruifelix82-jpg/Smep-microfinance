<?php
session_start();
// Replace config.php with your actual connection file if it's named db_connect.php
require_once 'db_connect.php'; 

// 1. SECURITY: Ensure only logged-in admins can see this
if(!isset($_SESSION['admin_logged_in'])) { 
    header("Location: admin_login.php"); 
    exit(); 
}

// 2. SEARCH & FILTER LOGIC
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_query = "";

if (!empty($search)) {
    $safe_search = $conn->real_escape_string($search);
    // Search by first name or second name
    $search_query = " WHERE (clients_info.first_name LIKE '%$safe_search%' OR clients_info.second_name LIKE '%$safe_search%') ";
}

// 3. FETCH DATA: Get all loans and join with customer details
$sql = "SELECT loans.id AS loan_id, clients_info.first_name, clients_info.second_name, loans.amount, loans.status 
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
        <hr style="border: 0.5px solid #34495e;">
        <a href="admin_dashboard.php">📊 Loan Applications</a>
        <a href="view_payments.php">💰 Payment History</a>
        <a href="users.php">👥 Manage Users</a>
        <a href="admin_logout.php" style="color: #e74c3c;">🚪 Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Loan Management</h1>
            <p>Authorize applications to generate customer receipts.</p>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
            <div id="status-msg" style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 4px;">
                <strong>Action Successful!</strong> The loan status has been updated and the client can now access their receipt.
            </div>
            <script>
                setTimeout(() => { document.getElementById('status-msg').style.display = 'none'; }, 4000);
            </script>
        <?php endif; ?>

        <div style="margin-bottom: 30px;">
            <form action="admin_dashboard.php" method="GET" style="display: flex; gap: 10px;">
                <input type="text" name="search" placeholder="Search customer by name..." value="<?php echo htmlspecialchars($search); ?>" 
                       style="padding: 12px; width: 350px; border-radius: 5px; border: 1px solid #ddd;">
                <button type="submit" style="padding: 12px 25px; background: #2c3e50; color: white; border: none; border-radius: 5px; cursor: pointer;">Search</button>
                <?php if(!empty($search)): ?>
                    <a href="admin_dashboard.php" style="padding: 12px; color: #7f8c8d; text-decoration: none;">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Amount Requested</th>
                    <th>Current Status</th>
                    <th>Action / Control</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?php echo $row['loan_id']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['second_name']); ?></td>
                        <td>Ksh <?php echo number_format($row['amount'], 2); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($row['status']); ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if($row['status'] == 'Pending'): ?>
                                <a href="manage_loans.php?id=<?php echo $row['loan_id']; ?>&action=approve" 
                                   class="btn-approve" 
                                   onclick="return confirm('Confirm approval? This will generate a legal receipt for the client.')">Approve</a>
                                
                                <a href="manage_loans.php?id=<?php echo $row['loan_id']; ?>&action=reject" 
                                   class="btn-reject" 
                                   onclick="return confirm('Are you sure you want to reject this application?')">Reject</a>
                            <?php else: ?>
                                <span style="color: #7f8c8d; font-size: 13px;">No actions available</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center; padding: 40px; color: #95a5a6;">No loan records found in the system.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>