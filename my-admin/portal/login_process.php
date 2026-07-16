<?php
// login_process.php
session_start();

include 'inc/conn.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // If the database crashes, redirect back with an error message
    header("Location: index.php?error=" . urlencode("System database terminal connectivity failure."));
    exit;
}

// 2. Intercept and Sanitize Incoming Form Post Requirements
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: index.php?error=" . urlencode("Please fill out all login credentials."));
        exit;
    }

    try {
        // 3. Query the Database for the Admin Record
        // (Assuming your users/admins table is called 'users' or 'admins' - update table name if needed)
        $stmt = $pdo->prepare("SELECT id, name, email, password, role , branch FROM admin WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // 4. Verify Identity Profile Matrix (Plain text verification)
        if ($user && $password === $user['password']) {

            // Regenerate session ID to prevent Session Fixation security vulnerabilities
            session_regenerate_id(true);

            // 5. Store safe user credentials in Session Global Array
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id']        = $user['id'];
            $_SESSION['admin_name']      = $user['name'];
            $_SESSION['admin_email']     = $user['email'];
            $_SESSION['admin_role']      = $user['role']; // e.g., 'HR', 'Security', 'Super Admin'
            $_SESSION['admin_branch']    = $user['branch']; // e.g., 'Head Office', 'Regional Office'
            // Optional: Handle a basic cookie tracking state if 'Remember Me' is clicked
            if (isset($_POST['remember'])) {
                setcookie("remember_admin_email", $email, time() + (86400 * 30), "/", "", true, true);
            }

            // Route user directly into their main workspace dashboard view
            header("Location: dashboard.php");
            exit;
        } else {
            // Generic security failure message prevents account harvesting
            header("Location: index.php?error=" . urlencode("Invalid email address or access password ."));
            exit;
        }
    } catch (PDOException $e) {
        error_log("Database Execution Error: " . $e->getMessage());
        header("Location: index.php?error=" . urlencode("An unexpected internal system processing exception occurred."));
        exit;
    }
} else {
    // If someone tries to browse directly to this URL, send them back to the login page
    header("Location: index.php");
    exit;
}
