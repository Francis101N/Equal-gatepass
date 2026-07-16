<?php
// apply.php

// 1. Force Localhost Error Reporting (Stops the generic HTTP 500 page from hiding errors)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fix for legacy PHPMailer crashing on modern PHP versions (PHP 7.4 / 8+)
if (!function_exists('get_magic_quotes_runtime')) {
    function get_magic_quotes_runtime()
    {
        return false;
    }
}

// 2. Database Configurations
$host     = 'localhost';
$db_name  = 'EQUAL-gatepass';
$username = 'EQUAL-gatepass';
$password = 'EQUAL-gatepass1972$$';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Initialize message variables for the front-end template
$success_message = '';
$error_message = '';

if (isset($_GET['success'])) {
    $success_message = "Gate pass submitted successfully for HR assessment.";
}
if (isset($_GET['error'])) {
    $error_message = urldecode($_GET['error']);
}

// 3. Process Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error_message)) {

    // Clean and capture text fields
    $staff_name         = trim($_POST['staff_name'] ?? '');
    $email              = trim($_POST['email'] ?? '');
    $branch             = trim($_POST['branch'] ?? '');
    $department         = trim($_POST['department'] ?? '');
    $pass_date          = trim($_POST['pass_date'] ?? '');
    $destination        = trim($_POST['destination'] ?? '');
    $purpose_of_exit    = trim($_POST['purpose_of_exit'] ?? '');
    $signature_initials = trim($_POST['signature_initials'] ?? '');

    $passport_photo_url = null;

    // Validate absolute essentials
    if (empty($staff_name) || empty($email) || empty($branch) || empty($department) || empty($pass_date) || empty($destination) || empty($purpose_of_exit) || empty($signature_initials)) {
        $error_message = "All text fields are required.";
        include('index.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please provide a valid email format.";
        include('index.php');
        exit;
    }

    // 4. Handle File Upload Safely
    if (isset($_FILES['passport_photo']) && $_FILES['passport_photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['passport_photo']['tmp_name'];
        $fileName      = $_FILES['passport_photo']['name'];
        $fileSize      = $_FILES['passport_photo']['size'];

        $fileNameCmps  = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($fileExtension, $allowedExtensions)) {
            if ($fileSize < 5000000) {
                $uploadFileDir = __DIR__ . '/uploads/';

                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                if (move_uploaded_file($fileTmpPath, $uploadFileDir . $newFileName)) {
                    $passport_photo_url = 'uploads/' . $newFileName;
                } else {
                    $error_message = "Error moving photo file.";
                    include('index.php');
                    exit;
                }
            } else {
                $error_message = "File size too large. Upload caps at 5MB.";
                include('index.php');
                exit;
            }
        } else {
            $error_message = "Invalid image extension type allowed.";
            include('index.php');
            exit;
        }
    }

    // 5. Secure Insert Execution Block
    $sql = "INSERT INTO gate_passes (
                passport_photo_url, 
                staff_name, 
                email,
                branch,
                department, 
                pass_date, 
                destination, 
                purpose_of_exit, 
                signature_initials
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $passport_photo_url,
            $staff_name,
            $email,
            $branch,
            $department,
            $pass_date,
            $destination,
            $purpose_of_exit,
            $signature_initials
        ]);

        // Dynamic Database Lookup for Branch HR User
        $hr_target_email = 'francisnwankwo1972@gmail.com';

        $hr_query = "SELECT email FROM hr WHERE branch = ? LIMIT 1";
        $hr_stmt = $pdo->prepare($hr_query);
        $hr_stmt->execute([$branch]);
        $hr_row = $hr_stmt->fetch();

        if ($hr_row && !empty($hr_row['email'])) {
            $hr_target_email = $hr_row['email'];
        }

        // Escape Data for Emails
        $staff_name_html = htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8');
        $email_html      = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $branch_html     = htmlspecialchars($branch, ENT_QUOTES, 'UTF-8');
        $dept_html       = htmlspecialchars($department, ENT_QUOTES, 'UTF-8');
        $date_html       = htmlspecialchars($pass_date, ENT_QUOTES, 'UTF-8');
        $dest_html       = htmlspecialchars($destination, ENT_QUOTES, 'UTF-8');
        $purpose_html    = nl2br(htmlspecialchars($purpose_of_exit, ENT_QUOTES, 'UTF-8'));
        $sig_html        = htmlspecialchars($signature_initials, ENT_QUOTES, 'UTF-8');

        /*
        |--------------------------------------------------------------------------
        | Manual PHP-Version-Safe File Includes
        |--------------------------------------------------------------------------
        */
        $phpmailer_dir = __DIR__ . '/PHPMailer/';

        if (file_exists($phpmailer_dir . 'class.phpmailer.php')) {
            // Version 5.x Structure (Matches your setup files)
            require_once $phpmailer_dir . 'class.phpmailer.php';
            require_once $phpmailer_dir . 'class.smtp.php';
            $mail = new PHPMailer(true);
        } elseif (file_exists($phpmailer_dir . 'PHPMailer.php')) {
            // Version 6.x Structure Fallback
            require_once $phpmailer_dir . 'Exception.php';
            require_once $phpmailer_dir . 'PHPMailer.php';
            require_once $phpmailer_dir . 'SMTP.php';
            $v6_class = '\\PHPMailer\\PHPMailer\\PHPMailer';
            $mail = new $v6_class(true);
        } else {
            die("PHPMailer files could not be found inside: " . $phpmailer_dir . ". Please check your folder contents.");
        }

        // Mail Server Settings
        $mail->isSMTP();
        $mail->Host        = 'mail.techbyfrancis.com';
        $mail->SMTPAuth    = true;
        $mail->Username    = 'admin@techbyfrancis.com';
        $mail->Password    = 'Francis1972##';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->CharSet     = 'UTF-8';
        $mail->isHTML(true);
        $mail->setFrom('admin@techbyfrancis.com', 'EQUAL Logistics Gatepass System');

        // Bypass Localhost SSL/TLS Handshake Check Failures
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true
            ]
        ];

        // Recipient Mapping
        $mail->addAddress($hr_target_email);
        $mail->Subject = "New Gate Pass Pending Review - {$staff_name_html} ({$branch_html})";

        // Setup the image inline variable block if a photo is present
        $image_html_tag = '';
        if ($passport_photo_url && file_exists(__DIR__ . '/' . $passport_photo_url)) {
            $mail->addEmbeddedImage(__DIR__ . '/' . $passport_photo_url, 'staff_passport_cid', 'passport.jpg');
            // Formatted directly as a structured img element without breaking the table container
            $image_html_tag = '<img src="cid:staff_passport_cid" alt="Profile" style="width: 64px; height: 64px; object-fit: cover; display: block;" />';
        } else {
            // High quality fallback avatar vector if profile photo isn't attached
            $image_html_tag = '<img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Profile" style="width: 44px; height: 44px; padding: 10px; opacity: 0.4; display: block;" />';
        }

        $mail->Body = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Gate Pass Authorization Request</title>
    </head>
    <body style='margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;'>
        <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #f8fafc; padding: 32px 16px;'>
            <tr>
                <td align='center'>
                    <!-- Main Container Card -->
                    <table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 500px; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);'>
                        
                        <!-- Top Header Accent -->
                        <tr>
                            <td bgcolor='#df2d45' style='padding: 24px; text-align: left;'>
                                <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                    <tr>
                                        <td>
                                            <span style='display: inline-block; vertical-align: middle; width: 8px; height: 8px; background-color: #ffffff; transform: rotate(45deg); margin-right: 6px; border-radius: 2px;'></span>
                                            <h3 style='margin: 0; display: inline-block; vertical-align: middle; font-size: 13px; font-weight: 900; color: #ffffff; text-transform: uppercase; font-family: sans-serif; letter-spacing: 2px;'>EQUAL LOGISTICS</h3>
                                            <p style='margin: 4px 0 0 0; font-size: 10px; font-family: monospace; letter-spacing: 1px; color: #ffffff; text-transform: uppercase; opacity: 0.85;'>Official Movement Authorization</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- Main Content Body -->
                        <tr>
                            <td style='padding: 24px;'>
                                <h1 style='margin: 0 0 8px 0; font-size: 20px; font-weight: 700; color: #0f172a;'>Hello HR,</h1>
                                <p style='margin: 0 0 24px 0; font-size: 14px; line-height: 1.5; color: #475569;'>A new exit authorization log has been filed and requires your immediate administrative review.</p>

                                <!-- Identity Profile Row -->
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #f8fafc; border: 1px solid #f1f5f9; border-radius: 12px; padding: 16px; margin-bottom: 20px;'>
                                    <tr>
                                        <!-- Dynamic Avatar Frame Support -->
                                        <td style='width: 64px; vertical-align: top;'>
                                            <div style='width: 64px; height: 64px; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; text-align: center;'>
                                                {$image_html_tag}
                                            </div>
                                        </td>
                                        <!-- Carrier Profile Fields -->
                                        <td style='padding-left: 16px; vertical-align: middle;'>
                                            <span style='font-size: 9px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 2px;'>Authorized Carrier</span>
                                            <h4 style='margin: 0 0 2px 0; font-size: 16px; font-weight: 700; color: #0f172a;'>{$staff_name_html}</h4>
                                            <p style='margin: 0 0 4px 0; font-size: 12px; font-family: monospace; color: #64748b;'>{$email_html}</p>
                                            <span style='font-size: 11px; font-weight: 700; color: #334155;'>{$branch_html}</span>
                                            <span style='font-size: 11px; color: #cbd5e1; margin: 0 4px;'>•</span>
                                            <span style='font-size: 11px; font-weight: 600; color: #df2d45;'>{$dept_html}</span>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Secondary Metadata Context Table -->
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' style='margin-bottom: 24px;'>
                                    <tr>
                                        <td style='padding: 8px 0; border-bottom: 1px solid #f1f5f9;'>
                                            <span style='font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 2px;'>Pass Request Date</span>
                                            <span style='font-size: 13px; font-weight: 700; color: #1e293b; font-family: monospace;'>{$date_html}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 12px 0; border-bottom: 1px solid #f1f5f9;'>
                                            <span style='font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 2px;'>Destination Point</span>
                                            <span style='font-size: 13px; font-weight: 700; color: #1e293b;'>{$dest_html}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 12px 0; border-bottom: 1px solid #f1f5f9;'>
                                            <span style='font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 2px;'>Mission / Purpose</span>
                                            <span style='font-size: 13px; color: #334155; line-height: 1.4;'>{$purpose_html}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 12px 0;'>
                                            <span style='font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;'>Staff Signature Affirmation</span>
                                            <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;'>
                                                <tr>
                                                    <td style='font-family: Georgia, serif; font-style: italic; font-size: 18px; color: #334155; font-weight: 500; letter-spacing: 2px;'>
                                                        {$sig_html}
                                                    </td>
                                                    <td align='right' style='font-family: monospace; font-size: 10px; color: #94a3b8;'>
                                                        STATUS: PENDING
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Action System Button Redirect -->
                                <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                    <tr>
                                        <td align='center' style='padding-top: 4px;'>
                                            <a href='http://localhost/gate-pass/my-admin/portal/' target='_blank' style='display: block; width: 100%; box-sizing: border-box; background-color: #df2d45; color: #ffffff; text-align: center; padding: 14px 16px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; border-radius: 8px; font-family: sans-serif;'>
                                                Open Management Console
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- System Reference Footer Flag -->
                        <tr>
                            <td bgcolor='#f8fafc' style='padding: 16px 24px; border-top: 1px solid #e2e8f0; font-family: monospace; font-size: 10px; color: #94a3b8;'>
                                <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                    <tr>
                                        <td>EQUAL LOGISTICS SYSTEM</td>
                                        <td align='right' style='font-weight: bold; color: #334155; letter-spacing: 1px;'>||| | |||| | ||</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    
                    <!-- Appended Contextual Help Text Below Card Container -->
                    <p style='margin: 16px 0 0 0; font-size: 11px; color: #94a3b8; text-align: center; line-height: 1.4;'>
                        This is an automated operational routing transmission sent directly by the security gate management matrix.<br>
                        Please do not reply directly to this notification message thread.
                    </p>
                </td>
            </tr>
        </table>
    </body>
    </html>
";
        $mail->send();

        $success_message = "Gate pass submitted successfully for HR assessment.";
        include('index.php');
        exit;
    } catch (Exception $e) {
        error_log("System Exception Encountered: " . $e->getMessage());
        $error_message = "Database updated successfully, but Mailer failed: " . $e->getMessage();
        include('index.php');
        exit;
    }
}
