<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="adminHMD professional admin dashboard template">
  <title>Admin Profile | Equal Gate-Pass </title>

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

      <main class="dashboard-content">
        <!-- Inline Custom Stylesheet to Apply Website Branding Theme -->
        <style>
          :root {
            --brand-color: #df2d45;
            --brand-hover: #c42036;
          }

          /* Theme Elements Overrides */
          .dashboard-content .page-icon {
            background-color: rgba(223, 45, 69, 0.1) !important;
            color: var(--brand-color) !important;
          }

          .dashboard-content .profile-avatar {
            background-color: var(--brand-color) !important;
            color: #ffffff !important;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            font-family: monospace;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: -40px;
            position: relative;
            z-index: 2;
          }

          .dashboard-content .text-bg-brand {
            background-color: var(--brand-color) !important;
            color: #ffffff !important;
          }

          .dashboard-content .btn-brand {
            background-color: var(--brand-color) !important;
            border-color: var(--brand-color) !important;
            color: #ffffff !important;
            transition: all 0.2s ease-in-out;
          }

          .dashboard-content .btn-brand:hover,
          .dashboard-content .btn-brand:focus {
            background-color: var(--brand-hover) !important;
            border-color: var(--brand-hover) !important;
            color: #ffffff !important;
          }

          .dashboard-content .form-control:focus {
            border-color: var(--brand-color) !important;
            box-shadow: 0 0 0 0.25rem rgba(223, 45, 69, 0.15) !important;
          }

          .dashboard-content .section-title i {
            color: var(--brand-color) !important;
          }
        </style>

        <div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Account</p>
                <h1 class="h3 mb-1">Profile</h1>
                <p class="text-muted mb-0">Manage your personal details, bio, and contact preferences.</p>
              </div>
            </div>
          </div>

          <?php
          // Fallbacks for profile display if session variables are empty
          $admin_name  = $_SESSION['admin_name'] ?? 'Admin Hasan';
          $admin_email = $_SESSION['admin_email'] ?? 'admin@example.com';
          $admin_role  = $_SESSION['admin_role'] ?? 'Product Administrator';
          $admin_branch = $_SESSION['admin_branch'] ?? 'Head Office';

          $words = explode(' ', trim($admin_name));
          $initials = '';

          foreach ($words as $word) {
            if (!empty($word)) {
              $initials .= strtoupper($word[0]);
            }
          }
          $initials = substr($initials, 0, 2); // Keep neat up to 2 characters max
          ?>

          <section class="row g-3">
            <div class="col-12 col-xl-4">
              <div class="panel h-100 text-center profile-card bg-white border rounded-3 overflow-hidden shadow-sm">
                <!-- Top cover colored with your primary brand red color layout -->
                <div class="profile-cover" style="height: 100px; background-color: var(--brand-color);"></div>

                <!-- Initials Avatar Elements Layout -->
                <div class="profile-avatar">
                  <?php echo htmlspecialchars($initials); ?>
                </div>

                <h2 class="h5 mt-3 mb-1 fw-bold"><?php echo htmlspecialchars($admin_name); ?></h2>
                <p class="text-muted mb-3 small"><?php echo htmlspecialchars($admin_role); ?></p>

                <div class="d-flex justify-content-center gap-2">
                  <span class="badge text-bg-brand">Admin</span>
                  <span class="badge bg-light text-dark border">Verified</span>
                </div>

                <div class="info-list mt-4 text-start p-3 border-top">
                  <div class="py-2 border-bottom d-flex justify-content-between">
                    <span class="text-muted small">Email</span>
                    <strong class="small"><?php echo htmlspecialchars($admin_email); ?></strong>
                  </div>
                  <div class="py-2 border-bottom d-flex justify-content-between">
                    <span class="text-muted small">Role</span>
                    <strong class="small"><?php echo htmlspecialchars($admin_role); ?></strong>
                  </div>
                  <div class="py-2 border-bottom d-flex justify-content-between">
                    <span class="text-muted small">Branch</span>
                    <strong class="small"><?php echo htmlspecialchars($admin_branch); ?></strong>
                  </div>
                  <div class="py-2 d-flex justify-content-between">
                    <span class="text-muted small">Status</span>
                    <strong class="text-success small">Active Session</strong>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-xl-8">
              <form class="panel needs-validation bg-white border rounded-3 p-4 shadow-sm" method="POST" action="update_profile.php" novalidate>
                <div class="panel-header mb-4 pb-2 border-bottom">
                  <div>
                    <h2 class="h5 mb-1 section-title fw-bold d-flex align-items-center gap-2">
                      <i class="bi bi-person-gear" aria-hidden="true"></i>
                      <span>Profile Settings</span>
                    </h2>
                    <p class="text-muted mb-0 small">Update your account profile, email credentials, and login passwords.</p>

                  </div>
                </div>
                <?php if (isset($_SESSION['success_message'])): ?>
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['success_message'];
                                                                  unset($_SESSION['success_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['error_message'];
                                                                          unset($_SESSION['error_message']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                <?php endif; ?>

                <div class="row g-3">
                  <!-- Name Field -->
                  <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary" for="profileName">Name</label>
                    <input class="form-control" id="profileName" name="profile_name" type="text" value="<?php echo htmlspecialchars($admin_name ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    <div class="invalid-feedback">Name is required.</div>
                  </div>

                  <!-- Email Field -->
                  <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary" for="profileEmail">Email</label>
                    <input class="form-control" id="profileEmail" name="profile_email" type="email" value="<?php echo htmlspecialchars($admin_email ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                    <div class="invalid-feedback">Enter a valid email address.</div>
                  </div>

                  <!-- Current Role Display (Immutable Field) -->
                  <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary" for="profileRoleDisplay">Current Role Privilege</label>
                    <input class="form-control bg-light text-muted" id="profileRoleDisplay" type="text" value="<?php echo htmlspecialchars(strtoupper($admin_role ?? 'Staff'), ENT_QUOTES, 'UTF-8'); ?>" readonly disabled>
                  </div>

                  <!-- Current Branch Display (Immutable Field) -->
                  <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary" for="profileBranchDisplay">Current Branch</label>
                    <input class="form-control bg-light text-muted" id="profileBranchDisplay" type="text" value="<?php echo htmlspecialchars($admin_branch ?? 'Head Office', ENT_QUOTES, 'UTF-8'); ?>" readonly disabled>
                  </div>

                  <!-- Divider for Password Management Section -->
                  <div class="col-12 my-3">
                    <hr class="text-slate opacity-10">
                    <p class="text-dark fw-bold small mb-1"><i class="bi bi-shield-lock me-1"></i> Security Credentials</p>
                    <span class="text-muted d-block" style="font-size: 0.75rem;">Leave password inputs blank if you do not want to alter your current dashboard security passcode entries.</span>
                  </div>

                  <!-- New Password Input -->
                  <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary" for="profilePassword">New Password</label>
                    <input class="form-control" id="profilePassword" name="profile_password" type="password" placeholder="••••••••" minlength="6">
                    <div class="invalid-feedback">Password must be at least 6 characters long.</div>
                  </div>

                  <!-- Confirm New Password Input -->
                  <div class="col-md-6">
                    <label class="form-label small fw-semibold text-secondary" for="confirmPassword">Confirm New Password</label>
                    <input class="form-control" id="confirmPassword" name="confirm_password" type="password" placeholder="••••••••">
                    <div class="invalid-feedback">Please ensure entry fields match completely.</div>
                  </div>
                </div>

                <!-- Actions Footer Bar Group -->
                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                  <button class="btn btn-brand px-4 py-2 rounded-3 d-flex align-items-center gap-2 fw-medium" type="submit" style="background-color: #df2d45; color:#fff; border:none;">
                    <i class="bi bi-check2-circle" aria-hidden="true"></i> Save Profile Details
                  </button>
                </div>
              </form>
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