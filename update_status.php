<?php
// 1. Connect to the database
$mysqli = new mysqli("localhost", "root", "", "smep_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 2. Get the ID and Status from the URL
if (isset($_GET['id']) && isset($_GET['status'])) {
    $loan_id = $_GET['id'];
    $status = $_GET['status'];

    // 3. Update the database
    $stmt = $mysqli->prepare("UPDATE loans SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $loan_id);

    if ($stmt->execute()) {
        // 4. Redirect back to the dashboard
        header("Location: admin_dashboard.php?msg=updated");
        exit();
    } else {
        echo "Error updating record: " . $mysqli->error;
    }
    
    $stmt->close();
}
$mysqli->close();
?>