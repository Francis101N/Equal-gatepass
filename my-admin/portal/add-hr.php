<?php
// dashboard.php
session_start();

// Check if user session markers are invalid or missing entirely
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    session_destroy();
    header("Location: signin.html?error=" . urlencode("Access Denied. Please authenticate your identity parameters."));
    exit;
}

// Pull messaging states safely from session maps if present after redirect processing loops
$display_success = '';
$display_error = '';

if (!empty($_SESSION['success_message'])) {
    $display_success = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['error_message'])) {
    $display_error = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="adminHMD professional admin dashboard template">
    <title>Add Hr | Equal Gate-Pass </title>

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


    body {
        font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
        background-color: #f8fafc;
        color: #0f172a;
    }

    .brand-text-accent {
        color: #df2d45;
    }

    .bg-brand-accent {
        background-color: #df2d45 !important;
    }

    .btn-brand-submit {
        background-color: #df2d45;
        color: #ffffff;
        font-weight: 600;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-brand-submit:hover {
        background-color: #be2237;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(223, 45, 69, 0.2);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #df2d45;
        box-shadow: 0 0 0 0.25rem rgba(223, 45, 69, 0.15);
    }

    .profile-upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        background-color: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .profile-upload-zone:hover {
        border-color: #df2d45;
        background-color: #fff5f6;
    }

    .avatar-preview-container {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        display: none;
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

            <!-- Notification Engine Alerts System Injector -->
            <div class="container mt-4">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-xl-7 p-0">
                        <?php if (!empty($display_success)): ?>
                            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert" style="border-left: 4px solid #0f7b3e !important;">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0 mt-1">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0f7b3e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold text-success mb-0" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Operations Success
                                        </h5>
                                        <p class="text-success-emphasis mb-0 mt-1" style="font-size: 0.875rem;">
                                            <?php echo htmlspecialchars($display_success); ?>
                                        </p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($display_error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert" style="border-left: 4px solid #b02a37 !important;">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0 mt-1">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#b02a37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold text-danger mb-0" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i> Request Execution Interrupted
                                        </h5>
                                        <p class="text-danger-emphasis mb-0 mt-1" style="font-size: 0.875rem;">
                                            <?php echo htmlspecialchars($display_error); ?>
                                        </p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Main Registration Form Layout Blueprint Container Section -->
            <div class="container mb-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-xl-7">
                        <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
                            <div class="card-body p-4 p-md-5">
                                <div class="mb-4">
                                    <h2 class="fw-bold text-dark tracking-tight mb-1">Add HR Administrator</h2>
                                    <p class="text-muted small">Register new authorized Human Resources profiles .</p>
                                </div>

                                <hr class="text-slate opacity-10 mb-4">

                                <!-- Action targets your processing engine file. Change to form handler path if decoupled -->
                                <form action="process-add-hr.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

                                    <!-- Passport Photo Dynamic Drag/Upload Node Layer -->
                                    <div class="mb-4 text-center">
                                        <label class="form-label d-block fw-semibold text-start text-dark mb-2">Profile Passport Photo</label>
                                        <div class="profile-upload-zone" onclick="document.getElementById('passportInput').click();">
                                            <div id="uploadPlaceholder">
                                                <i class="bi bi-cloud-arrow-up display-6 brand-text-accent"></i>
                                                <p class="fw-medium small mt-2 mb-1">Click or drag profile photo here</p>
                                                <p class="text-muted" style="font-size: 0.75rem;">Supports clean JPEG, PNG, or WebP up to 5MB metrics</p>
                                            </div>
                                            <div class="d-flex justify-content-center align-items-center flex-column">
                                                <img id="avatarPreview" src="#" alt="Upload Avatar Preview" class="avatar-preview-container mb-2">
                                                <span id="fileNameDisplay" class="badge bg-secondary text-wrap d-none" style="max-width: 250px;"></span>
                                            </div>
                                            <input type="file" name="passport" id="passportInput" class="d-none" accept="image/jpeg, image/png, image/jpg, image/webp">
                                        </div>
                                    </div>

                                    <!-- Structural Input Form Matrix Blocks -->
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="name" class="form-label fw-semibold text-dark small">Full Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                                <input type="text" class="form-control border-start-0 bg-light" id="name" name="name" placeholder="e.g. Jane Doe" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="branch" class="form-label fw-semibold text-dark small">Assigned Corporate Office Branch</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-building"></i></span>
                                                <select class="form-select border-start-0 bg-light" id="branch" name="branch" required>
                                                    <option value="" selected disabled>Select assigned structural branch...</option>
                                                    <option value="Omisore">Omisore</option>
                                                    <option value="Fingesi">Fingesi</option>
                                                    <option value="Apapa">Apapa</option>
                                                    <!-- <option value="Kano Logistics Terminal">Kano Logistics Terminal</option> -->
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email" class="form-label fw-semibold text-dark small">Corporate Email Address</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                                <input type="email" class="form-control border-start-0 bg-light" id="email" name="email" placeholder="jane.doe@techbyfrancis.com" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="phone" class="form-label fw-semibold text-dark small">Official Phone Line Contact</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                                <input type="tel" class="form-control border-start-0 bg-light" id="phone" name="phone" placeholder="e.g. +234 803 123 4567" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <button type="submit" class="btn btn-brand-submit w-100 py-3 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                            <i class="bi bi-shield-check-fill"></i> Save HR Record Profile
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <?php
            include 'inc/footer.php';
            ?>
        </div>
    </div>

    <script>
        // Real-time Upload File Validation & Dynamic Image Rendering Pipeline
        document.getElementById('passportInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('avatarPreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            const nameBadge = document.getElementById('fileNameDisplay');

            if (file) {
                // Enforce maximum structural limits on client side before hitting backend limits (5MB Cap)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Structural File Size Limitation Triggered: The attached payload volume breaks maximum permissible 5MB parameters.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                    nameBadge.textContent = file.name;
                    nameBadge.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // Strict Native Layout Form Submissions Controls
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>

</html>