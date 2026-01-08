<?php
include "db_connect.php";

// 1. DEFINE the string first (make sure column names match your DB)
$query = "SELECT l.id, l.amount, c.first_name, c.second_name 
          FROM loans l 
          JOIN clients_info c ON l.user_id = c.id 
          WHERE l.status = 'pending'";

// 2. NOW execute the query
$result = $conn->query($query); 

// 3. Check if the query actually worked
if (!$result) {
    die("Query Failed: " . $conn->error);
}
?>
<h2>Admin Loan Review Queue</h2>
<table border="1" cellpadding="10">
    <tr>
        <th>Client Name</th>
        <th>Amount</th>
        <th>Loan Type</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['first_name'] . " " . $row['second_name']; ?></td>
        <td>Ksh <?php echo number_format($row['amount']); ?></td>
        <td><?php echo $row['loan_type']; ?></td>
        <td>
            <a href="update_loan.php?id=<?php echo $row['id']; ?>&action=approve" 
               style="color: green; font-weight: bold;">Approve</a> | 
            
            <a href="update_loan.php?id=<?php echo $row['id']; ?>&action=reject" 
               style="color: red;">Reject</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>