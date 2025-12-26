<?php
session_start();
// Include your database connection
include __DIR__ . '/../db_connect.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        // 1. Prepare statement to fetch user details
        $stmt = $conn->prepare("SELECT id, first_name, second_name, password FROM clients_info WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // 2. Verify the hashed password
            if (password_verify($password, $user['password'])) {
               
                // We store the unique database ID into the session
                $_SESSION['user_id'] = $user['id']; 
                
                // Store a friendly name for the dashboard greeting
                $_SESSION['user_name'] = $user['first_name'] . " " . $user['second_name'];

                // 3. Redirect to the dashboard
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