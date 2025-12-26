<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Get the data from the form
    $loan_id = intval($_POST['loan_id']);
    $amount_paid = floatval($_POST['amount_paid']);
    $transaction_ref = strtoupper($conn->real_escape_string($_POST['transaction_ref']));
    $user_id = $_SESSION['user_id'];

    // 2. Insert into a "payments" table (History)
    // Note: Make sure you have a table named 'payments'
    $query_pay = "INSERT INTO payments (loan_id, user_id, amount_paid, transaction_ref, date_paid) VALUES (?, ?, ?, ?, NOW())";
    $stmt_pay = $conn->prepare($query_pay);
    $stmt_pay->bind_param("iids", $loan_id, $user_id, $amount_paid, $transaction_ref);

    if ($stmt_pay->execute()) {
        // 3. Subtract the payment from the 'total_payable' in the 'loans' table
        $query_update = "UPDATE loans SET total_payable = total_payable - ? WHERE id = ?";
        $stmt_update = $conn->prepare($query_update);
        $stmt_update->bind_param("di", $amount_paid, $loan_id);
        $stmt_update->execute();

        // 4. Check if the loan is fully paid
        $check_bal = $conn->query("SELECT total_payable FROM loans WHERE id = $loan_id")->fetch_assoc();
        if ($check_bal['total_payable'] <= 0) {
            // Set balance to 0 and mark as Cleared
            $conn->query("UPDATE loans SET total_payable = 0, status = 'Cleared' WHERE id = $loan_id");
        }

        // 5. Success! Go back to the dashboard
        header("Location: Repay_loan.php?msg=success");
        exit();
    } else {
        echo "Error recording payment: " . $conn->error;
    }
}
?>