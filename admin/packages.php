<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

/* ── Ensure packages table exists & is seeded ────────────────────────────── */
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS packages (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(255)  NOT NULL,
    description TEXT,
    price       DECIMAL(10,2) DEFAULT 0.00,
    duration    VARCHAR(100),
    destination VARCHAR(255),
    image       VARCHAR(255),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$chk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM packages"));
if ($chk['c'] == 0) {
    $defaults = [
        ['Tropical Paradise Getaway',    'Relax in the Maldives with a 7-day luxury beach resort stay, private overwater villa, snorkeling, and romantic beach dinners.',    2500, '7 Days',  'Maldives',      '../images/img-1.jpg'],
        ['European Adventure Tour',      'Explore Paris, Rome, and Barcelona on a 10-day guided tour featuring the Eiffel Tower, gondola rides, and tapas tasting.',        3200, '10 Days', 'Europe',        '../images/img-2.jpg'],
        ['Safari Expedition in Africa',  'Experience an 8-day wildlife adventure in Kenya and Tanzania, luxury camps & balloon ride.',                                      4800, '8 Days',  'Africa',        '../images/img-3.jpg'],
        ['Asian Cultural Journey',       'Discover Japan and Thailand in 12 days with samurai sword experiences, tea ceremonies, and floating markets.',                    3500, '12 Days', 'Asia',          '../images/img-4.jpg'],
        ['USA Road Trip Adventure',      'Drive from LA to Vegas and the Grand Canyon on a 10-day self-guided tour with helicopter rides and Vegas nightlife.',             2800, '10 Days', 'USA',           '../images/img-5.jpg'],
        ['Northern Lights Expedition',   'Hunt the Aurora Borealis in Iceland with glacier hikes, ice caves, and a Blue Lagoon spa retreat.',                               3000, '7 Days',  'Iceland',       '../images/img-6.jpg'],
        ['Amazon Rainforest Exploration','Stay in an eco-lodge in Brazil for 6 days, trekking through jungles and visiting indigenous villages.',                           2200, '6 Days',  'Brazil',        '../images/img-7.jpg'],
        ['Australian Outback & Reef',    'Tour Sydney, Uluru, and the Great Barrier Reef in 12 days, including snorkeling and sunset views.',                              4500, '12 Days', 'Australia',     '../images/img-8.jpg'],
        ['Himalayan Trekking Adventure', 'Trek to Everest Base Camp in 14 days with Sherpa guides and a Kathmandu cultural tour.',                                         3800, '14 Days', 'Nepal',         '../images/img-9.jpg'],
        ['Mediterranean Cruise',         'Sail through Greece, Turkey, and Croatia on a 10-day luxury cruise, exploring Santorini and Dubrovnik.',                         5000, '10 Days', 'Mediterranean', '../images/img-10.jpg'],
        ['New Zealand Adventure',        'Experience bungee jumping, Hobbiton tours, and Milford Sound cruises in 14 days.',                                               4200, '14 Days', 'New Zealand',   '../images/img-11.jpg'],
        ['Desert Luxury in Dubai',       'Enjoy 5 days of opulence with Burj Khalifa dining, desert safaris, and gold market shopping.',                                   3600, '5 Days',  'Dubai',         '../images/img-12.jpg'],
    ];
    foreach ($defaults as $d) {
        $n  = mysqli_real_escape_string($conn, $d[0]);
        $dc = mysqli_real_escape_string($conn, $d[1]);
        $p  = (float)$d[2];
        $dr = mysqli_real_escape_string($conn, $d[3]);
        $ds = mysqli_real_escape_string($conn, $d[4]);
        $im = mysqli_real_escape_string($conn, $d[5]);
        mysqli_query($conn, "INSERT INTO packages (name,description,price,duration,destination,image) VALUES('$n','$dc',$p,'$dr','$ds','$im')");
    }
}

$msg = $msg_type = '';

/* ── ADD ──────────────────────────────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_package'])) {
    $name  = mysqli_real_escape_string($conn, trim($_POST['name']        ?? ''));
    $desc  = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $price = (float)($_POST['price'] ?? 0);
    $dur   = mysqli_real_escape_string($conn, trim($_POST['duration']    ?? ''));
    $dest  = mysqli_real_escape_string($conn, trim($_POST['destination'] ?? ''));
    $img   = mysqli_real_escape_string($conn, trim($_POST['image']       ?? ''));

    if ($name !== '') {
        $ins = mysqli_query($conn, "INSERT INTO packages (name,description,price,duration,destination,image) VALUES('$name','$desc',$price,'$dur','$dest','$img')");
        $msg = $ins ? 'Package added successfully.' : 'Failed to add: ' . mysqli_error($conn);
        $msg_type = $ins ? 'success' : 'danger';
    } else {
        $msg = 'Package name is required.'; $msg_type = 'danger';
    }
}

/* ── EDIT ─────────────────────────────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_package'])) {
    $id    = (int)($_POST['package_id'] ?? 0);
    $name  = mysqli_real_escape_string($conn, trim($_POST['name']        ?? ''));
    $desc  = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $price = (float)($_POST['price'] ?? 0);
    $dur   = mysqli_real_escape_string($conn, trim($_POST['duration']    ?? ''));
    $dest  = mysqli_real_escape_string($conn, trim($_POST['destination'] ?? ''));
    $img   = mysqli_real_escape_string($conn, trim($_POST['image']       ?? ''));

    $upd = mysqli_query($conn, "UPDATE packages SET name='$name',description='$desc',price=$price,duration='$dur',destination='$dest',image='$img' WHERE id=$id");
    $msg = $upd ? 'Package updated successfully.' : 'Update failed: ' . mysqli_error($conn);
    $msg_type = $upd ? 'success' : 'danger';
}

/* ── DELETE ───────────────────────────────────────────────────────────────── */
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id  = (int)$_GET['delete'];
    $del = mysqli_query($conn, "DELETE FROM packages WHERE id=$id");
    $msg = $del ? 'Package deleted.' : 'Delete failed.';
    $msg_type = $del ? 'success' : 'danger';
}

/* ── Edit row ──────────────────────────────────────────────────────────────── */
$edit_row = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $eid     = (int)$_GET['edit'];
    $er      = mysqli_query($conn, "SELECT * FROM packages WHERE id=$eid");
    if ($er) $edit_row = mysqli_fetch_assoc($er);
}

/* ── List ─────────────────────────────────────────────────────────────────── */
$search = trim($_GET['search'] ?? '');
$where  = '1=1';
if ($search !== '') {
    $s     = mysqli_real_escape_string($conn, $search);
    $where = "name LIKE '%$s%' OR destination LIKE '%$s%' OR description LIKE '%$s%'";
}
$res  = mysqli_query($conn, "SELECT * FROM packages WHERE $where ORDER BY id DESC");
$pkgs = [];
if ($res) { while ($r = mysqli_fetch_assoc($res)) $pkgs[] = $r; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Packages — travel. Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="admin-body">

<?php include 'includes/sidebar.php'; ?>

<main class="admin-main" id="adminMain">

    <div class="page-header">
        <div>
            <h1 class="ph-title">Manage Packages</h1>
            <p class="ph-sub">Add, edit, or remove travel packages shown on your website.</p>
        </div>
        <div class="ph-actions">
            <button class="btn-primary" id="openAddModal">
                <i class="fas fa-plus"></i> Add Package
            </button>
        </div>
    </div>

    <?php if ($msg): ?>
    <div class="alert alert-<?= $msg_type ?> alert-dismissible">
        <i class="fas fa-<?= $msg_type === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
        <?= htmlspecialchars($msg) ?>
        <button class="dismiss-alert">×</button>
    </div>
    <?php endif; ?>

    <!-- ═══════════════ ADD Modal ══════════════════════════════════════════ -->
    <div class="modal-overlay" id="addModal" style="display:none">
        <div class="modal-box modal-lg">
            <div class="modal-header">
                <h3><i class="fas fa-plus-circle"></i> Add New Package</h3>
                <button class="modal-close" id="closeAddModal">×</button>
            </div>
            <form method="POST" action="packages.php" class="modal-form">
                <div class="form-grid-2">
                    <div class="form-group span-2">
                        <label>Package Name <span class="req">*</span></label>
                        <input type="text" name="name" placeholder="e.g. Tropical Paradise Getaway" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Destination</label>
                        <input type="text" name="destination" placeholder="e.g. Maldives" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" name="duration" placeholder="e.g. 7 Days" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Price (USD / person)</label>
                        <input type="number" name="price" placeholder="2500" min="0" step="0.01" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Image Path <small style="color:var(--text-3)">(relative to site root, e.g. ../images/img-1.jpg)</small></label>
                        <input type="text" name="image" placeholder="../images/img-1.jpg" class="form-control">
                    </div>
                    <div class="form-group span-2">
                        <label>Description</label>
                        <textarea name="description" rows="3" placeholder="Describe the package…" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelAdd">Cancel</button>
                    <button type="submit" name="add_package" class="btn-primary"><i class="fas fa-plus"></i> Add Package</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ═══════════════ EDIT Modal ════════════════════════════════════════ -->
    <?php if ($edit_row): ?>
    <div class="modal-overlay" id="editModal">
        <div class="modal-box modal-lg">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Package #<?= $edit_row['id'] ?></h3>
                <button class="modal-close" onclick="location.href='packages.php'">×</button>
            </div>
            <form method="POST" action="packages.php" class="modal-form">
                <input type="hidden" name="package_id" value="<?= $edit_row['id'] ?>">
                <div class="form-grid-2">
                    <div class="form-group span-2">
                        <label>Package Name <span class="req">*</span></label>
                        <input type="text" name="name" value="<?= htmlspecialchars($edit_row['name']) ?>" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Destination</label>
                        <input type="text" name="destination" value="<?= htmlspecialchars($edit_row['destination']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" name="duration" value="<?= htmlspecialchars($edit_row['duration']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Price (USD / person)</label>
                        <input type="number" name="price" value="<?= htmlspecialchars($edit_row['price']) ?>" min="0" step="0.01" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Image Path</label>
                        <input type="text" name="image" value="<?= htmlspecialchars($edit_row['image']) ?>" class="form-control">
                    </div>
                    <div class="form-group span-2">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control"><?= htmlspecialchars($edit_row['description']) ?></textarea>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="location.href='packages.php'">Cancel</button>
                    <button type="submit" name="edit_package" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── Package Cards Grid ─────────────────────────────────────────────── -->
    <div class="table-card">
        <div class="table-header">
            <h3><i class="fas fa-suitcase-rolling"></i> All Packages (<?= count($pkgs) ?>)</h3>
            <form method="GET" action="packages.php" class="search-form">
                <div class="search-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" placeholder="Search packages…"
                           value="<?= htmlspecialchars($search) ?>" class="search-input">
                    <?php if ($search): ?>
                    <a href="packages.php" class="search-clear"><i class="fas fa-times"></i></a>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn-sm"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <?php if (empty($pkgs)): ?>
        <div class="table-empty">
            <i class="fas fa-suitcase"></i>
            <p>No packages found<?= $search ? " matching \"" . htmlspecialchars($search) . "\"" : '' ?>.</p>
            <button class="btn-primary" style="margin-top:1rem" id="openAddModalEmpty">
                <i class="fas fa-plus"></i> Add First Package
            </button>
        </div>
        <?php else: ?>
        <div class="packages-grid">
            <?php foreach ($pkgs as $pkg): ?>
            <div class="pkg-card">
                <div class="pkg-img-wrap">
                    <img src="<?= htmlspecialchars($pkg['image']) ?>"
                         alt="<?= htmlspecialchars($pkg['name']) ?>"
                         class="pkg-img"
                         onerror="this.src='https://placehold.co/400x220/44ad85/ffffff?text=No+Image'">
                    <div class="pkg-dest-label">
                        <i class="fas fa-map-pin"></i> <?= htmlspecialchars($pkg['destination'] ?: 'N/A') ?>
                    </div>
                </div>
                <div class="pkg-body">
                    <h4 class="pkg-title"><?= htmlspecialchars($pkg['name']) ?></h4>
                    <p class="pkg-desc"><?= htmlspecialchars(mb_substr($pkg['description'], 0, 90)) ?>…</p>
                    <div class="pkg-meta">
                        <span class="pkg-duration"><i class="fas fa-clock"></i> <?= htmlspecialchars($pkg['duration'] ?: '—') ?></span>
                        <span class="pkg-price">$<?= number_format((float)$pkg['price'], 0) ?><small>/person</small></span>
                    </div>
                    <div class="pkg-actions">
                        <a href="packages.php?edit=<?= $pkg['id'] ?>" class="act-btn act-view">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="packages.php?delete=<?= $pkg['id'] ?>" class="act-btn act-del"
                           onclick="return confirm('Delete: <?= addslashes(htmlspecialchars($pkg['name'])) ?>?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</main>

<script src="js/admin.js"></script>
<script>
    const addModal = document.getElementById('addModal');

    document.getElementById('openAddModal')?.addEventListener('click',      () => addModal.style.display = 'flex');
    document.getElementById('closeAddModal')?.addEventListener('click',     () => addModal.style.display = 'none');
    document.getElementById('cancelAdd')?.addEventListener('click',         () => addModal.style.display = 'none');
    document.getElementById('openAddModalEmpty')?.addEventListener('click', () => addModal.style.display = 'flex');

    <?php if ($edit_row): ?>
    document.getElementById('editModal').style.display = 'flex';
    <?php endif; ?>

    // Close modals on backdrop click
    document.querySelectorAll('.modal-overlay').forEach(el => {
        el.addEventListener('click', e => { if (e.target === el) el.style.display = 'none'; });
    });
</script>
</body>
</html>
