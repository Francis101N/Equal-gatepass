<?php
// Start session for processing alerts feedback systems at the absolute top
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Authentication Guard Check
if (!isset($_SESSION['admin_role'])) {
    $_SESSION['error_message'] = "Access Denied: Unauthorized administrative session.";
    header("Location: hr-details.php");
    exit;
}

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

    $token_id = isset($_POST['id']) ? trim($_POST['id']) : '';

    if (empty($token_id)) {
        $_SESSION['error_message'] = "Validation Error: Missing structural user target references.";
        header("Location: hr-details.php");
        exit;
    }

    // Decode Base64 Token 
    $raw_id = base64_decode($token_id, true);
    if ($raw_id === false || !is_numeric($raw_id)) {
        $_SESSION['error_message'] = "Validation Error: The structural token identifier is corrupted.";
        header("Location: hr-details.php");
        exit;
    }
    $hr_id = (int)$raw_id;

    // Capture and Sanitize Text Elements
    $name   = trim($_POST['name'] ?? '');
    $branch = trim($_POST['branch'] ?? '');
    $email  = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone  = trim($_POST['phone'] ?? '');

    // Strict Field Validations
    if (empty($name) || empty($branch) || !$email || empty($phone)) {
        $_SESSION['error_message'] = "Validation Error: Please fill out all required parameters with clean structural formats.";
        header("Location: hr-details.php?id=" . urlencode($token_id));
        exit;
    }

    try {
        // Fetch existing record configuration map to preserve previous passport files if no new upload occurs
        $fetch_stmt = $pdo->prepare("SELECT passport FROM hr WHERE id = ? LIMIT 1");
        $fetch_stmt->execute([$hr_id]);
        $existing_record = $fetch_stmt->fetch();

        if (!$existing_record) {
            $_SESSION['error_message'] = "Processing Error: Targeted HR profile row index does not exist.";
            header("Location: hr-details.php?id=" . urlencode($token_id));
            exit;
        }

        $passport_path = $existing_record['passport']; // Default to current file string

        // 4. Check for New Passport File Uploads
        if (isset($_FILES['passport']) && $_FILES['passport']['error'] === UPLOAD_ERR_OK) {
            $file_tmp    = $_FILES['passport']['tmp_name'];
            $file_name   = $_FILES['passport']['name'];
            $file_size   = $_FILES['passport']['size'];

            // Validate File Content Extensions
            $allowed_mime_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $detected_mime = $finfo->file($file_tmp);

            if (!in_array($detected_mime, $allowed_mime_types)) {
                $_SESSION['error_message'] = "File Error: Invalid file format type. Only JPEG, PNG, and WebP images are supported.";
                header("Location: hr-details.php?id=" . urlencode($token_id));
                exit;
            }

            // Enforce size metric restrictions (5MB Max Limit Caps)
            if ($file_size > 5 * 1024 * 1024) {
                $_SESSION['error_message'] = "File Error: Selected file volume exceeds allowed 5MB limits.";
                header("Location: hr-details.php?id=" . urlencode($token_id));
                exit;
            }

            // Create directories safely if they don't exist
            $upload_dir = __DIR__ . '/uploads/passports/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generate clean unique filename hashes to avoid path collisions
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_filename   = 'hr_' . $hr_id . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $file_extension;
            $target_file    = $upload_dir . $new_filename;

            if (move_uploaded_file($file_tmp, $target_file)) {
                // Save database references using a clean localized format mapping relative pathing
                $passport_path = 'uploads/passports/' . $new_filename;

                // Delete old image from server storage arrays to keep disk use clean
                if (!empty($existing_record['passport'])) {
                    $old_file_full_path = __DIR__ . '/' . ltrim($existing_record['passport'], '/');
                    if (file_exists($old_file_full_path) && is_file($old_file_full_path)) {
                        @unlink($old_file_full_path);
                    }
                }
            } else {
                $_SESSION['error_message'] = "File Error: Internal systems failed to transfer file attachments onto host folders.";
                header("Location: hr-details.php?id=" . urlencode($token_id));
                exit;
            }
        }

        // 5. Execute Update Statements 
        $update_sql = "UPDATE hr 
                       SET name     = :name, 
                           branch   = :branch, 
                           email    = :email, 
                           phone    = :phone, 
                           passport = :passport 
                       WHERE id     = :id";

        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([
            ':name'     => $name,
            ':branch'   => $branch,
            ':email'    => $email,
            ':phone'    => $phone,
            ':passport' => $passport_path,
            ':id'       => $hr_id
        ]);

        $_SESSION['success_message'] = "Success: Administrative Profile details updated successfully.";
        header("Location: hr-details.php?id=" . urlencode($token_id));
        exit;
    } catch (Exception $e) {
        error_log("Profile Update Error: " . $e->getMessage());
        $_SESSION['error_message'] = "Database Error: Technical complications block profile writes.";
        header("Location: hr-details.php?id=" . urlencode($token_id));
        exit;
    }
} else {
    header("Location: hr-details.php");
    exit();
}
