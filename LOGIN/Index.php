<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

  
    <link rel="stylesheet" href="index.css">
</head>
<body>

    <div class="container">
        <h2>Login</h2>
<form method="POST" action="login_process.php">
        <form>
            <label>Email</label>
            <input type="email" name="email" 
       value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>" 
       placeholder="Enter your email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>

            <div class="buttons">
                <button type="submit" class="btn login-btn">Login</button>
                <button type="reset" class="btn clear-btn">Clear</button>
            </div>
        </form>

        <div class="register-link">
            <p>Don't have an account? <a href="../register.php">Register</a></p>
        </div>
    </div>

</body>
</html>