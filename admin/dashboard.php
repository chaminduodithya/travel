<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

// ── Stats ─────────────────────────────────────────────────────────────────────

// Total bookings
$res_total   = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM book_form");
$total_books = mysqli_fetch_assoc($res_total)['cnt'] ?? 0;

// Bookings this month
$res_month   = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM book_form WHERE MONTH(arrivals) = MONTH(CURDATE()) AND YEAR(arrivals) = YEAR(CURDATE())");
$month_books = mysqli_fetch_assoc($res_month)['cnt'] ?? 0;

// Upcoming bookings (arrivals >= today)
$res_up  = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM book_form WHERE arrivals >= CURDATE()");
$upcoming = mysqli_fetch_assoc($res_up)['cnt'] ?? 0;

// Unique guests (distinct emails)
$res_guests = mysqli_query($conn, "SELECT COUNT(DISTINCT email) AS cnt FROM book_form");
$total_guests = mysqli_fetch_assoc($res_guests)['cnt'] ?? 0;

// Recent 7 bookings
$res_recent  = mysqli_query($conn, "SELECT * FROM book_form ORDER BY id DESC LIMIT 7");
$recent_rows = [];
if ($res_recent) {
    while ($r = mysqli_fetch_assoc($res_recent)) $recent_rows[] = $r;
}

// Bookings per destination (top 6)
$res_dest = mysqli_query($conn, "SELECT location, COUNT(*) AS cnt FROM book_form GROUP BY location ORDER BY cnt DESC LIMIT 6");
$dest_data = [];
if ($res_dest) {
    while ($r = mysqli_fetch_assoc($res_dest)) $dest_data[] = $r;
}

// Monthly booking trend (current year)
$res_trend = mysqli_query($conn, "SELECT MONTH(arrivals) AS m, COUNT(*) AS cnt FROM book_form WHERE YEAR(arrivals) = YEAR(CURDATE()) GROUP BY m ORDER BY m");
$trend_counts = array_fill(1, 12, 0);
if ($res_trend) {
    while ($r = mysqli_fetch_assoc($res_trend)) $trend_counts[(int)$r['m']] = (int)$r['cnt'];
}

// Total packages (static — from package.php we know 12 packages)
$total_packages = 12;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — travel. Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
</head>
<body class="admin-body">

<?php include 'includes/sidebar.php'; ?>

<main class="admin-main" id="adminMain">

    <!-- ── Page Header ───────────────────────────────────────────────────── -->
    <div class="page-header">
        <div>
            <h1 class="ph-title">Dashboard</h1>
            <p class="ph-sub">Welcome back, <strong><?= htmlspecialchars($_SESSION['admin_user'] ?? 'Admin') ?></strong>! Here's what's happening today.</p>
        </div>
        <div class="ph-actions">
            <a href="bookings.php" class="btn-primary"><i class="fas fa-calendar-plus"></i> View Bookings</a>
        </div>
    </div>

    <!-- ── Stat Cards ─────────────────────────────────────────────────────── -->
    <div class="stats-grid">
        <div class="stat-card stat-green">
            <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="stat-info">
                <span class="stat-num" data-target="<?= $total_books ?>"><?= $total_books ?></span>
                <span class="stat-label">Total Bookings</span>
            </div>
            <div class="stat-trend"><i class="fas fa-arrow-up"></i> All time</div>
        </div>
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
            <div class="stat-info">
                <span class="stat-num" data-target="<?= $month_books ?>"><?= $month_books ?></span>
                <span class="stat-label">This Month</span>
            </div>
            <div class="stat-trend"><i class="fas fa-calendar"></i> Current month</div>
        </div>
        <div class="stat-card stat-orange">
            <div class="stat-icon"><i class="fas fa-plane-departure"></i></div>
            <div class="stat-info">
                <span class="stat-num" data-target="<?= $upcoming ?>"><?= $upcoming ?></span>
                <span class="stat-label">Upcoming Trips</span>
            </div>
            <div class="stat-trend"><i class="fas fa-clock"></i> From today</div>
        </div>
        <div class="stat-card stat-purple">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <span class="stat-num" data-target="<?= $total_guests ?>"><?= $total_guests ?></span>
                <span class="stat-label">Unique Guests</span>
            </div>
            <div class="stat-trend"><i class="fas fa-user-check"></i> Distinct emails</div>
        </div>
        <div class="stat-card stat-teal">
            <div class="stat-icon"><i class="fas fa-suitcase-rolling"></i></div>
            <div class="stat-info">
                <span class="stat-num" data-target="<?= $total_packages ?>"><?= $total_packages ?></span>
                <span class="stat-label">Total Packages</span>
            </div>
            <div class="stat-trend"><i class="fas fa-globe"></i> Available</div>
        </div>
    </div>

    <!-- ── Charts Row ─────────────────────────────────────────────────────── -->
    <div class="charts-row">
        <div class="chart-card wide">
            <div class="chart-header">
                <h3><i class="fas fa-chart-line"></i> Monthly Booking Trend</h3>
                <span class="chart-year"><?= date('Y') ?></span>
            </div>
            <canvas id="trendChart" height="100"></canvas>
        </div>
        <div class="chart-card">
            <div class="chart-header">
                <h3><i class="fas fa-map-marked-alt"></i> Top Destinations</h3>
            </div>
            <?php if (empty($dest_data)): ?>
            <div class="empty-chart"><i class="fas fa-chart-pie"></i><p>No data yet</p></div>
            <?php else: ?>
            <canvas id="destChart" height="200"></canvas>
            <?php endif; ?>
        </div>
    </div>

    <!-- ── Recent Bookings Table ──────────────────────────────────────────── -->
    <div class="table-card">
        <div class="table-header">
            <h3><i class="fas fa-clock"></i> Recent Bookings</h3>
            <a href="bookings.php" class="btn-sm">View All <i class="fas fa-arrow-right"></i></a>
        </div>
        <?php if (empty($recent_rows)): ?>
        <div class="table-empty">
            <i class="fas fa-inbox"></i>
            <p>No bookings yet. They'll appear here once customers book.</p>
        </div>
        <?php else: ?>
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Destination</th>
                        <th>Guests</th>
                        <th>Arrival</th>
                        <th>Departure</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_rows as $i => $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td>
                            <div class="guest-cell">
                                <div class="guest-avatar"><?= strtoupper(substr($row['name'], 0, 1)) ?></div>
                                <div>
                                    <span class="guest-name"><?= htmlspecialchars($row['name']) ?></span>
                                    <span class="guest-email"><?= htmlspecialchars($row['email']) ?></span>
                                </div>
                            </div>
                        </td>
                        <td><span class="dest-badge"><i class="fas fa-map-pin"></i> <?= htmlspecialchars($row['location']) ?></span></td>
                        <td><i class="fas fa-user"></i> <?= htmlspecialchars($row['guests']) ?></td>
                        <td><i class="fas fa-plane-arrival"></i> <?= htmlspecialchars($row['arrivals']) ?></td>
                        <td><i class="fas fa-plane-departure"></i> <?= htmlspecialchars($row['leaving']) ?></td>
                        <td>
                            <div class="action-btns">
                                <a href="bookings.php?view=<?= $row['id'] ?>" class="act-btn act-view" title="View"><i class="fas fa-eye"></i></a>
                                <a href="bookings.php?delete=<?= $row['id'] ?>" class="act-btn act-del" title="Delete" onclick="return confirm('Delete this booking?')"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

</main>

<script src="js/admin.js"></script>
<script>
// ── Trend Chart ──────────────────────────────────────────────────────────────
const trendData  = <?= json_encode(array_values($trend_counts)) ?>;
const trendCtx   = document.getElementById('trendChart').getContext('2d');
const gradient   = trendCtx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0,   'rgba(68,173,133,.5)');
gradient.addColorStop(1,   'rgba(68,173,133,.02)');

new Chart(trendCtx, {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Bookings',
            data: trendData,
            borderColor: '#44ad85',
            backgroundColor: gradient,
            borderWidth: 3,
            pointBackgroundColor: '#44ad85',
            pointRadius: 5,
            pointHoverRadius: 8,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
        scales: {
            x: { grid: { color: 'rgba(0,0,0,.06)' } },
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,.06)' } }
        }
    }
});

// ── Destinations Doughnut ────────────────────────────────────────────────────
<?php if (!empty($dest_data)): ?>
const destLabels = <?= json_encode(array_column($dest_data, 'location')) ?>;
const destCounts = <?= json_encode(array_map('intval', array_column($dest_data, 'cnt'))) ?>;
const destCtx    = document.getElementById('destChart').getContext('2d');

new Chart(destCtx, {
    type: 'doughnut',
    data: {
        labels: destLabels,
        datasets: [{
            data: destCounts,
            backgroundColor: ['#44ad85','#2563eb','#f59e0b','#ec4899','#8b5cf6','#14b8a6'],
            borderWidth: 3,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        cutout: '60%',
        plugins: {
            legend: { position: 'bottom', labels: { padding: 16, font: { size: 12 } } },
            tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.parsed} bookings` } }
        }
    }
});
<?php endif; ?>
</script>
</body>
</html>
