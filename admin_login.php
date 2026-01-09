<?php
session_start();
require_once 'db_connect.php'; 
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $row['username'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid credentials. Please try again.";
        }
    } else {
        $error = "Admin account not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMEP | Secure Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin_login.css">
</head>
<body>

    <div class="login-wrapper">
        <div class="auth-card">
            <div class="brand-header">
                <div class="logo-box">S</div>
                <h2>SMEP<span>Microfinance</span></h2>
                <p>Administrative Gateway</p>
            </div>

            <?php if($error): ?>
                <div class="alert error-toast"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="modern-form">
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" placeholder="Enter your ID" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox"> Remember for 30 days
                    </label>
                    <a href="#" class="forgot-pass">Forgot?</a>
                </div>

                <button type="submit" class="prime-btn">Authorize Access</button>
            </form>

            <div class="auth-footer">
                <p>New to the system? <a href="admin_register.php">Create Account</a></p>
            </div>
        </div>
    </div>

</body>
</html>