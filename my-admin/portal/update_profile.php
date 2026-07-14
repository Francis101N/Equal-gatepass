<?php
// Start session for processing alerts feedback systems at the absolute top
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Authentication Guard Validation Check
if (!isset($_SESSION['admin_role']) || !isset($_SESSION['admin_id'])) {
    $_SESSION['error_message'] = "Access Denied: Please log in to edit system account parameters.";
    header("Location: login.php");
    exit;
}

// 2. Pull environment configuration map metrics
include 'inc/conn.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    die("Critical Error: Unable to connect safely to processing layers.");
}

// 3. Capture Action Payload Form Post Inputs
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve dynamic state tracking properties from active authorization tokens
    $user_id   = (int)$_SESSION['admin_id'];
    $user_role = strtolower(trim($_SESSION['admin_role'])); // 'hr' or 'security'

    // Map targets to their respective database structure tables cleanly
    $target_table = ($user_role === 'hr') ? 'hr' : 'security';

    // Capture text variations safely
    $profile_name  = trim($_POST['profile_name'] ?? '');
    $profile_email = filter_var(trim($_POST['profile_email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $new_password  = $_POST['profile_password'] ?? '';
    $conf_password = $_POST['confirm_password'] ?? '';

    // Enforce basic baseline parameter structures
    if (empty($profile_name) || !$profile_email) {
        $_SESSION['error_message'] = "Validation Error: Please fill out all structural target fields with valid formats.";
        header("Location: profile.php"); // Point back to your profile configuration template file
        exit;
    }

    try {
        // Prevent email duplication conflicts across other rows within the designated table mapping
        $dup_stmt = $pdo->prepare("SELECT id FROM {$target_table} WHERE email = ? AND id != ? LIMIT 1");
        $dup_stmt->execute([$profile_email, $user_id]);
        if ($dup_stmt->fetch()) {
            $_SESSION['error_message'] = "Conflict Error: This email address is currently allocated to another administrator asset profile registration row.";
            header("Location: profile.php");
            exit;
        }

        // Initialize our basic baseline SQL execution variables
        $update_password_string = "";
        $query_parameters = [
            ':name'  => $profile_name,
            ':email' => $profile_email,
            ':id'    => $user_id
        ];

        // 4. Validate and append password fields dynamically if the user wants to update them
        if (!empty($new_password)) {
            // Enforce explicit minimum password metrics length criteria checks
            if (strlen($new_password) < 6) {
                $_SESSION['error_message'] = "Security Exception: Your updated account password parameter length must consist of at least 6 characters.";
                header("Location: profile.php");
                exit;
            }

            // Enforce structural equality parameters matching
            if ($new_password !== $conf_password) {
                $_SESSION['error_message'] = "Validation Error: The structural passwords provided inside tracking matrices do not match.";
                header("Location: profile.php");
                exit;
            }

            // Hash the password cleanly with native high-security parameters
            $update_password_string = ", password = :password";
            $query_parameters[':password'] = password_hash($new_password, PASSWORD_DEFAULT);
        }

        // 5. Run Database Setup Writes
        $update_sql = "UPDATE {$target_table} 
                       SET name  = :name, 
                           email = :email 
                           {$update_password_string} 
                       WHERE id  = :id";

        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute($query_parameters);

        // 6. Keep layout session variables completely in sync with database records immediately
        $_SESSION['admin_name']  = $profile_name;
        $_SESSION['admin_email'] = $profile_email;

        $_SESSION['success_message'] = "Success: System authentication account records updated successfully.";
        header("Location: profile.php");
        exit;
    } catch (Exception $e) {
        error_log("Profile Infrastructure Settings Update Failure: " . $e->getMessage());
        $_SESSION['error_message'] = "Database Processing Exception: Internal database problems interrupted modifications records updates logs.";
        header("Location: profile.php");
        exit;
    }
} else {
    header("Location: profile.php");
    exit();
}
