<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
include "db_connect.php";

// 1. Find loans due in exactly 3 days
// SQL logic: Select loans where due_date = today + 3 days
$query = "SELECT l.*, c.email, c.first_name 
          FROM loans l 
          JOIN clients_info c ON l.user_id = c.id 
          WHERE l.status = 'approved' 
          AND l.due_date = DATE_ADD(CURDATE(), INTERVAL 3 DAY)";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        sendReminderEmail($row['email'], $row['first_name'], $row['amount'], $row['due_date']);
    }
}

function sendReminderEmail($toEmail, $name, $amount, $dueDate) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'waruifelix82@gmail.com'; 
        $mail->Password   = 'ypit qxnf kltw pesn'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('waruifelix82@gmail.com', 'SMEP Payments');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'SMEP Reminder: Loan Payment Due Soon';
        $mail->Body    = "
            <div style='font-family: Arial; border: 1px solid #eee; padding: 20px;'>
                <h2>Hello $name,</h2>
                <p>This is a friendly reminder that your loan payment of <b>KES " . number_format($amount) . "</b> is due on <b>$dueDate</b>.</p>
                <p>Please ensure your account is funded or pay via M-Pesa to avoid late interest charges.</p>
                <br>
                <a href='http://192.168.57.20/smep%20microfinance/login.php' style='background: #27ae60; color: white; padding: 10px; text-decoration: none;'>Pay Now</a>
            </div>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Reminder failed for $toEmail: {$mail->ErrorInfo}");
    }
}
?>ioh