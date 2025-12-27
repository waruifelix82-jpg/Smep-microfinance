<?php
session_start();
require_once 'config.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            // Success: Set Session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $user;
            $_SESSION['role'] = 'admin';
            
            header("Location: admin_dashboard.php?login=success");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>SMEP | Admin Login</title></head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh; background:#f4f7f6; font-family:Arial;">
    <div style="background:white; padding:30px; border-radius:8px; box-shadow:0 4px 6px rgba(0,0,0,0.1); width:350px;">
        <h2 style="text-align:center;">Admin Login</h2>
        <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required style="width:100%; padding:10px; margin:10px 0;">
            <label>Password</label>
            <input type="password" name="password" required style="width:100%; padding:10px; margin:10px 0;">
            <button type="submit" style="width:100%; background:#3498db; color:white; padding:12px; border:none; cursor:pointer;">Login</button>
        </form>
        <p style="text-align:center; margin-top:15px;"><a href="admin_register.php">Need to register?</a></p>
    </div>
</body>
</html>