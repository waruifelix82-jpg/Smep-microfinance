<?php
// Start the session to gain access to the data we want to clear
session_start();

// 1. Unset all of the session variables specific to the admin
$_SESSION = array();

// 2. If it's desired to kill the session, also delete the session cookie.
// This completely cleans the browser's memory of this visit.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Finally, destroy the session.
session_destroy();

// 4. Redirect to the admin login page
header("Location: admin_login.php");
exit();
?>