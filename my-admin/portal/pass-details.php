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
  <title>User Details | Equal Gate-Pass </title>

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

      $pass_data = null;
      $token_id  = isset($_GET['id']) ? $_GET['id'] : '';

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
            // Fetch comprehensive row information matching decoded index
            $stmt = $pdo->prepare("SELECT * FROM gate_passes WHERE id = ? LIMIT 1");
            $stmt->execute([$raw_id]);
            $pass_data = $stmt->fetch();
          }
        } catch (PDOException $e) {
          $error_message = "Database Error: " . $e->getMessage();
        }
      }

      // Redirect or show alternative UI if the pass is invalid or not found
      if (!$pass_data) {
        echo "<div class='container mt-5'><div class='alert alert-danger'><i class='bi bi-exclamation-triangle'></i> Access Authorization Record could not be retrieved. Ensure token mapping parameters are valid. <a href='index.php' class='alert-link'>Return to Log Dashboard</a></div></div>";
        exit;
      }

      // Formulate clean conditional status configuration badges
      $status = strtolower(trim($pass_data['approval_status']));
      if ($status === 'approved' || $status === 'active') {
        $status_badge = '<span class="badge text-bg-success py-2 px-3 fs-6">Approved</span>';
      } elseif ($status === 'pending') {
        $status_badge = '<span class="badge text-bg-warning py-2 px-3 fs-6 text-dark">Pending Approval</span>';
      } elseif ($status === 'request to see person' || $status === 'hold') {
        $status_badge = '<span class="badge text-bg-info py-2 px-3 fs-6 text-dark">Awaiting Staff</span>';
      } else {
        $status_badge = '<span class="badge text-bg-danger py-2 px-3 fs-6">' . (!empty($pass_data['approval_status']) ? htmlspecialchars($pass_data['approval_status']) : 'Declined') . '</span>';
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
                <p class="text-muted mb-0">Inspect exit clearance validation, identity details, routing parameters, and HR response status logs.</p>
              </div>
            </div>

          </div>
          <!-- System Message Containers Hooked to PHP Processing Status States -->
          <?php if (!empty($success_message)): ?>
            <div class="container mt-3 mb-3">
              <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert" style="border-left: 4px solid #0f7b3e !important;">
                <div class="d-flex align-items-start gap-3">
                  <!-- Icon -->
                  <div class="flex-shrink-0 mt-1">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0f7b3e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <!-- Content -->
                  <div class="flex-grow-1">
                    <h5 class="fw-bold text-success mb-0" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                      <i class="bi bi-check-circle-fill me-1"></i> Submission Successful
                    </h5>
                    <p class="text-success-emphasis mb-0 mt-1" style="font-size: 0.875rem;">
                      <?php echo htmlspecialchars($success_message); ?>
                    </p>
                  </div>
                  <!-- Close Button -->
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php if (!empty($error_message)): ?>
            <div class="container mt-3 mb-3">
              <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert" style="border-left: 4px solid #b02a37 !important;">
                <div class="d-flex align-items-start gap-3">
                  <!-- Icon -->
                  <div class="flex-shrink-0 mt-1">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#b02a37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <!-- Content -->
                  <div class="flex-grow-1">
                    <h5 class="fw-bold text-danger mb-0" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                      <i class="bi bi-exclamation-circle-fill me-1"></i> Submission Failed
                    </h5>
                    <p class="text-danger-emphasis mb-0 mt-1" style="font-size: 0.875rem;">
                      <?php echo htmlspecialchars($error_message); ?>
                    </p>
                  </div>
                  <!-- Close Button -->
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <section class="row g-3">
            <!-- Sidebar Column Profile Card Section -->
            <div class="col-12 col-xl-4">
              <div class="panel h-100 text-center profile-card shadow-sm border p-3">


                <div class="profile-hero py-3 mt-5">
                  <?php if (!empty($pass_data['passport_photo_url'])): ?>
                    <?php
                    // Clean up any stray backslashes or leading slashes from the DB entry
                    $clean_db_path = ltrim(str_replace('\\', '/', $pass_data['passport_photo_url']), '/');

                    // Build an absolute URL path based on your working localhost structure
                    $image_src = "http://localhost/gate-pass/" . $clean_db_path;
                    ?>
                    <img class="avatar-img avatar-xl profile-photo rounded-circle border border-3 mb-3 shadow-sm"
                      src="<?php echo htmlspecialchars($image_src); ?>"
                      alt="<?php echo htmlspecialchars($pass_data['staff_name']); ?>"
                      style="width:120px; height:120px; object-fit:cover;">
                  <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white mx-auto mb-3 shadow-sm" style="width:120px; height:120px; font-size: 2.5rem; font-weight: bold;">
                      <?php
                      echo !empty($pass_data['signature_initials'])
                        ? htmlspecialchars(strtoupper($pass_data['signature_initials']))
                        : htmlspecialchars(strtoupper(substr($pass_data['staff_name'], 0, 2)));
                      ?>
                    </div>
                  <?php endif; ?>

                  <h2 class="h4 mb-1 fw-bold text-dark"><?php echo htmlspecialchars($pass_data['staff_name']); ?></h2>

                  <p class="text-muted mb-1 font-monospace small">
                    <code class="text-secondary"><?php echo htmlspecialchars($pass_data['department']); ?></code>
                  </p>

                  <!-- Added Email Node Display Element -->
                  <?php if (!empty($pass_data['email'])): ?>
                    <p class="text-muted small mb-3">
                      <i class="bi bi-envelope text-xs me-1"></i><?php echo htmlspecialchars($pass_data['email']); ?>
                    </p>
                  <?php else: ?>
                    <div class="mb-3"></div>
                  <?php endif; ?>

                  <div class="mb-2"><?php echo $status_badge; ?></div>
                </div>


                <hr class="text-muted opacity-25">

                <div class="info-list mt-2 text-start small">
                  <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted fw-medium">Verification ID:</span>
                    <strong class="font-monospace text-dark"><?php echo htmlspecialchars($pass_data['verification_id']); ?></strong>
                  </div>
                  <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted fw-medium">Log Clearance Date:</span>
                    <strong><?php echo htmlspecialchars($pass_data['pass_date']); ?></strong>
                  </div>
                  <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted fw-medium">Primary Branch Origin:</span>
                    <strong><?php echo htmlspecialchars($pass_data['branch']); ?></strong>
                  </div>
                  <div class="d-flex justify-content-between py-2 pt-2">
                    <span class="text-muted fw-medium">Signature Sign-Off:</span>
                    <strong class="font-monospace text-uppercase">[ <?php echo htmlspecialchars($pass_data['signature_initials']); ?> ]</strong>
                  </div>
                </div>
              </div>
            </div>

            <!-- Content Area Logs Breakdown Panels -->
            <div class="col-12 col-xl-8">
              <!-- Location & Logistics Metrics Panel -->
              <div class="panel mb-3 shadow-sm border p-3">
                <div class="panel-header d-flex justify-content-between align-items-center mb-3">
                  <div>
                    <h2 class="h5 mb-1 section-title"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i><span> Exit Authorization Overview</span></h2>
                    <p class="text-muted mb-0 small">Routing destinations, targeted checkpoints, and departure/return timing schedules.</p>
                  </div>
                </div>
                <div class="row g-3">
                  <div class="col-md-4">
                    <div class="mini-card p-2 border rounded bg-light-subtle">
                      <span class="text-muted d-block small mb-1">Target Destination</span>
                      <strong class="text-dark fs-6 d-block text-truncate" title="<?php echo htmlspecialchars($pass_data['destination']); ?>"><?php echo htmlspecialchars($pass_data['destination']); ?></strong>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mini-card p-2 border rounded bg-light-subtle">
                      <span class="text-muted d-block small mb-1">Time Outward</span>
                      <strong class="text-success font-monospace fs-6"><i class="bi bi-box-arrow-right"></i> <?php echo htmlspecialchars($pass_data['time_out']); ?></strong>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="mini-card p-2 border rounded bg-light-subtle">
                      <span class="text-muted d-block small mb-1">Expected Return</span>
                      <strong class="text-primary font-monospace fs-6"><i class="bi bi-box-arrow-in-left"></i> <?php echo htmlspecialchars($pass_data['expected_time_in']); ?></strong>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Purpose and Statement Details Box -->
              <div class="panel mb-3 shadow-sm border p-3">
                <div class="panel-header mb-2">
                  <h2 class="h5 mb-1 section-title"><i class="bi bi-chat-left-text-fill" aria-hidden="true"></i><span> Declared Purpose of Exit</span></h2>
                </div>
                <div class="bg-light p-3 rounded border text-secondary font-sans-serif" style="line-height: 1.6;">
                  <?php echo nl2br(htmlspecialchars($pass_data['purpose_of_exit'])); ?>
                </div>
              </div>

              <!-- NEW: Interactive HR / Security Direct Review Processing Action Panel -->
              <div class="panel mb-3 shadow-sm border p-3 bg-white">
                <div class="panel-header mb-2">
                  <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                    <span> Update Clearance Action Status</span>
                  </h2>
                  <p class="text-muted mb-0 small">
                    <?php if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'security'): ?>
                      Security Desk Mode: Record real-time gate entry and exit timestamps below.
                    <?php else: ?>
                      Select immediate gate pass resolution parameters and record validation comments.
                    <?php endif; ?>
                  </p>
                </div>

                <form action="process_action.php" method="POST" class="mt-3">
                  <!-- Pass encoded ID payload reference securely via token inputs -->
                  <input type="hidden" name="id" value="<?php echo htmlspecialchars($token_id); ?>">

                  <?php
                  // Check if current user is security
                  $is_security = (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'security');

                  // Status evaluation for dropdown selection
                  $status = strtolower(trim($pass_data['approved_status'] ?? ''));
                  ?>

                  <div class="row g-3">
                    <!-- HR Decision Status -->
                    <div class="col-md-6">
                      <label for="approved_status" class="form-label small fw-semibold text-dark">Review Action Decision</label>
                      <!-- Disabled for security so they cannot modify HR decisions -->
                      <select class="form-select form-select-sm" name="approval_status" id="approved_status" required <?php echo $is_security ? 'disabled' : ''; ?>>
                        <option value="" disabled <?php echo empty($pass_data['approval_status']) ? 'selected' : ''; ?>>-- Choose Status --</option>
                        <option value="Approved" <?php echo ($status === 'approved') ? 'selected' : ''; ?>>Approved</option>
                        <option value="Declined" <?php echo ($status === 'declined' || $status === 'denied') ? 'selected' : ''; ?>>Declined</option>
                        <option value="Request to See Person" <?php echo ($status === 'request to see person') ? 'selected' : ''; ?>>Hold - Request to See Person</option>
                      </select>
                      <?php if ($is_security): ?>
                        <!-- Hidden input preserves the value in the form submission because disabled inputs are ignored by POST -->
                        <input type="hidden" name="approval_status" value="<?php echo htmlspecialchars($pass_data['approval_status'] ?? ''); ?>">
                      <?php endif; ?>
                    </div>

                    <!-- Reviewer Identity -->
                    <div class="col-md-6">
                      <label for="hr_reviewed_by" class="form-label small fw-semibold text-dark">Reviewer Identity</label>
                      <input type="text" class="form-control form-control-sm" name="hr_reviewed_by" id="hr_reviewed_by" placeholder="e.g. HR Officer" value="<?php echo htmlspecialchars($pass_data['hr_reviewed_by'] ?? ''); ?>" required <?php echo $is_security ? 'disabled' : ''; ?>>
                      <?php if ($is_security): ?>
                        <input type="hidden" name="hr_reviewed_by" value="<?php echo htmlspecialchars($pass_data['hr_reviewed_by'] ?? ''); ?>">
                      <?php endif; ?>
                    </div>

                    <?php
                    // Check if HR has approved it (Check either approval_status or approved_status depending on DB structure)
                    $approvalState = $pass_data['approved_status'] ?? $pass_data['approval_status'] ?? '';
                    $isApproved = (strtolower(trim($approvalState)) === 'approved');
                    ?>

                    <?php if ($is_security): ?>

                      <!-- SECURITY VIEW: Actual Time Out -->
                      <div class="col-md-6">
                        <label for="time_out" class="form-label small fw-semibold text-dark">
                          Actual Time Out
                        </label>

                        <?php $isTimeOutSaved = !empty($pass_data['time_out']); ?>

                        <div class="input-group input-group-sm">
                          <input
                            type="time"
                            class="form-control"
                            name="time_out"
                            id="time_out"
                            value="<?php echo htmlspecialchars($pass_data['time_out'] ?? ''); ?>"
                            <?php
                            if (!$isApproved) {
                              echo 'disabled';
                            } elseif ($isTimeOutSaved) {
                              echo 'readonly style="background-color: #e9ecef; cursor: not-allowed;"';
                            } else {
                              echo 'required';
                            }
                            ?>>

                          <button
                            class="btn btn-outline-secondary"
                            type="button"
                            onclick="document.getElementById('time_out').value=new Date().toTimeString().slice(0,5);"
                            <?php echo ($isApproved && !$isTimeOutSaved) ? '' : 'disabled'; ?>>
                            Now
                          </button>
                        </div>

                        <?php if (!$isApproved): ?>
                          <small class="text-danger d-block mt-1">
                            <i class="bi bi-lock-fill"></i>
                            Time Out can only be recorded after HR approves this gate pass.
                          </small>
                        <?php elseif ($isTimeOutSaved): ?>
                          <small class="text-success d-block mt-1">
                            <i class="bi bi-check-circle-fill"></i>
                            Time Out recorded and locked.
                          </small>
                          <input type="hidden" name="time_out" value="<?php echo htmlspecialchars($pass_data['time_out']); ?>">
                        <?php endif; ?>
                      </div>

                      <!-- SECURITY VIEW: Actual Time In -->
                      <div class="col-md-6">
                        <label for="time_in" class="form-label small fw-semibold text-dark">
                          Actual Time In
                        </label>

                        <?php $isTimeInSaved = !empty($pass_data['expected_time_in']); ?>

                        <div class="input-group input-group-sm">
                          <input
                            type="time"
                            class="form-control"
                            name="time_in"
                            id="time_in"
                            value="<?php echo htmlspecialchars($pass_data['expected_time_in'] ?? ''); ?>"
                            <?php
                            if (!$isApproved) {
                              echo 'disabled';
                            } elseif ($isTimeInSaved) {
                              echo 'readonly style="background-color: #e9ecef; cursor: not-allowed;"';
                            }
                            ?>>

                          <button
                            class="btn btn-outline-secondary"
                            type="button"
                            onclick="document.getElementById('time_in').value=new Date().toTimeString().slice(0,5);"
                            <?php echo ($isApproved && !$isTimeInSaved) ? '' : 'disabled'; ?>>
                            Now
                          </button>
                        </div>

                        <?php if (!$isApproved): ?>
                          <small class="text-danger d-block mt-1">
                            <i class="bi bi-lock-fill"></i>
                            Time In becomes available after HR approval.
                          </small>
                        <?php elseif ($isTimeInSaved): ?>
                          <small class="text-success d-block mt-1">
                            <i class="bi bi-check-circle-fill"></i>
                            Time In recorded and locked.
                          </small>
                          <input type="hidden" name="time_in" value="<?php echo htmlspecialchars($pass_data['time_in']); ?>">
                        <?php endif; ?>
                      </div>

                    <?php else: ?>
                      <!-- HR ONLY view of the timestamps (Displays read-only to HR managers after security logs it) -->
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold text-muted">Actual Time Out (Logged by Security)</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="<?php echo !empty($pass_data['time_out']) ? htmlspecialchars($pass_data['time_out']) : 'Not departed yet'; ?>" disabled>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label small fw-semibold text-muted">Actual Time In (Logged by Security)</label>
                        <input type="text" class="form-control form-control-sm bg-light" value="<?php echo !empty($pass_data['time_in']) ? htmlspecialchars($pass_data['time_in']) : 'Not returned yet'; ?>" disabled>
                      </div>
                    <?php endif; ?>

                    <!-- HR Remarks -->
                    <div class="col-12">
                      <label for="hr_remarks" class="form-label small fw-semibold text-dark">HR Review Notes & Remarks</label>
                      <textarea class="form-control form-control-sm" name="hr_remarks" id="hr_remarks" rows="2" placeholder="Provide operational context regarding this entry check..." required <?php echo $is_security ? 'disabled' : ''; ?>><?php echo htmlspecialchars($pass_data['hr_remarks'] ?? ''); ?></textarea>
                      <?php if ($is_security): ?>
                        <input type="hidden" name="hr_remarks" value="<?php echo htmlspecialchars($pass_data['hr_remarks'] ?? ''); ?>">
                      <?php endif; ?>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 text-end">
                      <?php
                      // If user is security, and HR hasn't approved yet, OR if both timestamps are already saved, hide/disable the update button so they aren't confused
                      $disableSecurityUpdate = ($is_security && !$isApproved) || ($is_security && $isTimeOutSaved && $isTimeInSaved);
                      ?>
                      <button
                        type="submit"
                        class="btn btn-brand-primary btn-sm fw-medium"
                        <?php echo $disableSecurityUpdate ? 'disabled' : ''; ?>>
                        <i class="bi bi-save"></i> <?php echo $disableSecurityUpdate ? 'Fully Logged' : 'Update'; ?>
                      </button>
                    </div>
                  </div>
                </form>

              </div>

              <!-- HR Review Audit Logs History Overview -->
              <div class="panel shadow-sm border p-3 bg-light-subtle">
                <div class="panel-header mb-3">
                  <div>
                    <h2 class="h5 mb-1 section-title"><i class="bi bi-shield-check" aria-hidden="true"></i><span> Log File History Track</span></h2>
                  </div>
                </div>

                <div class="activity-list border-start ps-3 ms-2 position-relative">
                  <div class="activity-item pb-3 mb-2 position-relative">
                    <span class="activity-dot bg-danger position-absolute rounded-circle" style="width:10px; height:10px; left:-21px; top:6px; background-color: var(--brand-main) !important;"></span>
                    <div>
                      <p class="mb-1 fw-semibold text-dark">Active System Log State</p>
                      <p class="text-muted mb-1 small">
                        <strong>Last Updated By:</strong> <?php echo !empty($pass_data['hr_reviewed_by']) ? htmlspecialchars($pass_data['hr_reviewed_by']) : '<span class="text-muted italic small">Unassigned Entry</span>'; ?>
                      </p>
                      <div class="p-2 bg-light border rounded small mt-1">
                        <span class="text-muted d-block font-weight-bold" style="font-size:11px; text-transform: uppercase;">Active Notes Fragment:</span>
                        <p class="mb-0 text-dark italic"><?php echo !empty($pass_data['hr_remarks']) ? htmlspecialchars($pass_data['hr_remarks']) : 'No initial screening comments filed on database rows.'; ?></p>
                      </div>
                    </div>
                  </div>

                  <div class="activity-item position-relative">
                    <span class="activity-dot bg-secondary position-absolute rounded-circle" style="width:10px; height:10px; left:-21px; top:6px;"></span>
                    <div>
                      <p class="mb-1 fw-semibold text-dark">Initial Entry Created</p>
                      <p class="text-muted small mb-0 font-monospace">
                        <i class="bi bi-calendar-event"></i> System Log Time: <?php echo htmlspecialchars(date('M d, Y - h:i A', strtotime($pass_data['date_created']))); ?>
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