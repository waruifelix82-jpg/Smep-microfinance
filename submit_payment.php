<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $loan_id = $_POST['loan_id'];
    $amount_paid = $_POST['amount_paid'];
    $transaction_ref = strtoupper($_POST['transaction_ref']); // Force M-Pesa code to Uppercase
    $user_id = $_SESSION['user_id'];

    // STEP 1: Insert the payment record
    $insert_query = "INSERT INTO payments (loan_id, user_id, amount_paid, transaction_ref) VALUES (?, ?, ?, ?)";
    $stmt1 = $conn->prepare($insert_query);
    $stmt1->bind_param("iids", $loan_id, $user_id, $amount_paid, $transaction_ref);

    if ($stmt1->execute()) {
        
        // STEP 2: Subtract the amount from the loan's total_payable
        $update_query = "UPDATE loans SET total_payable = total_payable - ? WHERE id = ?";
        $stmt2 = $conn->prepare($update_query);
        $stmt2->bind_param("di", $amount_paid, $loan_id);
        $stmt2->execute();

        // STEP 3: Check if the loan is fully paid
        $check_query = "SELECT total_payable FROM loans WHERE id = ?";
        $stmt3 = $conn->prepare($check_query);
        $stmt3->bind_param("i", $loan_id);
        $stmt3->execute();
        $result = $stmt3->get_result();
        $loan_data = $result->fetch_assoc();

        if ($loan_data['total_payable'] <= 0) {
            // Mark as Settled if debt is gone
            $settle_query = "UPDATE loans SET status = 'Settled', total_payable = 0 WHERE id = ?";
            $stmt4 = $conn->prepare($settle_query);
            $stmt4->bind_param("i", $loan_id);
            $stmt4->execute();
        }

        // Send the user back to the dashboard with a success message
        header("Location: dashboard.php?msg=payment_success");
        exit();

    } else {
        echo "Error processing payment: " . $conn->error;
    }
}
?>