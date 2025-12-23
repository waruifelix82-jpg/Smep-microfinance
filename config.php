<?php
// Database credentials
$host = "localhost";
$user = "root";      // Default for XAMPP
$pass = "";          // Default for XAMPP is empty
$dbname = "smep_db"; // Make sure this matches your database name in phpMyAdmin

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check if the connection worked
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8 to handle special characters correctly
$conn->set_charset("utf8");
?>