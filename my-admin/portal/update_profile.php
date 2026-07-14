<?php
// 1. Initialize safe session tracking at the very top
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Authentication Guard Check
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role'])) {
    $_SESSION['error_message'] = "Access Denied: Please log in to complete this action.";
    header("Location: login.php");
    exit();
}

// 3. Bring in your database connection parameters
// Database Connection Parameters
$host     = 'localhost';
$db_name  = 'EQUAL-gatepass';
$username = 'EQUAL-gatepass';
$password = 'EQUAL-gatepass1972$$';  // Single quotes = no variable parsing 'inc/conn.php';

// Safe PDO connection wrapper block
if (!isset($pdo)) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        $_SESSION['error_message'] = "System Error: Connection to the database could not be established.";
        header("Location: profile.php");
        exit();
    }
}

// 4. Process ONLY on POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Capture user details from active session
    $user_id   = (int)$_SESSION['admin_id'];
    $user_role = strtolower(trim($_SESSION['admin_role'])); // 'hr' or 'security'

    // Map database table based on role
    $target_table = ($user_role === 'hr') ? 'hr' : 'security';

    // Capture and clean form inputs
    $profile_name  = trim($_POST['profile_name'] ?? '');
    $profile_email = filter_var(trim($_POST['profile_email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $new_password  = $_POST['profile_password'] ?? '';
    $conf_password = $_POST['confirm_password'] ?? '';

    // Validation check
    if (empty($profile_name)) {
        $_SESSION['error_message'] = "Validation Error: Name cannot be empty.";
        header("Location: profile.php");
        exit();
    }

    if (!$profile_email) {
        $_SESSION['error_message'] = "Validation Error: Please enter a valid email address.";
        header("Location: profile.php");
        exit();
    }

    try {
        // Check for duplicate emails
        $email_check_stmt = $pdo->prepare("SELECT id FROM {$target_table} WHERE email = ? AND id != ? LIMIT 1");
        $email_check_stmt->execute([$profile_email, $user_id]);

        if ($email_check_stmt->fetch()) {
            $_SESSION['error_message'] = "Error: This email address is already in use.";
            header("Location: profile.php");
            exit();
        }

        // Initialize SQL update parts
        $sql_password_update = "";
        $params = [
            ':name'  => $profile_name,
            ':email' => $profile_email,
            ':id'    => $user_id
        ];

        // 5. If the user typed a new password, validate it and save it raw
        if (!empty($new_password)) {
            if (strlen($new_password) < 6) {
                $_SESSION['error_message'] = "Error: Password must be at least 6 characters long.";
                header("Location: profile.php");
                exit();
            }

            if ($new_password !== $conf_password) {
                $_SESSION['error_message'] = "Error: New password and confirmation password do not match.";
                header("Location: profile.php");
                exit();
            }

            // ⚠️ WARNING: Saving the password completely raw (unhashed) as requested
            $sql_password_update = ", password = :password";
            $params[':password'] = $new_password;
        }

        // 6. Execute the update query
        $update_sql = "UPDATE {$target_table} 
                       SET name = :name, 
                           email = :email 
                           {$sql_password_update} 
                       WHERE id = :id";

        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute($params);

        // 7. Success! Sync the active session details
        $_SESSION['admin_name']  = $profile_name;
        $_SESSION['admin_email'] = $profile_email;

        $_SESSION['success_message'] = "Profile settings updated successfully!";
        header("Location: profile.php");
        exit();
    } catch (Exception $e) {
        error_log("Profile Update Failure: " . $e->getMessage());
        $_SESSION['error_message'] = "System Error: Something went wrong while saving changes.";
        header("Location: profile.php");
        exit();
    }
} else {
    header("Location: profile.php");
    exit();
}
