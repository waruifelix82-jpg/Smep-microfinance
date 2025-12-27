<?php
// 1. IMPORT PHPMAILER (Make sure these files are in your 'phpmailer' folder)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

include "db_connect.php"; 
session_start();

$message = "";
$message_type = "error"; // Default to error (red)

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
                // SUCCESS! SET COLOR TO GREEN
                $message = "Registration successful! A welcome email has been sent to your inbox.";
                $message_type = "success"; 

                // --- SEND EMAIL START ---
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'waruifelix82@gmail.com'; // Your Gmail
                    $mail->Password   = 'your-app-password';       // Your 16-digit App Password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('your-smep-email@gmail.com', 'SMEP Microfinance');
                    $mail->addAddress($email); 

                    $mail->isHTML(true);
                    $mail->Subject = 'Welcome to SMEP Microfinance';
                    $mail->Body    = "<h2>Hello $first_name!</h2>
                                      <p>You have successfully registered to <b>SMEP Microfinance</b> for free.</p>
                                      <p>You can now log in to access your dashboard.</p>";
                    $mail->send();
                } catch (Exception $e) {
                    // Email failed, but DB insertion was successful.
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