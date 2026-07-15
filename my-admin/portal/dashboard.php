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
  <meta name="description" content="Equal Gate-pass admin dashboard template">
  <title>Dashboard | Equal Gate-pass</title>

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
        <div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Security & Logistical Overview</p>
                <h1 class="h3 mb-1">Gate Pass Management</h1>
                <p class="text-muted mb-0">Monitor active personnel movement, process pending HR approvals, and audit historical security logs from one unified workspace.</p>
              </div>
            </div>
            <!-- <div class="heading-actions"><button class="btn btn-outline-secondary btn-sm" type="button"><i class="bi bi-download" aria-hidden="true"></i> Export</button><button class="btn btn-primary btn-sm" type="button"><i class="bi bi-file-earmark-plus" aria-hidden="true"></i> Create Report</button></div> -->
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

          <section class="row g-3 mt-1 align-items-stretch" aria-label="Dashboard metrics">
            <!-- Pending Approvals -->
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
              <a href="pending_passes" class="text-decoration-none w-100 d-flex text-dark" style="transition: transform 0.2s ease-in-out;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
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
              </a>
            </div>

            <!-- Active Outside Personnel -->
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
              <a href="active_passes" class="text-decoration-none w-100 d-flex text-dark" style="transition: transform 0.2s ease-in-out;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
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
              </a>
            </div>

            <!-- Approved/Completed Today -->
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
              <a href="today_logs" class="text-decoration-none w-100 d-flex text-dark" style="transition: transform 0.2s ease-in-out;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
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
              </a>
            </div>

            <!-- Rejected / Flagged Requests -->
            <div class="col-12 col-sm-6 col-xl-3 d-flex">
              <a href="declined_passes" class="text-decoration-none w-100 d-flex text-dark" style="transition: transform 0.2s ease-in-out;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
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
              </a>
            </div>
          </section>

          <?php

          try {
            $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password, [
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
              PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            // 2. Fetch the top 5 most recent gate-pass requests
            $log_sql = "SELECT 
                    id,
                    passport_photo_url, 
                    staff_name, 
                    email, 
                    department, 
                    branch, 
                    pass_date, 
                    destination, 
                    purpose_of_exit, 
                    signature_initials,
                    approval_status -- If this column doesn't exist yet, it defaults to 'Pending' in the catch block
                FROM gate_passes 
                ORDER BY id DESC 
                LIMIT 5";

            $log_stmt = $pdo->query($log_sql);
            $recent_passes = $log_stmt->fetchAll();
          } catch (PDOException $e) {
            // Fallback: If the table structure query fails (e.g. if 'approval_status' column is missing), handle gracefully
            error_log("Database Error: " . $e->getMessage());
            $recent_passes = [];
          }
          ?>

          <section class="panel mt-3">
            <div class="panel-header">
              <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-card-checklist" aria-hidden="true"></i><span>Gate-Pass Requests Log</span></h2>
                <p class="text-muted mb-0">Top 5 most recent requests.</p>
              </div>
              <a class="btn btn-outline-secondary btn-sm" href="requests.php">Manage Passes</a>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th scope="col">User</th>
                    <th scope="col">Department</th>
                    <th scope="col">Branch</th>
                    <th scope="col">Pass Date</th>
                    <th scope="col">Destination</th>
                    <th scope="col">Purpose of Exit</th>
                    <th scope="col">Approval Status</th>
                    <th scope="col">Signature</th>
                    <th scope="col" class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($recent_passes)): ?>
                    <?php foreach ($recent_passes as $row):
                      // Set image file path fallback if empty
                      $photo = !empty($row['passport_photo_url']) ? $row['passport_photo_url'] : '../assets/images/avatar/avatar-placeholder.jpg';

                      // Handle optional status column state with a safe default
                      $status = !empty($row['approval_status']) ? strtolower($row['approval_status']) : 'pending';

                      // Map database status to Bootstrap context badges
                      $badge_class = 'text-bg-warning';
                      if ($status === 'approved' || $status === 'active') {
                        $badge_class = 'text-bg-success';
                      } elseif ($status === 'declined' || $status === 'failed') {
                        $badge_class = 'text-bg-danger';
                      } elseif ($status === 'Request to See Person' || $status === 'request to see person') {
                        $badge_class = 'text-bg-info';
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
                              <!-- Optional: Fallback default initials avatar or tiny icon if no photo exists -->
                              <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted fw-bold" style="width:36px; height:36px; font-size: 12px;">
                                <?php echo strtoupper(substr($row['staff_name'], 0, 2)); ?>
                              </div>
                            <?php endif; ?>

                            <div>
                              <p class="fw-semibold mb-0"><?php echo htmlspecialchars($row['staff_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                              <p class="text-muted small mb-0"><?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                          </div>
                        </td>
                        <td><?php echo htmlspecialchars($row['department'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['branch'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['pass_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['destination'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                          <div class="text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($row['purpose_of_exit'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($row['purpose_of_exit'], ENT_QUOTES, 'UTF-8'); ?>
                          </div>
                        </td>
                        <td><span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($status); ?></span></td>
                        <td><span class="font-monospace text-muted small"><?php echo htmlspecialchars($row['signature_initials'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td class="text-end"><a class="btn btn-light btn-sm" href="pass-details?id=<?php echo base64_encode($row['id']); ?>">View</a></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="8" class="text-center text-muted py-4">No recent gate-pass logs found or connection offline.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
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