<?php
// 1. IMPORT PHPMAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

include "db_connect.php"; 
session_start();

$message = "";
$message_type = "error"; 

if (isset($_POST['register'])) {
    $first_name  = trim($_POST['first_name']);
    $second_name = trim($_POST['second_name']);
    $email       = trim($_POST['email']);
    $phone       = trim($_POST['phone']); 
    $password    = trim($_POST['password']); 

    if (empty($first_name) || empty($second_name) || empty($email) || empty($phone) || empty($password)) {
        $message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } else {
        $check = $conn->prepare("SELECT id FROM clients_info WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Email already exists!";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO clients_info (first_name, second_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $first_name, $second_name, $email, $phone, $password_hash);

            if ($stmt->execute()) {
                $message = "Registration successful! Welcome email sent.";
                $message_type = "success"; 

                // --- SEND EMAIL START ---
                $mail = new PHPMailer(true);
                try {
                    // SMTP Settings
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'waruifelix82@gmail.com'; 
                    $mail->Password   = 'ypit qxnf kltw pesn'; 
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;
                    
                    // Fix for XAMPP SSL Certificate issues
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );

                    // Recipients
                    $mail->setFrom('waruifelix82@gmail.com', 'SMEP Microfinance');
                    $mail->addAddress($email); 

                    // THE FIX: Using your laptop IP so your phone can find the server
                    $login_url = "http://192.168.57.20/smep%20microfinance/login.php";

                    $mail->isHTML(true);
                    $mail->Subject = 'Welcome to SMEP Microfinance';
                    
                    $mail->Body = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px;'>
                            <h2 style='color: #2c3e50; text-align: center;'>Welcome to SMEP, $first_name!</h2>
                            <p>Your account has been created successfully. We are happy to have you join our microfinance community.</p>
                            <div style='text-align: center; margin: 25px 0;'>
                                <a href='$login_url' style='background-color: #27ae60; color: white; padding: 15px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>
                                    Login to My Dashboard
                                </a>
                            </div>
                            <p style='text-align: center; font-size: 12px; color: #7f8c8d;'>
                                If the button doesn't work, click the link below:<br>
                                <a href='$login_url'>$login_url</a>
                            </p>
                            <hr style='border: 0; border-top: 1px solid #eee; margin: 20px 0;'>
                            <p style='font-size: 11px; color: #999; text-align: center;'>SMEP Microfinance - Your Growth, Our Priority.</p>
                        </div>";

                    $mail->send();
                } catch (Exception $e) {
                    // Silently log or display error if email fails
                    $message = "Registration successful, but email failed: {$mail->ErrorInfo}";
                }
                // --- SEND EMAIL END ---

            } else {
                $message = "Registration failed: " . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title> 
    <link rel="stylesheet" href="register.css">
</head>
<body>

<div class="container">
    <h2>Register</h2>

    <?php if(!empty($message)): ?>
        <div style="padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; 
            <?php echo ($message_type == 'success') ? 
                'color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb;' : 
                'color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb;'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <label>First Name:</label>
        <input type="text" name="first_name" placeholder="Enter your first name" required>

        <label>Second Name:</label>
        <input type="text" name="second_name" placeholder="Enter your second name" required>

        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Phone Number:</label>
        <input type="text" name="phone" placeholder="Enter your phone number" required>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <button type="submit" name="register">Register</button>
    </form>

    <p>Already have an account? <a href="./LOGIN/Index.php">Login here</a></p>
</div>

<?php if($message_type == "success"): ?>
<script>
    setTimeout(function() {
        window.location.href = "./LOGIN/Index.php";
    }, 4000); // Wait 4 seconds
</script>
<?php endif; ?>

</body>
</html>