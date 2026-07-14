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
  <title>Requests | Equal Gate-Pass </title>

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
              <span class="page-icon"><i class="bi bi-card-checklist" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Terminal Logistics</p>
                <h1 class="h3 mb-1">Gate-Pass Requests</h1>
                <p class="text-muted mb-0">Review real-time visitor passes, active outside personnel, and security entry logs.</p>
              </div>
            </div>
            <!-- <div class="heading-actions">
              <a class="btn btn-outline-secondary btn-sm" href="tables.html"><i class="bi bi-download" aria-hidden="true"></i> Export Logs</a>

            </div> -->
          </div>

          <?php
          // 1. Include database structural parameters
          include 'inc/conn.php';

          try {
            // 2. Instantiate PDO layer connection mapping parameters
            $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password, [
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
              PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            // 3. Count Pending HR Reviews (Assuming status = 'pending')
            $stmt_pending = $pdo->query("SELECT COUNT(*) FROM gate_passes WHERE approval_status = 'PENDING'");
            $count_pending = $stmt_pending->fetchColumn();

            // 4. Count Active Passes Out (Personnel currently outside who have NOT checked back in yet)
            $stmt_active = $pdo->query("SELECT COUNT(*) FROM gate_passes WHERE time_out IS NOT NULL AND time_out != '' AND (expected_time_in IS NULL OR expected_time_in = '')");
            $count_active = $stmt_active->fetchColumn();

            // 5. Count Total Logs Registered Today (Filter logs by current server date)
            $stmt_today = $pdo->query("SELECT COUNT(*) FROM gate_passes WHERE DATE(date_created) = CURRENT_DATE");
            $count_today = $stmt_today->fetchColumn();

            // 6. Count Declined / Revoked Passes (Assuming status = 'declined' or 'rejected')
            $stmt_declined = $pdo->query("SELECT COUNT(*) FROM gate_passes WHERE approval_status = 'Declined'");
            $count_declined = $stmt_declined->fetchColumn();
          } catch (Exception $e) {
            error_log("Dashboard Counter Core Aggregation Error: " . $e->getMessage());
            // Fallbacks to prevent application crashes on view load
            $count_pending  = 0;
            $count_active   = 0;
            $count_today    = 0;
            $count_declined = 0;
          }
          ?>

          <!-- Grid metrics layout forced to be mathematically identical in structural sizing widths/heights -->
          <section class="row g-3 mt-1 align-items-stretch" aria-label="Dashboard metrics">
            <!-- Pending Approvals -->
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
              <article class="metric-card metric-primary w-full w-100 h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="metric-top">
                    <span class="metric-label">Pending HR Review</span>
                    <span class="metric-icon"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
                  </div>
                  <div class="metric-value"><?php echo number_format($count_pending); ?></div>
                </div>
                <div class="metric-meta mt-auto">
                  <span class="text-warning fw-semibold">Requires Action</span>
                  <span>awaiting sign-off</span>
                </div>
              </article>
            </div>

            <!-- Active Outside Personnel -->
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
              <article class="metric-card metric-success w-full w-100 h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="metric-top">
                    <span class="metric-label">Active Passes (Out)</span>
                    <span class="metric-icon"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></span>
                  </div>
                  <div class="metric-value"><?php echo number_format($count_active); ?></div>
                </div>
                <div class="metric-meta mt-auto">
                  <span class="text-success fw-semibold">Personnel Outside</span>
                  <span>awaiting return check</span>
                </div>
              </article>
            </div>

            <!-- Approved/Completed Today -->
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
              <article class="metric-card metric-warning w-full w-100 h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="metric-top">
                    <span class="metric-label">Total Logs Today</span>
                    <span class="metric-icon"><i class="bi bi-journal-check" aria-hidden="true"></i></span>
                  </div>
                  <div class="metric-value"><?php echo number_format($count_today); ?></div>
                </div>
                <div class="metric-meta mt-auto">
                  <span class="text-success fw-semibold">Live Feed</span>
                  <span>registered today</span>
                </div>
              </article>
            </div>

            <!-- Rejected / Flagged Requests -->
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
              <article class="metric-card metric-danger w-full w-100 h-100 d-flex flex-column justify-content-between">
                <div>
                  <div class="metric-top">
                    <span class="metric-label">Declined / Revoked</span>
                    <span class="metric-icon"><i class="bi bi-shield-x" aria-hidden="true"></i></span>
                  </div>
                  <div class="metric-value"><?php echo number_format($count_declined); ?></div>
                </div>
                <div class="metric-meta mt-auto">
                  <span class="text-danger fw-semibold">Security Flag</span>
                  <span>failed verification</span>
                </div>
              </article>
            </div>
          </section>

          <br>
          <?php

          try {
            $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password, [
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
              PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            // 2. Query Total Count for Database Summary Analytics
            $total_stmt = $pdo->query("SELECT COUNT(*) FROM gate_passes");
            $total_records = $total_stmt->fetchColumn();

            // 3. Fetch Gate Pass Logs (ordered by newest additions first)
            $stmt = $pdo->query("SELECT id, verification_id, passport_photo_url, staff_name, department, pass_date, destination, branch, purpose_of_exit, time_out, expected_time_in, signature_initials, approval_status, hr_reviewed_by, hr_remarks, date_created FROM gate_passes ORDER BY date_created DESC");
            $gate_passes = $stmt->fetchAll();
          } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Database Terminal Connectivity Error: " . htmlspecialchars($e->getMessage()) . "</div>";
            $gate_passes = [];
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
                <h2 class="h5 mb-1 section-title"><i class="bi bi-table" aria-hidden="true"></i><span> Gate-Pass Requests Log</span></h2>
                <p class="text-muted mb-0">Search, review, and manage active terminal pass clearances.</p>
              </div>
              <div class="d-flex flex-wrap gap-2">
                <input class="form-control form-control-sm table-search" type="search" placeholder="Search gate passes..." data-table-search="gatePassesTable" aria-label="Search gate passes">
                <!-- <a class="btn btn-brand-action btn-sm fw-medium" href="add-user.html"><i class="bi bi-plus-circle" aria-hidden="true"></i> Issue New Pass</a> -->
              </div>
            </div>

            <div class="table-responsive">
              <table class="table align-middle mb-0" id="gatePassesTable" data-searchable-table>
                <thead>
                  <tr>
                    <th scope="col">Personnel Details</th>
                    <th scope="col">Verification ID</th>
                    <th scope="col">Destination / Branch</th>
                    <th scope="col">Purpose / Timing</th>
                    <th scope="col">HR Review Status</th>
                    <th scope="col" class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($gate_passes)): ?>
                    <?php foreach ($gate_passes as $row):
                      // Process the Status Badges
                      $status = strtolower(trim($row['approval_status']));

                      if ($status === 'approved' || $status === 'active') {
                        // Approved color remains Green (Success)
                        $status_badge = '<span class="badge text-bg-success">Approved</span>';
                      } elseif ($status === 'pending') {
                        // Pending remains Yellow/Orange (Warning)
                        $status_badge = '<span class="badge text-bg-warning">Pending</span>';
                      } elseif ($status === 'Declined' || $status === 'denied') {
                        // Declined & Denied updated to Yellow/Orange (Warning)
                        $status_badge = '<span class="badge text-bg-warning">' . htmlspecialchars($row['approval_status']) . '</span>';
                      } elseif ($status === 'request to see person') {
                        // Request to See Person updated to Light Blue (Info)
                        $status_badge = '<span class="badge text-bg-info">Request to See Person</span>';
                      } else {
                        // Fallback for empty or unique alternative states (Red Danger)
                        $status_badge = '<span class="badge text-bg-danger">' . (!empty($row['approval_status']) ? htmlspecialchars($row['approval_status']) : 'Declined') . '</span>';
                      }
                    ?>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center gap-2">
                            <?php if (!empty($row['passport_photo_url'])): ?>

                              <?php

                              // Strip out stray leading slashes and format the string uniformly

                              $clean_row_path = ltrim(str_replace('\\', '/', $row['passport_photo_url']), '/');

                              // Construct the absolute path working for your local environment

                              $table_image_src = "http://localhost/gate-pass/" . $clean_row_path;

                              ?>

                              <img class="avatar-img avatar-sm rounded-circle" src="<?php echo htmlspecialchars($table_image_src); ?>" alt="<?php echo htmlspecialchars($row['staff_name']); ?>" style="width:36px; height:36px; object-fit:cover;">

                            <?php else: ?>
                              <div class="d-flex align-items-center justify-content-center rounded-circle bg-secondary text-white small fw-bold font-monospace" style="width:36px; height:36px; background-color: #6c757d !important;">
                                <?php
                                echo !empty($row['signature_initials'])
                                  ? htmlspecialchars(strtoupper($row['signature_initials']))
                                  : htmlspecialchars(strtoupper(substr($row['staff_name'], 0, 2)));
                                ?>
                              </div>
                            <?php endif; ?>
                            <div>
                              <p class="fw-semibold mb-0"><?php echo htmlspecialchars($row['staff_name']); ?></p>
                              <p class="text-muted small mb-0"><?php echo htmlspecialchars($row['department']); ?></p>
                            </div>
                          </div>
                        </td>
                        <td>
                          <code class="text-dark fw-mono font-monospace small"><?php echo htmlspecialchars($row['verification_id']); ?></code>
                        </td>
                        <td>
                          <p class="mb-0 fw-semibold text-xs" style="font-size:13px;"><?php echo htmlspecialchars($row['destination']); ?></p>
                          <p class="text-muted small mb-0" style="font-size:11px;"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($row['branch']); ?></p>
                        </td>
                        <td>
                          <p class="text-truncate mb-0 small text-dark" style="max-width: 220px;" title="<?php echo htmlspecialchars($row['purpose_of_exit']); ?>">
                            <?php echo htmlspecialchars($row['purpose_of_exit']); ?>
                          </p>
                          <p class="text-muted small mb-0 font-monospace" style="font-size:11px;">
                            <i class="bi bi-clock"></i> <?php echo htmlspecialchars($row['time_out']); ?> → <?php echo htmlspecialchars($row['expected_time_in']); ?>
                          </p>
                        </td>
                        <td>
                          <div class="mb-1"><?php echo $status_badge; ?></div>
                          <?php if (!empty($row['hr_reviewed_by'])): ?>
                            <p class="text-muted xx-small mb-0 tracking-tight" style="font-size:10px;" title="<?php echo htmlspecialchars($row['hr_remarks']); ?>">
                              By: <?php echo htmlspecialchars($row['hr_reviewed_by']); ?>
                            </p>
                          <?php endif; ?>
                        </td>
                        <td class="text-end">
                          <a class="btn btn-light btn-sm border" href="pass-details.php?id=<?php echo base64_encode($row['id']); ?>">View Log</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center py-4 text-muted">
                        <i class="bi bi-folder-x fs-4 d-block mb-2"></i> No gate pass entries found in the workspace system.
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-3">
              <p class="text-muted small mb-0">
                Showing 1 to <?php echo count($gate_passes); ?> of <?php echo $total_records; ?> active log entries
              </p>
              <nav aria-label="Gate-pass pagination hierarchy">
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