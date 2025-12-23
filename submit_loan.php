<?php
session_start();
require_once 'config.php'; // Ensure this file exists and works!

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $amount = $_POST['amount'];
    $duration = $_POST['duration'];
    $purpose = $_POST['purpose'];

    // 1. Calculate totals
    $interest = $amount * 0.12;
    $total_payable = $amount + $interest;

    // 2. Prepare the statement
    $sql = "INSERT INTO loans (user_id, amount, duration, purpose, interest_amount, total_payable, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')";
    
    $stmt = $conn->prepare($sql);

    // 3. CHECK IF PREPARE WORKED (This prevents the Line 11 error)
    if ($stmt === false) {
        die("Database Error: " . $conn->error); 
    }

    // 4. Bind and Execute
    $stmt->bind_param("idissd", $user_id, $amount, $duration, $purpose, $interest, $total_payable);

    if ($stmt->execute()) {
        header("Location: dashboard.php?msg=success");
        exit();
    } else {
        echo "Execution failed: " . $stmt->error;
    }
}
?>