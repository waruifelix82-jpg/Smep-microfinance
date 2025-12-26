<?php
session_start();
require_once 'config.php';

if (isset($_GET['id']) && isset($_GET['action'])) {
    $loan_id = intval($_GET['id']);
    $action = $_GET['action'];

    // 1. Determine the status
    $new_status = ($action === 'approve') ? 'Approved' : 'Rejected';

    // 2. Prepare the statement
    $stmt = $conn->prepare("UPDATE loans SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $loan_id);

    // 3. Execute once and check if any row was actually changed
    if ($stmt->execute()) {
        // Check if the loan ID actually existed in the database
        if ($stmt->affected_rows > 0) {
            header("Location: admin_dashboard.php?msg=updated");
        } else {
            // The query ran, but no row had that ID (or it was already that status)
            header("Location: admin_dashboard.php?msg=no_change");
        }
        exit();
    } else {
        echo "Database Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>