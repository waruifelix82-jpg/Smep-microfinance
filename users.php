<?php
session_start();
require_once 'config.php';

// Handle User Deletion
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM clients_info WHERE id = $id");
    header("Location: users.php?msg=deleted");
    exit();
}

// Fetch all clients
$sql = "SELECT id, first_name, second_name, email, phone, created_at FROM clients_info ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SMEP Admin | Manage Users</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

    <div class="sidebar">
        <h2>SMEP Admin</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="view_payments.php">Payment History</a>
        <a href="users.php" class="active">Manage Users</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>User Management</h1>
            <p>View and manage registered clients</p>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #f5c6cb;">
                User has been removed successfully.
            </div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Joined Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['first_name'] . " " . $row['second_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        <td>
                            <a href="users.php?delete_id=<?php echo $row['id']; ?>" 
                               style="color: #e74c3c; text-decoration: none; font-size: 13px; font-weight: bold;" 
                               onclick="return confirm('Are you sure you want to delete this user? This cannot be undone.')">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align: center;">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>