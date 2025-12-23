<?php
session_start();

// Make sure the path is correct
// If connect.php is in the parent folder, this works. Otherwise, adjust.
include __DIR__ . '/../db_connect.php'; 

// Check if $conn exists after include
if (!isset($conn)) {
    die("Database connection failed. Please check your connect.php path.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if email and password are set
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        // Escape email safely
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];
// After password_verify is successful:
    $_SESSION['user_name'] = $first_name . " " . $second_name;
        // Prepare SQL statement to avoid SQL injection
        // Select the specific columns you actually have in your table
$stmt = $conn->prepare("SELECT id, first_name, second_name, password FROM clients_info WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                // Redirect to dashboard
                header("Location: ../dashboard.php");
                exit();
            } else {
                echo "Incorrect password!";
            }
        } else {
            echo "No user found with this email!";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Please enter both email and password.";
    }
}
?>
