<?php
// dashboard.php
session_start();

// Check if user session markers are invalid or missing entirely
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    session_destroy();
    header("Location: signin.html?error=" . urlencode("Access Denied. Please authenticate your identity parameters."));
    exit;
}

// Now you have seamless dynamic access to your admin details anywhere on the front-end page!
$current_admin_name = $_SESSION['admin_name'];
$current_admin_role = $_SESSION['admin_role'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="adminHMD professional admin dashboard template">
    <title>Security Details | Equal Gate-Pass </title>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<style>
    .profile-button {
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        gap: .70rem;
        padding: .32rem .3rem;
    }

    .profile-initials {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #de2942;
        color: #fff;
        font-weight: 700;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .profile-name {
        font-weight: 600;
        color: #212529;
        white-space: nowrap;
    }
</style>

<body>
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
            <div class="sidebar-header d-flex align-items-center justify-content-center p-3">
                <a class="brand-mark d-flex align-items-center justify-content-center w-100" href="index.php" aria-label="Equal Gate-pass dashboard">
                    <img src="../assets/images/Equaloffshorelimited.Logoalone-ezgif.com-crop.gif" alt="Equal Logistics Logo" class="h-auto max-w-[120px] w-100 object-contain">
                </a>
            </div>


            <?php include 'inc/nav.php'; ?>

            <div class="sidebar-user-panel d-flex align-items-center gap-3 p-3 my-3 m-4 rounded-4 shadow-sm"
                style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid rgba(226, 232, 240, 0.8);">

                <?php
                // 1. Safe Fallback extraction mapping 
                $session_name = !empty($_SESSION['admin_name']) ? trim($_SESSION['admin_name']) : 'Portal Admin';
                $session_role = !empty($_SESSION['admin_role']) ? trim($_SESSION['admin_role']) : 'Staff';

                // 2. Compute dynamic clean name initials layout maps (e.g., "John Doe" -> "JD")
                $name_parts = array_filter(explode(' ', $session_name));
                $initials   = '';
                foreach ($name_parts as $part) {
                    $initials .= strtoupper($part[0]);
                }
                $display_initials = !empty($initials) ? substr($initials, 0, 2) : 'AD';

                // 3. Render either the custom avatar image or the fallback premium geometric badge
                if (!empty($_SESSION['admin_passport'])):
                    $sidebar_avatar = ltrim(str_replace('\\', '/', $_SESSION['admin_passport']), '/');
                ?>
                    <div class="position-relative" style="flex-shrink: 0;">
                        <img class="avatar-img rounded-circle border border-2 border-white shadow-sm"
                            src="<?php echo htmlspecialchars($sidebar_avatar, ENT_QUOTES, 'UTF-8'); ?>"
                            alt="<?php echo htmlspecialchars($session_name, ENT_QUOTES, 'UTF-8'); ?>"
                            style="width: 46px; height: 46px; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle animate-pulse"
                            style="width: 12px; height: 12px;" title="Online"></span>
                    </div>
                <?php else: ?>
                    <div class="position-relative" style="flex-shrink: 0;">
                        <div class="d-flex align-items-center justify-content-center rounded-circle text-white shadow-sm font-monospace"
                            style="width: 46px; height: 46px; font-size: 0.95rem; font-weight: 700; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 2px solid rgba(223, 45, 69, 0.15);">
                            <?php echo htmlspecialchars($display_initials, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle"
                            style="width: 12px; height: 12px;" title="Online"></span>
                    </div>
                <?php endif; ?>

                <!-- Premium Structured Text Core Layout -->
                <div class="sidebar-user-meta overflow-hidden">
                    <strong class="d-block text-dark text-truncate"
                        style="font-size: 0.925rem; font-weight: 700; letter-spacing: -0.01em; line-height: 1.25;"
                        title="<?php echo htmlspecialchars($session_name, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($session_name, ENT_QUOTES, 'UTF-8'); ?>
                    </strong>
                    <span class="d-inline-flex align-items-center badge mt-1 text-uppercase font-monospace tracking-wider"
                        style="font-size: 0.65rem; font-weight: 700; padding: 0.25em 0.6em; border-radius: 6px; background-color: rgba(15, 23, 42, 0.06); color: #334155; letter-spacing: 0.05em;">
                        <?php echo htmlspecialchars($session_role, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
            </div>

            <div class="sidebar-footer">
                <span class="status-dot"></span>
                <span class="sidebar-footer-text">System running smoothly</span>
            </div>
        </aside>

        <div class="admin-main">
            <nav class="navbar admin-navbar navbar-expand bg-white">
                <div class="container-fluid px-3 px-lg-4">
                    <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
                        <input class="form-control search-input" type="search" placeholder="Search users, orders, reports" aria-label="Search">
                    </form>

                    <div class="navbar-actions ms-auto">
                        <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
                            <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                        </button>
                        <div class="dropdown">
                            <button class="icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                                <span class="notification-dot"></span>
                                <i class="bi bi-bell" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-menu">
                                <div class="dropdown-header fw-bold text-body">Notifications</div>
                                <a class="dropdown-item" href="users.html">
                                    <span class="notification-title">New user registered</span>
                                    <span class="notification-time">4 minutes ago</span>
                                </a>
                                <a class="dropdown-item" href="charts.html">
                                    <span class="notification-title">Revenue target reached</span>
                                    <span class="notification-time">32 minutes ago</span>
                                </a>
                                <a class="dropdown-item" href="settings.html">
                                    <span class="notification-title">Security review completed</span>
                                    <span class="notification-time">1 hour ago</span>
                                </a>
                            </div>
                        </div>

                        <div class="dropdown">
                            <?php
                            // Dynamically generate initials from the logged-in user session
                            $admin_name = $_SESSION['admin_name'] ?? 'Admin Hasan';
                            $admin_role = $_SESSION['admin_role'] ?? 'Super Admin';

                            $words = explode(" ", $admin_name);
                            $initials = "";
                            foreach ($words as $w) {
                                $initials .= strtoupper($w[0] ?? '');
                            }
                            $initials = substr($initials, 0, 2); // Keep max 2 characters
                            ?>

                            <button class="profile-button dropdown-toggle d-flex align-items-center gap-2"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">

                                <!-- Dynamic Initials Avatar -->
                                <div class="profile-initials">
                                    <?php echo htmlspecialchars($initials); ?>
                                </div>

                                <!-- Responsive Identity Text Details -->
                                <div class="text-start d-none d-md-flex flex-column lh-sm">
                                    <span class="">
                                        Admin <?php echo htmlspecialchars($admin_name); ?>
                                    </span>
                                    <span class="profile-role text-muted small" style="font-size: 11px; font-weight: 500; display: block; margin-top: 1px;">
                                        <?php echo htmlspecialchars($admin_role); ?>
                                    </span>
                                </div>

                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <!-- <li><a class="dropdown-item" href="settings.html">Account settings</a></li> -->
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a href="logout.php" class="ms-4 btn btn-outline-danger" onclick="return confirm('Are you sure you want to log out of the Gatepass portal?');">
                                        <i class="bi bi-box-arrow-right"></i> Log Out
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <?php
            include 'inc/conn.php';

            $security_data  = null;
            $token_id = isset($_GET['id']) ? $_GET['id'] : '';

            // 2. Safely Retrieve & Decode URL Parameters
            if (!empty($token_id)) {
                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]);

                    // Decode the base64 token back into its raw id integer/string value
                    $raw_id = base64_decode($token_id);

                    if ($raw_id !== false) {
                        // Fetch comprehensive row information matching decoded index from security_table
                        $stmt = $pdo->prepare("SELECT * FROM security WHERE id = ? LIMIT 1");
                        $stmt->execute([$raw_id]);
                        $security_data = $stmt->fetch();
                    }
                } catch (PDOException $e) {
                    $error_message = "Database Error: " . $e->getMessage();
                }
            }

            // Redirect or show alternative UI if the security officer profile is invalid or not found
            if (!$security_data) {
                echo "<div class='container mt-5'><div class='alert alert-danger'><i class='bi bi-exclamation-triangle'></i> Security Officer profile record could not be retrieved. Ensure token mapping parameters are valid. <a href='manage-security.php' class='alert-link'>Return to Security Directory</a></div></div>";
                exit;
            }
            ?>

            <main class="dashboard-content pass-details-wrapper">
                <!-- Brand Theme Color Rule Injections -->
                <style>
                    :root {
                        --brand-main: #df2d45;
                        --brand-main-hover: #c42036;
                    }

                    .pass-details-wrapper .btn-brand-primary {
                        background-color: var(--brand-main) !important;
                        border-color: var(--brand-main) !important;
                        color: #ffffff !important;
                    }

                    .pass-details-wrapper .btn-brand-primary:hover {
                        background-color: var(--brand-main-hover) !important;
                        border-color: var(--brand-main-hover) !important;
                        color: #ffffff !important;
                    }

                    .pass-details-wrapper .section-title i,
                    .pass-details-wrapper .page-icon i {
                        color: var(--brand-main) !important;
                    }

                    .pass-details-wrapper .form-select:focus,
                    .pass-details-wrapper .form-control:focus {
                        border-color: var(--brand-main) !important;
                        box-shadow: 0 0 0 0.25rem rgba(223, 45, 69, 0.15) !important;
                    }
                </style>

                <div class="container-fluid px-3 px-lg-4 py-4">
                    <div class="page-heading">
                        <div class="page-heading-copy">
                            <span class="page-icon"><i class="bi bi-person-lines-fill" aria-hidden="true"></i></span>
                            <div>
                                <p class="eyebrow mb-1">Authorization Details</p>
                                <h1 class="h3 mb-1">Gate Pass Logistics Clearance</h1>
                                <p class="text-muted mb-0">Inspect exit clearance validation, identity details, routing parameters, and Security response status logs.</p>
                            </div>
                        </div>

                    </div>
                    <!-- System Message Containers Hooked to PHP Processing Status States -->
                    <?php
                    // Pull messaging states safely from session maps if present after redirect loops
                    $display_success = '';
                    $display_error = '';

                    if (!empty($_SESSION['success_message'])) {
                        $display_success = $_SESSION['success_message'];
                        unset($_SESSION['success_message']); // Clear immediately so alert doesn't stick
                    }
                    if (!empty($_SESSION['error_message'])) {
                        $display_error = $_SESSION['error_message'];
                        unset($_SESSION['error_message']); // Clear immediately so alert doesn't stick
                    }
                    ?>

                    <?php if (!empty($display_success)): ?>
                        <div class="container mt-3 mb-3">
                            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert" style="border-left: 4px solid #0f7b3e !important;">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0 mt-1">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0f7b3e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold text-success mb-0" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Submission Successful
                                        </h5>
                                        <p class="text-success-emphasis mb-0 mt-1" style="font-size: 0.875rem;">
                                            <?php echo htmlspecialchars($display_success); ?>
                                        </p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($display_error)): ?>
                        <div class="container mt-3 mb-3">
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert" style="border-left: 4px solid #b02a37 !important;">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0 mt-1">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#b02a37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold text-danger mb-0" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i> Submission Failed
                                        </h5>
                                        <p class="text-danger-emphasis mb-0 mt-1" style="font-size: 0.875rem;">
                                            <?php echo htmlspecialchars($display_error); ?>
                                        </p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <section class="row g-3">
                        <!-- Sidebar Column Profile Card Section -->
                        <div class="col-12 col-xl-4">
                            <div class="panel h-100 text-center profile-card shadow-sm border p-3 bg-white">
                                <div class="profile-hero py-3 mt-4">
                                    <?php if (!empty($security_data['passport'])): ?>
                                        <?php
                                        // Clean up any stray backslashes or leading slashes from the DB entry
                                        $image_src = ltrim(str_replace('\\', '/', $security_data['passport']), '/');
                                        ?>
                                        <img class="avatar-img avatar-xl profile-photo rounded-circle border border-3 mb-3 shadow-sm"
                                            src="<?php echo htmlspecialchars($image_src, ENT_QUOTES, 'UTF-8'); ?>"
                                            alt="<?php echo htmlspecialchars($security_data['name']); ?>"
                                            style="width:120px; height:120px; object-fit:cover;">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white mx-auto mb-3 shadow-sm" style="width:120px; height:120px; font-size: 2.5rem; font-weight: bold; background-color: #6c757d !important;">
                                            <?php echo htmlspecialchars(strtoupper(substr($security_data['name'] ?? 'Security', 0, 2))); ?>
                                        </div>
                                    <?php endif; ?>

                                    <h2 class="h4 mb-1 fw-bold text-dark"><?php echo htmlspecialchars($security_data['name']); ?></h2>

                                    <p class="text-muted mb-1 font-monospace small">
                                        <code class="text-secondary">Role: Security </code>
                                    </p>

                                    <?php if (!empty($security_data['email'])): ?>
                                        <p class="text-muted small mb-1">
                                            <i class="bi bi-envelope text-xs me-1"></i><?php echo htmlspecialchars($security_data['email']); ?>
                                        </p>
                                    <?php endif; ?>
                                    <p class="text-muted small mb-1">
                                        Branch : <?php echo htmlspecialchars($security_data['branch']); ?>
                                    </p>
                                </div>

                                <hr class="text-muted opacity-25">

                                <div class="info-list mt-2 text-start small">
                                    <div class="d-flex justify-content-between py-2 border-bottom">
                                        <span class="text-muted fw-medium">Database Record ID:</span>
                                        <strong class="font-monospace text-dark">#<?php echo htmlspecialchars($security_data['id']); ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between py-2 border-bottom">
                                        <span class="text-muted fw-medium">Assigned Office Branch:</span>
                                        <strong><i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($security_data['branch']); ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between py-2 border-bottom">
                                        <span class="text-muted fw-medium">Phone Extension:</span>
                                        <strong class="font-monospace"><?php echo htmlspecialchars($security_data['phone'] ?? 'N/A'); ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between py-2 pt-2">
                                        <span class="text-muted fw-medium">Onboarding Timestamp:</span>
                                        <strong class="text-dark"><?php echo !empty($security_data['date_created']) ? date('Y-m-d', strtotime($security_data['date_created'])) : 'N/A'; ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Area Profile Management Panels -->
                        <div class="col-12 col-xl-8">
                            <!-- Interactive Administrative Profile Form Panel -->
                            <div class="panel mb-3 shadow-sm border p-3 bg-white">
                                <div class="panel-header mb-2">
                                    <h2 class="h5 mb-1 section-title">
                                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                        <span> Edit Administrative Profile Details</span>
                                    </h2>
                                    <p class="text-muted mb-0 small">
                                        Modify personnel metadata parameters, local operational branches, and core communication handles.
                                    </p>
                                </div>

                                <form action="process_security_update.php" method="POST" enctype="multipart/form-data" class="mt-3">
                                    <!-- Pass encoded ID payload reference securely via token inputs -->
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($token_id); ?>">

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="security_name" class="form-label small fw-semibold text-dark">Full Legal Name</label>
                                            <input type="text" class="form-control form-control-sm" name="name" id="security_name" value="<?php echo htmlspecialchars($security_data['name'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="security_branch" class="form-label small fw-semibold text-dark">Assigned Jurisdiction Branch</label>
                                            <input type="text" class="form-control form-control-sm" name="branch" id="security_branch" value="<?php echo htmlspecialchars($security_data['branch'] ?? ''); ?>" placeholder="e.g. Lagos Terminal" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="security_email" class="form-label small fw-semibold text-dark">Email Routing Target Address</label>
                                            <input type="email" class="form-control form-control-sm" name="email" id="security_email" value="<?php echo htmlspecialchars($security_data['email'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="security_phone" class="form-label small fw-semibold text-dark">Contact Phone Number</label>
                                            <input type="text" class="form-control form-control-sm" name="phone" id="security_phone" value="<?php echo htmlspecialchars($security_data['phone'] ?? ''); ?>" required>
                                        </div>

                                        <div class="col-12">
                                            <label for="security_passport" class="form-label small fw-semibold text-dark">Replace Passport Photo (Optional)</label>
                                            <input type="file" class="form-control form-control-sm" name="passport" id="security_passport" accept="image/*">
                                            <div class="form-text text-muted xx-small style-hint" style="font-size: 11px;">Supported image format constraints layout caps at 5MB limits.</div>
                                        </div>

                                        <div class="col-12 text-end mt-4">
                                            <a href="manage-hr.php" class="btn btn-light btn-sm border me-2">Cancel</a>
                                            <button type="submit" class="btn btn-brand-primary btn-sm fw-medium"><i class="bi bi-save"></i> Save Profile Modifications</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- HR Administration Audit History Track Panel -->
                            <div class="panel shadow-sm border p-3 bg-light-subtle">
                                <div class="panel-header mb-3">
                                    <div>
                                        <h2 class="h5 mb-1 section-title"><i class="bi bi-shield-check" aria-hidden="true"></i><span> Account Audit Status</span></h2>
                                    </div>
                                </div>

                                <div class="activity-list border-start ps-3 ms-2 position-relative">
                                    <div class="activity-item pb-3 mb-2 position-relative">
                                        <span class="activity-dot bg-success position-absolute rounded-circle" style="width:10px; height:10px; left:-21px; top:6px; background-color: #198754 !important;"></span>
                                        <div>
                                            <p class="mb-1 fw-semibold text-dark">Operational Status Flag</p>
                                            <p class="text-muted mb-0 small">
                                                This administrator profile account is verified and fully authorized to execute gate pass application choices for the <strong><?php echo htmlspecialchars($security_data['branch']); ?></strong> workstation database node maps.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="activity-item position-relative">
                                        <span class="activity-dot bg-secondary position-absolute rounded-circle" style="width:10px; height:10px; left:-21px; top:6px;"></span>
                                        <div>
                                            <p class="mb-1 fw-semibold text-dark">Profile File Creation</p>
                                            <p class="text-muted small mb-0 font-monospace">
                                                <i class="bi bi-calendar-event"></i> Initial Onboard Timestamp: <?php echo !empty($security_data['date_created']) ? htmlspecialchars(date('M d, Y - h:i A', strtotime($security_data['date_created']))) : 'N/A'; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </main>

            <?php include 'inc/footer.php'; ?>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>