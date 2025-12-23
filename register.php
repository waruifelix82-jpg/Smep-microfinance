<?php
include "db_connect.php"; // Connect to the database
session_start();

$message = "";

if (isset($_POST['register'])) {
    $first_name  = trim($_POST['first_name']);
    $second_name = trim($_POST['second_name']);
    $email       = trim($_POST['email']);
    $phone    = trim($_POST['phone']); // matches your HTML input name
    $password    = trim($_POST['password']); // make sure you add this field in the form

    // Basic validation
    if (empty($first_name) || empty($second_name) || empty($email) || empty($phone) || empty($password)) {
        $message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } else {
        // Check if email already exists
        $check = $conn->prepare("SELECT id FROM clients_info WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Email already exists!";
        } else {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert into database
            $stmt = $conn->prepare("INSERT INTO clients_info (first_name, second_name, email, phone, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $first_name, $second_name, $email, $phone, $password_hash);

            if ($stmt->execute()) {
                $message = "Registration successful!";
                // Optionally redirect to login page
                // header("Location: ./LOGIN/Index.php"); exit();
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
</head>
<body>

<div class="container">
    <h2>Register</h2>

    <!-- Display message -->
    <?php if(!empty($message)): ?>
        <p style="color:red; text-align:center;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <label>First Name:</label>
        <input type="text" name="first_name" placeholder="Enter your first name" required><br><br>

        <label>Second Name:</label>
        <input type="text" name="second_name" placeholder="Enter your second name" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required><br><br>

        <label>Phone Number:</label>
        <input type="text" name="phone" placeholder="Enter your phone number" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required><br><br>

        <button type="submit" name="register">Register</button>
    </form>

    <p>Already have an account? <a href="./LOGIN/Index.php">Login here</a></p>
</div>

</body>
</html>
