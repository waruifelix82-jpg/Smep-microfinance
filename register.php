<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="javascript/script.js" defer></script>
</head>
<body>

    <form action="register_process.php" method="POST" onsubmit="return showSuccess();">
    <h2>Register</h2>
    <label>First name:</label>
    <input type="text" name="username" required><br><br>

    <label>Second name:</label>
    <input type="text" name="username" required><br><br>

    <label>Email:</label>
    <input type="email" name="email" required><br><br>

    <label>Phone Number:</label>
    <input type="text" name="text" required><br><br>

    <button type="submit">Register</button>
    <p>Already have an account? <a href="./LOGIN/Index.php">Login here</a></p>

</form>
</body>
</html>
