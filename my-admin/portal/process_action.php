<?php
// Start the session at the absolute top of the processing script
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Core Authentication Guard Check
if (!isset($_SESSION['admin_role'])) {
    $error_message = "Access Denied: You must be logged into the gatepass portal to execute updates.";
    include('pass-details.php');
    exit;
}

// Fix for legacy PHPMailer crashing on modern PHP versions (PHP 7.4 / 8+)
if (!function_exists('get_magic_quotes_runtime')) {
    function get_magic_quotes_runtime()
    {
        return false;
    }
}

// 2. Database Connection Parameters
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
    error_log("Database Connection Error: " . $e->getMessage());
    die("Critical Error: Unable to communicate safely with the backend services.");
}

// 3. Confirm valid POST structural context payloads
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize incoming token references
    $pass_id_encoded = isset($_POST['id']) ? trim($_POST['id']) : null;

    if (empty($pass_id_encoded)) {
        $error_message = "Invalid Request Error: Missing unique record identifier index.";
        include('pass-details.php');
        exit;
    }

    // Store the original encoded ID for later use
    $original_encoded_id = $pass_id_encoded;

    // --- DECODE BASE64 ID ---
    $decoded_id = base64_decode($pass_id_encoded, true);

    // Validate that decoding was successful and result is numeric
    if ($decoded_id === false || !is_numeric($decoded_id)) {
        $error_message = "Invalid Request Error: The record identifier appears to be corrupted.";
        include('pass-details.php');
        exit;
    }

    // Cast to integer for safety
    $pass_id = (int) $decoded_id;

    // Capture user role status parameter definitions
    $user_role = strtolower($_SESSION['admin_role']);

    // 4. Branching Logic Matrix: Security Desk vs. Management Admin Desk Updates
    if ($user_role === 'security') {

        // --- SECURITY ACTION PIPELINE ---
        $time_out         = !empty($_POST['time_out']) ? trim($_POST['time_out']) : null;
        $expected_time_in = !empty($_POST['time_in']) ? trim($_POST['time_in']) : null;

        $update_sql = "UPDATE gate_passes 
                       SET time_out = :time_out, 
                           expected_time_in = :expected_time_in 
                       WHERE id = :id";

        $stmt = $pdo->prepare($update_sql);
        $stmt->execute([
            ':time_out'         => $time_out,
            ':expected_time_in' => $expected_time_in,
            ':id'               => $pass_id
        ]);
    } else {

        // --- HR / MANAGEMENT PIPELINE ---
        $approval_status = isset($_POST['approval_status']) ? trim($_POST['approval_status']) : '';
        $hr_reviewed_by  = isset($_POST['hr_reviewed_by']) ? trim($_POST['hr_reviewed_by']) : '';
        $hr_remarks      = isset($_POST['hr_remarks']) ? trim($_POST['hr_remarks']) : '';

        // Enforce validation constraints
        if (empty($approval_status) || empty($hr_reviewed_by) || empty($hr_remarks)) {
            $error_message = "Validation Error: All mandatory review processing fields must be filled out.";
            include('pass-details.php');
            exit;
        }

        // Initialize variables for ID generation and communication
        $generated_verification_id = null;
        $clean_status              = strtolower($approval_status);

        // Fetch current database records for the staff member to process ID and gather email details
        $fetch_sql = "SELECT staff_name, branch, email, verification_id FROM gate_passes WHERE id = :id LIMIT 1";
        $fetch_stmt = $pdo->prepare($fetch_sql);
        $fetch_stmt->execute([':id' => $pass_id]);
        $record = $fetch_stmt->fetch();

        if ($record) {
            $staff_name = trim($record['staff_name']);
            $branch     = trim($record['branch']);
            $staff_email = trim($record['email']); // Pulls from your verified 'email' column layout

            // --- GENERATE ID ONLY IF STATUS IS APPROVED ---
            if ($clean_status === 'approved') {
                // Keep existing ID if one is already assigned
                if (!empty($record['verification_id'])) {
                    $generated_verification_id = $record['verification_id'];
                } else {
                    // Form Initials (e.g., "John Doe" -> "JD")
                    $name_parts = explode(' ', $staff_name);
                    $initials   = '';
                    foreach ($name_parts as $part) {
                        if (!empty($part)) {
                            $initials .= strtoupper($part[0]);
                        }
                    }
                    if (empty($initials)) {
                        $initials = "GP";
                    }

                    // Clean up the Branch string
                    $clean_branch = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $branch));
                    $date_stamp   = date('Ymd');
                    $random_suffix = rand(1000, 9999);

                    // Combine elements (e.g., "JD-20260710-LAGOS-5839")
                    $generated_verification_id = $initials . "-" . $date_stamp . "-" . $clean_branch . "-" . $random_suffix;
                }
            } else {
                // For non-approved statuses, retain the existing verification_id if any
                $generated_verification_id = !empty($record['verification_id']) ? $record['verification_id'] : null;
            }
        }

        // Update operational metrics AND verification_id directly in gate_passes
        $update_sql = "UPDATE gate_passes 
                       SET approval_status = :approval_status, 
                           hr_reviewed_by  = :hr_reviewed_by, 
                           hr_remarks      = :hr_remarks,
                           verification_id = :verification_id
                       WHERE id = :id";

        $stmt = $pdo->prepare($update_sql);
        $stmt->execute([
            ':approval_status' => $approval_status,
            ':hr_reviewed_by'  => $hr_reviewed_by,
            ':hr_remarks'      => $hr_remarks,
            ':verification_id' => $generated_verification_id,
            ':id'              => $pass_id
        ]);

        // --- DYNAMIC PHPMAILER NOTIFICATION ROUTINE ---
        // Array containing all target status states requiring an email update
        $target_statuses = ['approved', 'denied', 'declined', 'request to see person'];

        if ($record && !empty($staff_email) && in_array($clean_status, $target_statuses)) {

            // Escape values safely for HTML output handling
            $staff_name_html  = htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8');
            $hr_reviewed_html = htmlspecialchars($hr_reviewed_by, ENT_QUOTES, 'UTF-8');
            $hr_remarks_html  = nl2br(htmlspecialchars($hr_remarks, ENT_QUOTES, 'UTF-8'));
            $branch_html      = htmlspecialchars($branch, ENT_QUOTES, 'UTF-8');

            try {
                /*
                |--------------------------------------------------------------------------
                | Manual PHP-Version-Safe File Includes (Matches your apply.php file)
                |--------------------------------------------------------------------------
                */
                $phpmailer_dir = __DIR__ . '/PHPMailer/';

                if (file_exists($phpmailer_dir . 'class.phpmailer.php')) {
                    require_once $phpmailer_dir . 'class.phpmailer.php';
                    require_once $phpmailer_dir . 'class.smtp.php';
                    $mail = new PHPMailer(true);
                } elseif (file_exists($phpmailer_dir . 'PHPMailer.php')) {
                    require_once $phpmailer_dir . 'Exception.php';
                    require_once $phpmailer_dir . 'PHPMailer.php';
                    require_once $phpmailer_dir . 'SMTP.php';
                    $v6_class = '\\PHPMailer\\PHPMailer\\PHPMailer';
                    $mail = new $v6_class(true);
                } else {
                    throw new Exception("PHPMailer engine files missing in path structural map: " . $phpmailer_dir);
                }

                // Mail Server Settings
                $mail->isSMTP();
                $mail->Host        = 'mail.techbyfrancis.com';
                $mail->SMTPAuth    = true;
                $mail->Username    = 'admin@techbyfrancis.com';
                $mail->Password    = 'Francis1972##';
                $mail->SMTPSecure  = 'ssl';
                $mail->Port        = 465;
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

                // Recipient Mapping & Subject Definition
                $mail->addAddress($staff_email);
                $mail->Subject = "Gate Pass Application Status Update - " . ucwords($approval_status);

                // --- GENERATE STATUS VARIABLE BLOCKS FOR THE PRE-STYLED EMAIL TEMPLATE ---
                $status_header_html = '';
                $status_callout_box = '';
                $footer_notice_text = 'Please keep your tracking verification references updated or handy when encountering terminal security checkpoints.';

                if ($clean_status === 'approved') {
                    $status_header_html = "Gate Pass Approved";
                    $status_callout_box = "
                        <tr>
                            <td style='padding: 12px 0; border-bottom: 1px solid #f1f5f9;'>
                                <span style='font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;'>Gate Pass Verification ID</span>
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #f8fafc; border: 1px solid #df2d45; border-radius: 8px; padding: 12px;'>
                                    <tr>
                                        <td style='font-family: monospace; font-size: 16px; color: #df2d45; font-weight: 800; letter-spacing: 1px;'>
                                            {$generated_verification_id}
                                        </td>
                                        <td align='right' style='font-size: 10px; font-weight: 700; color: #df2d45; text-transform: uppercase; tracking: 1px;'>
                                            PASS ACTIVE
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    ";
                    $footer_notice_text = 'Please present this active Verification ID clearly to the security front desk upon terminal exit execution.';
                } elseif ($clean_status === 'request to see person') {
                    $status_header_html = "Action Required: See HR";
                    $status_callout_box = "
                        <tr>
                            <td style='padding: 12px 0; border-bottom: 1px solid #f1f5f9;'>
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #fffbeb; border: 1px solid #f59e0b; border-radius: 8px; padding: 12px;'>
                                    <tr>
                                        <td style='font-size: 13px; color: #b45309; font-weight: 700; line-height: 1.4;'>
                                            ⚠️ Administrative Alert: Please report directly to the HR Operations Desk inside your branch at your earliest convenience to process this application.
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    ";
                } else {
                    $status_text = ($clean_status === 'declined') ? 'Declined' : 'Denied';
                    $status_header_html = "Gate Pass {$status_text}";
                    $status_callout_box = "
                        <tr>
                            <td style='padding: 12px 0; border-bottom: 1px solid #f1f5f9;'>
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #fef2f2; border: 1px solid #ef4444; border-radius: 8px; padding: 12px;'>
                                    <tr>
                                        <td style='font-size: 13px; color: #991b1b; font-weight: 600; line-height: 1.4;'>
                                            Notice: This movement application has been marked as structural tracking standard " . strtoupper($status_text) . ". If you require clarification, contact your manager.
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    ";
                }

                // --- STYLISH BRANDED EMAIL ENGINE MASTER TEMPLATE BODY Injection ---
                $mail->Body = "
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset='utf-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                        <title>Gate Pass Request Status Update</title>
                    </head>
                    <body style='margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;'>
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #f8fafc; padding: 32px 16px;'>
                            <tr>
                                <td align='center'>
                                    <!-- Main Container Card Matrix Box -->
                                    <table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 500px; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);'>
                                        
                                        <!-- Top Corporate Header Banner Block -->
                                        <tr>
                                            <td bgcolor='#df2d45' style='padding: 24px; text-align: left;'>
                                                <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                                    <tr>
                                                        <td>
                                                            <span style='display: inline-block; vertical-align: middle; width: 8px; height: 8px; background-color: #ffffff; transform: rotate(45deg); margin-right: 6px; border-radius: 2px;'></span>
                                                            <h3 style='margin: 0; display: inline-block; vertical-align: middle; font-size: 13px; font-weight: 900; color: #ffffff; text-transform: uppercase; font-family: sans-serif; letter-spacing: 2px;'>EQUAL LOGISTICS</h3>
                                                            <p style='margin: 4px 0 0 0; font-size: 10px; font-family: monospace; letter-spacing: 1px; color: #ffffff; text-transform: uppercase; opacity: 0.85;'>Assessment Processing Notification</p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                        <!-- Main Notification Message Area -->
                                        <tr>
                                            <td style='padding: 24px;'>
                                                <h1 style='margin: 0 0 8px 0; font-size: 20px; font-weight: 700; color: #0f172a;'>{$status_header_html}</h1>
                                                <p style='margin: 0 0 24px 0; font-size: 14px; line-height: 1.5; color: #475569;'>Hello <strong>{$staff_name_html}</strong>, your submitted exit request has been reviewed successfully by HR administration.</p>

                                                <!-- Meta Verification Status Block Elements -->
                                                <table border='0' cellpadding='0' cellspacing='0' width='100%' style='margin-bottom: 16px;'>
                                                    {$status_callout_box}
                                                    <tr>
                                                        <td style='padding: 12px 0; border-bottom: 1px solid #f1f5f9;'>
                                                            <span style='font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 2px;'>Assigned Office Branch</span>
                                                            <span style='font-size: 13px; font-weight: 700; color: #1e293b;'>{$branch_html}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style='padding: 12px 0; border-bottom: 1px solid #f1f5f9;'>
                                                            <span style='font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 2px;'>Reviewed & Processed By</span>
                                                            <span style='font-size: 13px; font-weight: 700; color: #1e293b;'>{$hr_reviewed_html}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style='padding: 12px 0;'>
                                                            <span style='font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; display: block; margin-bottom: 4px;'>HR Assessment Remarks / Log</span>
                                                            <div style='font-size: 13px; color: #334155; line-height: 1.5; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;'>
                                                                {$hr_remarks_html}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <!-- Strategic Instruction Advisory Note -->
                                                <p style='margin: 0; font-size: 12px; line-height: 1.4; color: #64748b; font-style: italic;'>
                                                    {$footer_notice_text}
                                                </p>
                                            </td>
                                        </tr>

                                        <!-- System Identification Signature Line Block -->
                                        <tr>
                                            <td bgcolor='#f8fafc' style='padding: 16px 24px; border-top: 1px solid #e2e8f0; font-family: monospace; font-size: 10px; color: #94a3b8;'>
                                                <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                                    <tr>
                                                        <td>EQUAL LOGISTICS SYSTEM</td>
                                                        <td align='right' style='font-weight: bold; color: #334155; letter-spacing: 1px;'>|||| ||| ||</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Footnote Sub-Text -->
                                    <p style='margin: 16px 0 0 0; font-size: 11px; color: #94a3b8; text-align: center; line-height: 1.4;'>
                                        This is a system-automated verification record from Equal Logistics gate management logs.<br>
                                        Please do not attempt direct email replies targeting this automated address.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </body>
                    </html>
                ";

                $mail->send();
            } catch (Exception $mailException) {
                // Log transmission failure without blocking code redirection loops
                error_log("PHPMailer Error Processing Pass ID {$pass_id}: " . $mailException->getMessage());
            }
        }
    }

    // 5. Set the GET parameter so pass-details.php can find the record
    $_GET['id'] = $original_encoded_id;

    // Set success message
    $success_message = "Record updated successfully.";

    // Now include pass-details.php
    include('pass-details.php');
    exit;
} else {
    header("Location: manage-passes");
    exit();
}
