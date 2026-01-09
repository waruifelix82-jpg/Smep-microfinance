<?php
require_once 'db_connect.php'; 
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $user, $hashed_pass);

        if ($stmt->execute()) {
            $message = "<div class='alert success'>Admin registered! <a href='admin_login.php'>Login here</a></div>";
        }
    } catch (mysqli_sql_exception $e) {
        // Check if the error code is 1062 (MySQL code for Duplicate Entry)
        if ($e->getCode() == 1062) {
            $message = "<div class='alert error'>Error: The username '<strong>$user</strong>' is already taken. Please choose another.</div>";
        } else {
            $message = "<div class='alert error'>An unexpected error occurred. Please try again.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMEP | Admin Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin_register.css">
</head>
<body>

    <div class="auth-card">
        <div class="logo-area">
            <h1>SMEP<span>.admin</span></h1>
            <p>Institutional Access Management</p>
        </div>

        <?php echo $message; ?>

        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username" required autocomplete="off">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit">Create Account</button>
        </form>

        <div class="footer">
            Already registered? <a href="admin_login.php">Log in</a>
        </div>
    </div>

</body>
</html>