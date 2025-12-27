<?php
require_once 'config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    // Securely hash the password
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $user, $hashed_pass);

    if ($stmt->execute()) {
        $message = "<p style='color:green;'>Admin registered! <a href='admin_login.php'>Login here</a></p>";
    } else {
        $message = "<p style='color:red;'>Error: Username might already exist.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>SMEP | Register Admin</title></head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh; background:#f4f7f6; font-family:Arial;">
    <div style="background:white; padding:30px; border-radius:8px; box-shadow:0 4px 6px rgba(0,0,0,0.1); width:350px;">
        <h2 style="text-align:center;">Register Admin</h2>
        <?php echo $message; ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required style="width:100%; padding:10px; margin:10px 0;">
            <label>Password</label>
            <input type="password" name="password" required style="width:100%; padding:10px; margin:10px 0;">
            <button type="submit" style="width:100%; background:#2ecc71; color:white; padding:12px; border:none; cursor:pointer;">Create Account</button>
        </form>
    </div>
</body>
</html>