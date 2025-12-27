<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Clear the notification if they clicked "View & Clear"
if (isset($_GET['action']) && $_GET['action'] == 'mark_read') {
    $update = $conn->prepare("UPDATE loans SET seen = 1 WHERE user_id = ? AND status = 'approved'");
    $update->bind_param("i", $user_id);
    $update->execute();
}

// 2. Fetch the loan history for THIS client
$query = "SELECT amount, purpose, status, created_at FROM loans WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Loan History</title>
    <link rel="stylesheet" href="style.css"> <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; }
        .status-approved { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
        .status-rejected { color: red; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Loan History</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Purpose</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        <td>Ksh <?php echo number_format($row['amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                        <td>
                            <span class="status-<?php echo strtolower($row['status']); ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You haven't applied for any loans yet.</p>
    <?php endif; ?>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>