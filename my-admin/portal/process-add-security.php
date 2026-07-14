
<?php
// Start session for processing alerts feedback systems at the absolute top
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Authentication Guard Check (Only HR roles can register new security officers)
if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'hr') {
    $_SESSION['error_message'] = "Access Denied: Unauthorized administrative session.";
    header("Location: add-security.php");
    exit;
}

// 2. Include database structural parameters
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

// 3. Process Payload Conditions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Capture and Sanitize Text Elements
    $name   = trim($_POST['name'] ?? '');
    $branch = trim($_POST['branch'] ?? '');
    $email  = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone  = trim($_POST['phone'] ?? '');

    // Strict Field Validations
    if (empty($name) || empty($branch) || !$email || empty($phone)) {
        $_SESSION['error_message'] = "Validation Error: Please fill out all required parameters with clean structural formats.";
        header("Location: add-security.php");
        exit;
    }

    try {
        // Prevent Duplicate Entries by checking if the email is already registered in the security table
        $check_stmt = $pdo->prepare("SELECT id FROM security WHERE email = ? LIMIT 1");
        $check_stmt->execute([$email]);
        if ($check_stmt->fetch()) {
            $_SESSION['error_message'] = "Conflict Error: A security officer profile with this email address already exists.";
            header("Location: add-security.php");
            exit;
        }

        // Initialize empty baseline path layout string for optional photo parameters
        $passport_path = null;

        // 4. Check for New Passport File Uploads
        if (isset($_FILES['passport']) && $_FILES['passport']['error'] === UPLOAD_ERR_OK) {
            $file_tmp    = $_FILES['passport']['tmp_name'];
            $file_name   = $_FILES['passport']['name'];
            $file_size   = $_FILES['passport']['size'];

            // Validate File Content Extensions via finfo structure maps
            $allowed_mime_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $detected_mime = $finfo->file($file_tmp);

            if (!in_array($detected_mime, $allowed_mime_types)) {
                $_SESSION['error_message'] = "File Error: Invalid file format type. Only JPEG, PNG, and WebP images are supported.";
                header("Location: add-security.php");
                exit;
            }

            // Enforce size metric restrictions (5MB Max Limit Caps)
            if ($file_size > 5 * 1024 * 1024) {
                $_SESSION['error_message'] = "File Error: Selected file volume exceeds allowed 5MB limits.";
                header("Location: add-security.php");
                exit;
            }

            // Create directories safely if they do not exist
            $upload_dir = __DIR__ . '/uploads/passports/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generate clean unique filename hashes to avoid path collisions
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_filename   = 'security_new_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $file_extension;
            $target_file    = $upload_dir . $new_filename;

            if (move_uploaded_file($file_tmp, $target_file)) {
                // Save database references using a clean localized format mapping relative pathing
                $passport_path = 'uploads/passports/' . $new_filename;
            } else {
                $_SESSION['error_message'] = "File Error: Internal systems failed to transfer file attachments onto host folders.";
                header("Location: add-security.php");
                exit;
            }
        }

        // 5. Execute Core Data Insertion Layout Map into security table
        $insert_sql = "INSERT INTO security (name, branch, email, phone, passport) 
                       VALUES (:name, :branch, :email, :phone, :passport)";

        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->execute([
            ':name'     => $name,
            ':branch'   => $branch,
            ':email'    => $email,
            ':phone'    => $phone,
            ':passport' => $passport_path
        ]);

        $_SESSION['success_message'] = "Success: New Security Officer profile created successfully.";
        header("Location: add-security.php");
        exit;
    } catch (Exception $e) {
        error_log("Security Profile Insertion Error: " . $e->getMessage());
        $_SESSION['error_message'] = "Database Error: Technical complications block profile setup engine writes.";
        header("Location: add-security.php");
        exit;
    }
} else {
    header("Location: add-security.php");
    exit();
}
