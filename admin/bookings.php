<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

/* ── Handle Actions ─────────────────────────────────────────────────────── */

$msg = $msg_type = '';

// DELETE
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id  = (int)$_GET['delete'];
    $del = mysqli_query($conn, "DELETE FROM book_form WHERE id = $id");
    $msg = $del ? 'Booking deleted successfully.' : 'Failed to delete booking.';
    $msg_type = $del ? 'success' : 'danger';
}

// UPDATE STATUS (if status column exists — handled gracefully)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_booking'])) {
    $id      = (int)($_POST['booking_id'] ?? 0);
    $name    = mysqli_real_escape_string($conn, $_POST['name']    ?? '');
    $email   = mysqli_real_escape_string($conn, $_POST['email']   ?? '');
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']   ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $location= mysqli_real_escape_string($conn, $_POST['location']?? '');
    $guests  = mysqli_real_escape_string($conn, $_POST['guests']  ?? '');
    $arrivals= mysqli_real_escape_string($conn, $_POST['arrivals']?? '');
    $leaving = mysqli_real_escape_string($conn, $_POST['leaving'] ?? '');

    $upd = mysqli_query($conn,
        "UPDATE book_form SET
            name='$name', email='$email', phone='$phone',
            address='$address', location='$location',
            guests='$guests', arrivals='$arrivals', leaving='$leaving'
         WHERE id=$id"
    );
    $msg = $upd ? 'Booking updated successfully.' : 'Update failed: ' . mysqli_error($conn);
    $msg_type = $upd ? 'success' : 'danger';
}

/* ── Filters & Pagination ───────────────────────────────────────────────── */
$search   = trim($_GET['search']   ?? '');
$per_page = 10;
$page     = max(1, (int)($_GET['page'] ?? 1));
$offset   = ($page - 1) * $per_page;

$where = '1=1';
if ($search !== '') {
    $s = mysqli_real_escape_string($conn, $search);
    $where = "name LIKE '%$s%' OR email LIKE '%$s%' OR location LIKE '%$s%' OR phone LIKE '%$s%'";
}

$res_count  = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM book_form WHERE $where");
$total_rows = mysqli_fetch_assoc($res_count)['cnt'] ?? 0;
$total_pages= max(1, (int)ceil($total_rows / $per_page));

$res = mysqli_query($conn, "SELECT * FROM book_form WHERE $where ORDER BY id DESC LIMIT $per_page OFFSET $offset");
$rows = [];
if ($res) { while ($r = mysqli_fetch_assoc($res)) $rows[] = $r; }

/* ── View Single ─────────────────────────────────────────────────────────── */
$view_row = null;
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $vid = (int)$_GET['view'];
    $vr  = mysqli_query($conn, "SELECT * FROM book_form WHERE id=$vid");
    if ($vr) $view_row = mysqli_fetch_assoc($vr);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings — travel. Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="admin-body">

<?php include 'includes/sidebar.php'; ?>

<main class="admin-main" id="adminMain">

    <div class="page-header">
        <div>
            <h1 class="ph-title">Manage Bookings</h1>
            <p class="ph-sub">View, edit and remove customer trip bookings.</p>
        </div>
        <div class="ph-actions">
            <span class="total-badge"><i class="fas fa-ticket-alt"></i> <?= $total_rows ?> total</span>
        </div>
    </div>

    <?php if ($msg): ?>
    <div class="alert alert-<?= $msg_type ?> alert-dismissible">
        <i class="fas fa-<?= $msg_type === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
        <?= htmlspecialchars($msg) ?>
        <button class="dismiss-alert">×</button>
    </div>
    <?php endif; ?>

    <!-- ── View / Edit Modal ─────────────────────────────────────────────── -->
    <?php if ($view_row): ?>
    <div class="modal-overlay" id="viewModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Booking #<?= $view_row['id'] ?></h3>
                <button class="modal-close" onclick="document.getElementById('viewModal').remove()">×</button>
            </div>
            <form method="POST" action="bookings.php" class="modal-form">
                <input type="hidden" name="booking_id" value="<?= $view_row['id'] ?>">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($view_row['name']) ?>" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($view_row['email']) ?>" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($view_row['phone']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Destination</label>
                        <input type="text" name="location" value="<?= htmlspecialchars($view_row['location']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($view_row['address']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Number of Guests</label>
                        <input type="text" name="guests" value="<?= htmlspecialchars($view_row['guests']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Arrival Date</label>
                        <input type="date" name="arrivals" value="<?= htmlspecialchars($view_row['arrivals']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Departure Date</label>
                        <input type="date" name="leaving" value="<?= htmlspecialchars($view_row['leaving']) ?>" class="form-control">
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="document.getElementById('viewModal').remove()">Cancel</button>
                    <button type="submit" name="update_booking" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── Search & Filter ───────────────────────────────────────────────── -->
    <div class="table-card">
        <div class="table-header">
            <h3><i class="fas fa-list"></i> All Bookings</h3>
            <form method="GET" action="bookings.php" class="search-form">
                <div class="search-wrap">
                    <i class="fas fa-search"></i>
                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        placeholder="Search name, email, destination…"
                        value="<?= htmlspecialchars($search) ?>"
                        class="search-input"
                    >
                    <?php if ($search): ?>
                    <a href="bookings.php" class="search-clear" title="Clear"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-sm"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>

        <?php if (empty($rows)): ?>
        <div class="table-empty">
            <i class="fas fa-inbox"></i>
            <p><?= $search ? "No bookings match \"$search\"." : 'No bookings found.' ?></p>
            <?php if ($search): ?><a href="bookings.php" class="btn-sm">Clear search</a><?php endif; ?>
        </div>
        <?php else: ?>
        <div class="table-wrap">
            <table class="admin-table" id="bookingsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Guest</th>
                        <th>Phone</th>
                        <th>Destination</th>
                        <th>Guests</th>
                        <th>Arrival</th>
                        <th>Departure</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                    <?php
                        $arrival_d = strtotime($row['arrivals']);
                        $today_d   = strtotime(date('Y-m-d'));
                        $status    = $arrival_d >= $today_d ? 'upcoming' : 'past';
                    ?>
                    <tr class="booking-row <?= $status ?>">
                        <td><span class="id-badge">#<?= $row['id'] ?></span></td>
                        <td>
                            <div class="guest-cell">
                                <div class="guest-avatar"><?= strtoupper(substr($row['name'], 0, 1)) ?></div>
                                <div>
                                    <span class="guest-name"><?= htmlspecialchars($row['name']) ?></span>
                                    <span class="guest-email"><?= htmlspecialchars($row['email']) ?></span>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><span class="dest-badge"><i class="fas fa-map-pin"></i> <?= htmlspecialchars($row['location']) ?></span></td>
                        <td><i class="fas fa-users"></i> <?= htmlspecialchars($row['guests']) ?></td>
                        <td>
                            <span class="date-chip date-arrival">
                                <i class="fas fa-plane-arrival"></i>
                                <?= htmlspecialchars($row['arrivals']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="date-chip date-leave">
                                <i class="fas fa-plane-departure"></i>
                                <?= htmlspecialchars($row['leaving']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-btns">
                                <a href="bookings.php?view=<?= $row['id'] ?><?= $search ? '&search='.urlencode($search).'&page='.$page : '' ?>"
                                   class="act-btn act-view" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="bookings.php?delete=<?= $row['id'] ?><?= $search ? '&search='.urlencode($search).'&page='.$page : '' ?>"
                                   class="act-btn act-del" title="Delete"
                                   onclick="return confirm('Delete this booking?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php for ($p = 1; $p <= $total_pages; $p++): ?>
            <a href="bookings.php?page=<?= $p ?><?= $search ? '&search='.urlencode($search) : '' ?>"
               class="page-btn <?= $p === $page ? 'active' : '' ?>"><?= $p ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>

</main>

<script src="js/admin.js"></script>
<script>
    // Auto-open modal if view param in URL
    <?php if ($view_row): ?>
    document.getElementById('viewModal').style.display = 'flex';
    <?php endif; ?>
</script>
</body>
</html>
