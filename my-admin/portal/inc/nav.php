<?php
// 1. Get the current page filename (e.g., 'dashboard.php', 'requests.php')
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>

<nav class="sidebar-nav">
    <a class="nav-link <?php echo ($current_page === 'dashboard.php') ? 'active' : ''; ?>"
        href="dashboard"
        <?php echo ($current_page === 'dashboard.php') ? 'aria-current="page"' : ''; ?>>
        <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
        <span class="nav-text">Dashboard</span>
    </a>

    <!-- This highlights 'Requests' on requests.php AND when viewing an individual pass-details page -->
    <a class="nav-link <?php echo ($current_page === 'requests.php' || $current_page === 'pass-details.php') ? 'active' : ''; ?>"
        href="requests"
        <?php echo ($current_page === 'requests.php' || $current_page === 'pass-details.php') ? 'aria-current="page"' : ''; ?>>
        <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
        <span class="nav-text">Requests</span>
    </a>

    <?php if (isset($_SESSION['admin_role']) && strtolower($_SESSION['admin_role']) === 'super admin'): ?>

        <a class="nav-link <?php echo ($current_page === 'hr.php') ? 'active' : ''; ?>"
            href="hr"
            <?php echo ($current_page === 'hr.php') ? 'aria-current="page"' : ''; ?>>
            <span class="nav-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
            <span class="nav-text">HR</span>
        </a>

        <a class="nav-link <?php echo ($current_page === 'security.php') ? 'active' : ''; ?>"
            href="security"
            <?php echo ($current_page === 'security.php') ? 'aria-current="page"' : ''; ?>>
            <span class="nav-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
            <span class="nav-text">Security</span>
        </a>

    <?php endif; ?>

    <a class="nav-link <?php echo ($current_page === 'profile.php') ? 'active' : ''; ?>"
        href="profile"
        <?php echo ($current_page === 'profile.php') ? 'aria-current="page"' : ''; ?>>
        <span class="nav-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
        <span class="nav-text">Profile</span>
    </a>



</nav>