<?php
include "db_connect.php";

if (isset($_GET['id']) && isset($_GET['action'])) {
    $loan_id = $_GET['id'];
    $action = $_GET['action'];

    // Determine the new status
    $new_status = ($action == 'approve') ? 'approved' : 'rejected';

    // Update the database
    $stmt = $conn->prepare("UPDATE loans SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $loan_id);

    if ($stmt->execute()) {
        echo "<script>alert('Loan has been $new_status'); window.location='admin_panel.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>