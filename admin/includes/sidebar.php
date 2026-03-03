<?php
$current_page = basename($_SERVER['PHP_SELF']);
$admin_user   = $_SESSION['admin_user'] ?? 'Admin';
?>
<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="../home.php" class="sidebar-logo">travel.</a>
        <span class="sidebar-sub">Admin Panel</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="dashboard.php" class="nav-item <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>
        <a href="bookings.php" class="nav-item <?= $current_page === 'bookings.php' ? 'active' : '' ?>">
            <i class="fas fa-calendar-check"></i>
            <span>Bookings</span>
            <span class="nav-badge" id="bookingBadge"></span>
        </a>
        <a href="packages.php" class="nav-item <?= $current_page === 'packages.php' ? 'active' : '' ?>">
            <i class="fas fa-suitcase-rolling"></i>
            <span>Packages</span>
        </a>

        <div class="nav-section-label">System</div>
        <a href="../home.php" class="nav-item" target="_blank">
            <i class="fas fa-external-link-alt"></i>
            <span>View Website</span>
        </a>
        <a href="logout.php" class="nav-item nav-logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </nav>

    <div class="sidebar-user">
        <div class="user-avatar">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="user-info">
            <span class="user-name"><?= htmlspecialchars($admin_user) ?></span>
            <span class="user-role">Administrator</span>
        </div>
    </div>
</aside>

<!-- Top bar -->
<header class="topbar" id="topbar">
    <button class="sidebar-toggle" id="sidebarToggle" title="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>
    <div class="topbar-left">
        <h2 class="page-title" id="pageTitle">Dashboard</h2>
    </div>
    <div class="topbar-right">
        <div class="topbar-time" id="topbarTime"></div>
        <a href="../home.php" target="_blank" class="topbar-btn" title="View Website">
            <i class="fas fa-globe"></i>
        </a>
        <a href="logout.php" class="topbar-btn topbar-logout" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</header>
