<?php
// 1. Initialize safe session tracking at the very top of execution
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Authentication Guard Check - aligned with EQUAL-gatepass workspace
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true || !isset($_SESSION['admin_id'])) {
    $_SESSION['error_message'] = "Access Denied: Please log into your admin account first.";
    header("Location: signin.html");
    exit();
}

// 3. Default database parameter values aligned with standard configs
$host     = 'localhost';
$db_name  = 'EQUAL-gatepass';
$username = 'EQUAL-gatepass';
$password = 'EQUAL-gatepass1972$$';

// Try to load external configuration if available
if (file_exists('inc/conn.php')) {
    include_once 'inc/conn.php';
}

// Establish fallback PDO instance if it wasn't already configured inside the include file
if (!isset($pdo)) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        error_log("Database initialization failure in update process: " . $e->getMessage());
        $_SESSION['error_message'] = "System Error: Unable to establish database connection.";
        header("Location: profile.php");
        exit();
    }
}

// 4. Process only if request is a POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['admin_id'];

    // Capture and clean profile text inputs
    $profile_name   = isset($_POST['profile_name']) ? trim($_POST['profile_name']) : '';
    $profile_email  = isset($_POST['profile_email']) ? filter_var(trim($_POST['profile_email']), FILTER_VALIDATE_EMAIL) : false;
    
    // Fall back to current session branch since the input is now disabled/immutable on the frontend form
    $profile_branch = isset($_POST['profile_branch']) ? trim($_POST['profile_branch']) : ($_SESSION['admin_branch'] ?? '');
    
    $new_password   = isset($_POST['profile_password']) ? $_POST['profile_password'] : '';
    $conf_password  = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Field Validation Constraints
    if (empty($profile_name)) {
        $_SESSION['error_message'] = "Validation Error: Your account name cannot be empty.";
        header("Location: profile.php");
        exit();
    }

    if (!$profile_email) {
        $_SESSION['error_message'] = "Validation Error: Please enter a valid email address.";
        header("Location: profile.php");
        exit();
    }

    if (empty($profile_branch)) {
        // Fallback to fetch existing branch designation from the database directly as a final safety net
        try {
            $branch_stmt = $pdo->prepare("SELECT branch FROM admin WHERE id = :id LIMIT 1");
            $branch_stmt->execute([':id' => $user_id]);
            $db_branch = $branch_stmt->fetchColumn();
            $profile_branch = $db_branch ? trim($db_branch) : 'Head Office';
        } catch (PDOException $e) {
            $profile_branch = 'Head Office';
        }
    }

    try {
        // Enforce email uniqueness check across other admin records to protect account integrity
        $email_check_stmt = $pdo->prepare("SELECT id FROM admin WHERE email = :email AND id != :id LIMIT 1");
        $email_check_stmt->execute([
            ':email' => $profile_email,
            ':id'    => $user_id
        ]);

        if ($email_check_stmt->fetch()) {
            $_SESSION['error_message'] = "Update Conflict: That email address is already in use by another administrator.";
            header("Location: profile.php");
            exit();
        }

        // Initialize parameterized update bindings
        $sql_password_update = "";
        $params = [
            ':name'   => $profile_name,
            ':email'  => $profile_email,
            ':branch' => $profile_branch,
            ':id'     => $user_id
        ];

        // Validate and append custom password change query elements if filled
        if (!empty($new_password)) {
            if (strlen($new_password) < 6) {
                $_SESSION['error_message'] = "Security Error: Your password must be at least 6 characters long.";
                header("Location: profile.php");
                exit();
            }

            if ($new_password !== $conf_password) {
                $_SESSION['error_message'] = "Validation Error: New password inputs do not match.";
                header("Location: profile.php");
                exit();
            }

            // Storing passwords in plaintext to align with login_process.php
            $sql_password_update = ", password = :password";
            $params[':password'] = $new_password;
        }

        // Execute DB Update on the 'admin' table
        $update_sql = "UPDATE admin 
                       SET name = :name, 
                           email = :email,
                           branch = :branch
                           {$sql_password_update} 
                       WHERE id = :id";

        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute($params);

        // Sync active state variables inside session cookies
        $_SESSION['admin_name']   = $profile_name;
        $_SESSION['admin_email']  = $profile_email;
        $_SESSION['admin_branch'] = $profile_branch;

        $_SESSION['success_message'] = "Your admin profile details have been saved successfully.";
        header("Location: profile.php");
        exit();

    } catch (PDOException $e) {
        error_log("Profile settings database update error: " . $e->getMessage());
        $_SESSION['error_message'] = "Database Exception: An unexpected backend error occurred while saving your profile data.";
        header("Location: profile.php");
        exit();
    }
} else {
    // Intercept visual direct routes and direct back to terminal
    header("Location: profile.php");
    exit();
}