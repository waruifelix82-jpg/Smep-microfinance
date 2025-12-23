<?php
session_start();
include 'db_connect.php'; // Ensure you have your database connection file

// 1. Security Check: Ensure only logged-in users can submit
if (!isset($_SESSION['user_id'])) {
    header("Location: ./LOGIN/Index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Collect data from the form
    $user_id  = $_SESSION['user_id'];
    $amount   = mysqli_real_escape_string($conn, $_POST['amount']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $purpose  = mysqli_real_escape_string($conn, $_POST['purpose']);
    
    // 3. Simple Interest Calculation (Logic must match your frontend)
    $interest_rate = 0.12; // 12%
    $interest_amount = $amount * $interest_rate;
    $total_repayable = $amount + $interest_amount;
    
    // 4. Set Initial Status
    $status = "Pending";

    // 5. Insert into Database
    $sql = "INSERT INTO loans (user_id, amount, duration, interest, total_repayable, purpose, status) 
            VALUES ('$user_id', '$amount', '$duration', '$interest_amount', '$total_repayable', '$purpose', '$status')";

    if (mysqli_query($conn, $sql)) {
        // Success! Redirect to User Dashboard with a success message
        header("Location: user_dashboard.php?status=success");
        exit();
    } else {
        // Error handling
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>