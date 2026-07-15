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
    <title>Hr | Equal Gate-Pass </title>

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

            <?php
            include 'inc/nav.php';
            ?>

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
                        <input class="form-control search-input" type="search" placeholder="Search users, roles, teams" aria-label="Search">
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

            <main class="dashboard-content">
                <div class="container-fluid px-3 px-lg-4 py-4">
                    <div class="page-heading">
                        <!-- Style Override layer to force brand palette consistency -->
                        <style>
                            :root {
                                --brand-color: #df2d45;
                                --brand-hover: #c42036;
                            }

                            .page-heading .page-icon {
                                background-color: rgba(223, 45, 69, 0.1) !important;
                                color: var(--brand-color) !important;
                            }

                            .page-heading .btn-brand-primary {
                                background-color: var(--brand-color) !important;
                                border-color: var(--brand-color) !important;
                                color: #ffffff !important;
                            }

                            .page-heading .btn-brand-primary:hover {
                                background-color: var(--brand-hover) !important;
                                border-color: var(--brand-hover) !important;
                                color: #ffffff !important;
                            }
                        </style>

                        <div class="page-heading-copy">
                            <!-- Icon changed to person-plus for user addition context -->
                            <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                            <div>
                                <p class="eyebrow mb-1">Internal Operations</p>
                                <h1 class="h3 mb-1">Add New HR User</h1>
                                <p class="text-muted mb-0">Create administrative accounts to manage and evaluate gate pass requests for specific office branches.</p>
                            </div>
                        </div>
                        <div class="heading-actions">

                            <a class="btn btn-outline-secondary btn-sm" href="add-hr.php">
                                <i class="bi bi-people" aria-hidden="true"></i> ADD HR
                            </a>
                        </div>
                    </div>

                    <?php
                    // 1. Establish Secure Connection Parameters
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

                        // 2. Query Total Count for HR Directory Analytics
                        $total_stmt = $pdo->query("SELECT COUNT(*) FROM hr");
                        $total_records = $total_stmt->fetchColumn();

                        // 3. Fetch HR User Records (ordered by newest onboarded members first)
                        $stmt = $pdo->query("SELECT id, name, branch, email, phone, passport, date_created FROM hr ORDER BY date_created DESC");

                        // Assigned to match the $hr_table variable loop in your frontend template code block
                        $hr_table = $stmt->fetchAll();
                    } catch (PDOException $e) {
                        echo "<div class='alert alert-danger'>Database Terminal Connectivity Error: " . htmlspecialchars($e->getMessage()) . "</div>";
                        $hr_table = [];
                        $total_records = 0;
                    }
                    ?>

                    <section class="panel mt-3 table-panel-wrapper">
                        <!-- Brand Theme Style Overrides -->
                        <style>
                            :root {
                                --brand-main: #df2d45;
                                --brand-main-hover: #c42036;
                            }

                            .table-panel-wrapper .btn-brand-action {
                                background-color: var(--brand-main) !important;
                                border-color: var(--brand-main) !important;
                                color: #ffffff !important;
                            }

                            .table-panel-wrapper .btn-brand-action:hover {
                                background-color: var(--brand-main-hover) !important;
                                border-color: var(--brand-main-hover) !important;
                                color: #ffffff !important;
                            }

                            .table-panel-wrapper .form-control:focus {
                                border-color: var(--brand-main) !important;
                                box-shadow: 0 0 0 0.25rem rgba(223, 45, 69, 0.15) !important;
                            }

                            .table-panel-wrapper .pagination .page-item.active .page-link {
                                background-color: var(--brand-main) !important;
                                border-color: var(--brand-main) !important;
                                color: #ffffff !important;
                            }

                            .table-panel-wrapper .pagination .page-link {
                                color: var(--brand-main);
                            }

                            .table-panel-wrapper .pagination .page-item.disabled .page-link {
                                color: #6c757d;
                            }

                            .table-panel-wrapper .section-title i {
                                color: var(--brand-main) !important;
                            }
                        </style>

                        <div class="panel-header">
                            <div>
                                <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i><span> HR Management Directory</span></h2>
                                <p class="text-muted mb-0">Search, view, and manage authorized system administrators across all offices.</p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <input class="form-control form-control-sm table-search" type="search" placeholder="Search HR users..." data-table-search="hrManagersTable" aria-label="Search HR users">
                                <!-- <a class="btn btn-brand-action btn-sm fw-medium" href="add-hr.php"><i class="bi bi-person-plus" aria-hidden="true"></i> Add New HR</a> -->
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0" id="hrManagersTable" data-searchable-table>
                                <thead>
                                    <tr>
                                        <th scope="col">HR Administrator Details</th>
                                        <th scope="col">Branch Location</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Date Added</th>
                                        <th scope="col" class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($hr_table)): ?>
                                        <?php foreach ($hr_table as $row): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <?php if (!empty($row['passport'])): ?>
                                                            <?php
                                                            // Strip out stray leading slashes and format the layout string uniformly
                                                            $clean_row_path = ltrim(str_replace('\\', '/', $row['passport']), '/');
                                                            ?>
                                                            <img class="avatar-img avatar-sm rounded-circle" src="<?php echo htmlspecialchars($clean_row_path, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Photo" style="width: 40px; height: 40px; object-fit: cover;" />
                                                        <?php else: ?>
                                                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white small fw-bold font-monospace" style="width:36px; height:36px; background-color: #6c757d !important;">
                                                                <?php echo htmlspecialchars(strtoupper(substr($row['name'] ?? 'HR', 0, 2))); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <p class="fw-semibold mb-0"><?php echo htmlspecialchars($row['name']); ?></p>
                                                            <p class="text-muted small mb-0">Role: HR Administrator</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="mb-0 fw-semibold text-xs" style="font-size:13px;">
                                                        <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($row['branch']); ?>
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 small text-dark"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></p>
                                                    <p class="text-muted small mb-0 font-monospace" style="font-size:11px;"><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($row['phone']); ?></p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 small text-dark font-monospace">
                                                        <i class="bi bi-calendar-check"></i> <?php echo !empty($row['date_created']) ? date('Y-m-d', strtotime($row['date_created'])) : 'N/A'; ?>
                                                    </p>
                                                </td>
                                                <td class="text-end">
                                                    <a class="btn btn-light btn-sm border" href="hr-details?id=<?php echo base64_encode($row['id']); ?>">Edit Profile</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="bi bi-people-fill fs-4 d-block mb-2"></i> No HR user records found in the directory.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3">
                            <p class="text-muted small mb-0">
                                Showing 1 to <?php echo count($hr_table); ?> of <?php echo $total_records; ?> registered HR administrators
                            </p>
                            <nav aria-label="HR manager directory pagination hierarchy">
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                </ul>
                            </nav>
                        </div>
                    </section>

                </div>
            </main>

            <?php
            include 'inc/footer.php';
            ?>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>