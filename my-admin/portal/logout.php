<?php
// Initialize the session context
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Unset all session variables cleanly
$_SESSION = array();

// 2. Clear the session cookie from the user's browser if it exists
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000, // Backdate expiration time to delete immediately
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Destroy the session completely on the server
session_destroy();

// 4. Force browser cache invalidation (Prevents 'Back' button exposure)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 5. Redirect the user back to your root gatepass login portal page
header("Location: index.php");
exit();
