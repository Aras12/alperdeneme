<?php
$page_title = 'Ziyaretçi Takibi';
include 'includes/header.php';

// Handle IP blocking
if (isset($_GET['action']) && $_GET['action'] == 'block' && isset($_GET['ip'])) {
    $ip = sanitize($_GET['ip']);
    $reason = 'Admin tarafından engellendi';
    $conn->query("INSERT INTO blocked_ips (ip_address, reason, blocked_by) VALUES ('$ip', '$reason', {$_SESSION['admin_id']})");
    echo alert('IP adresi engellendi!', 'success');
}

// Handle delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("DELETE FROM visitors WHERE id=$id");
    echo alert('Ziyaretçi kaydı silindi!', 'success');
}

// Get filters
$date_filter = isset($_GET['date']) ? $_GET['date'] : 'all';
$device_filter = isset($_GET['device']) ? $_GET['device'] : 'all';
$source_filter = isset($_GET['source']) ? $_GET['source'] : 'all';

// Build query
$where = ["1=1"];

switch ($date_filter) {
    case 'today':
        $where[] = "DATE(created_at) = CURDATE()";
        break;
    case 'yesterday':
        $where[] = "DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        break;
    case 'week':
        $where[] = "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        break;
    case 'month':
        $where[] = "created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        break;
}

if ($device_filter != 'all') {
    $where[] = "device_type='" . sanitize($device_filter) . "'";
}

if ($source_filter == 'google') {
    $where[] = "(referrer_domain LIKE '%google%' OR utm_source='google')";
} elseif ($source_filter == 'facebook') {
    $where[] = "(referrer_domain LIKE '%facebook%' OR utm_source='facebook')";
} elseif ($source_filter == 'direct') {
    $where[] = "referrer_domain IS NULL OR referrer_domain = ''";
}

$where_sql = implode(' AND ', $where);

// Get statistics
$total_visitors = $conn->query("SELECT COUNT(*) as count FROM visitors WHERE $where_sql")->fetch_assoc()['count'];
$total_today = $conn->query("SELECT COUNT(*) as count FROM visitors WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['count'];
$total_mobile = $conn->query("SELECT COUNT(*) as count FROM visitors WHERE is_mobile=1 AND $where_sql")->fetch_assoc()['count'];
$total_desktop = $conn->query("SELECT COUNT(*) as count FROM visitors WHERE is_mobile=0 AND $where_sql")->fetch_assoc()['count'];

// Pagination
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$per_page = 50;
$offset = ($page - 1) * $per_page;
$total_pages = ceil($total_visitors / $per_page);

// Fetch visitors
$visitors = $conn->query("SELECT * FROM visitors WHERE $where_sql ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-users fa-2x text-primary"></i>
            <h3><?= number_format($total_visitors) ?></h3>
            <p>Toplam Ziyaretçi</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-calendar-day fa-2x text-success"></i>
            <h3><?= number_format($total_today) ?></h3>
            <p>Bugün</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-mobile-alt fa-2x text-warning"></i>
            <h3><?= number_format($total_mobile) ?></h3>
            <p>Mobil</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <i class="fas fa-desktop fa-2x text-info"></i>
            <h3><?= number_format($total_desktop) ?></h3>
            <p>Masaüstü</p>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-chart-line me-2"></i>Ziyaretçi Listesi</h5>
        <div class="d-flex gap-2">
            <a href="export-visitors.php?date=<?= $date_filter ?>&device=<?= $device_filter ?>&source=<?= $source_filter ?>" class="btn btn-sm btn-success">
                <i class="fas fa-file-excel"></i> Excel İndir
            </a>
            <a href="analytics" class="btn btn-sm btn-primary">
                <i class="fas fa-chart-bar"></i> Analitik
            </a>
            <a href="blocked-ips" class="btn btn-sm btn-danger">
                <i class="fas fa-ban"></i> IP Engelleme
            </a>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="date" class="form-select" onchange="this.form.submit()">
                <option value="all" <?= $date_filter == 'all' ? 'selected' : '' ?>>Tüm Zamanlar</option>
                <option value="today" <?= $date_filter == 'today' ? 'selected' : '' ?>>Bugün</option>
                <option value="yesterday" <?= $date_filter == 'yesterday' ? 'selected' : '' ?>>Dün</option>
                <option value="week" <?= $date_filter == 'week' ? 'selected' : '' ?>>Son 7 Gün</option>
                <option value="month" <?= $date_filter == 'month' ? 'selected' : '' ?>>Son 30 Gün</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="device" class="form-select" onchange="this.form.submit()">
                <option value="all" <?= $device_filter == 'all' ? 'selected' : '' ?>>Tüm Cihazlar</option>
                <option value="Mobile" <?= $device_filter == 'Mobile' ? 'selected' : '' ?>>Mobil</option>
                <option value="Desktop" <?= $device_filter == 'Desktop' ? 'selected' : '' ?>>Masaüstü</option>
                <option value="Tablet" <?= $device_filter == 'Tablet' ? 'selected' : '' ?>>Tablet</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="source" class="form-select" onchange="this.form.submit()">
                <option value="all" <?= $source_filter == 'all' ? 'selected' : '' ?>>Tüm Kaynaklar</option>
                <option value="google" <?= $source_filter == 'google' ? 'selected' : '' ?>>Google</option>
                <option value="facebook" <?= $source_filter == 'facebook' ? 'selected' : '' ?>>Facebook</option>
                <option value="direct" <?= $source_filter == 'direct' ? 'selected' : '' ?>>Direkt</option>
            </select>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th>IP</th>
                    <th>Konum</th>
                    <th>Cihaz</th>
                    <th>OS</th>
                    <th>Tarayıcı</th>
                    <th>Kaynak</th>
                    <th>Sayfa</th>
                    <th>Tarih</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php while($v = $visitors->fetch_assoc()): ?>
                <tr>
                    <td><code><?= $v['ip_address'] ?></code></td>
                    <td>
                        <small>
                            <?= $v['city'] ?>, <?= $v['country'] ?>
                        </small>
                    </td>
                    <td>
                        <span class="badge bg-<?= $v['is_mobile'] ? 'success' : 'primary' ?>">
                            <?= $v['device_type'] ?>
                        </span>
                        <?php if($v['device_brand'] != 'Unknown'): ?>
                        <br><small><?= $v['device_brand'] ?></small>
                        <?php endif; ?>
                    </td>
                    <td><small><?= $v['os'] ?> <?= $v['os_version'] ?></small></td>
                    <td><small><?= $v['browser'] ?> <?= $v['browser_version'] ?></small></td>
                    <td>
                        <?php if($v['utm_source']): ?>
                            <span class="badge bg-warning"><?= $v['utm_source'] ?></span>
                        <?php elseif($v['referrer_domain']): ?>
                            <small><?= $v['referrer_domain'] ?></small>
                        <?php else: ?>
                            <span class="badge bg-secondary">Direkt</span>
                        <?php endif; ?>
                        <?php if($v['keyword']): ?>
                            <br><small class="text-muted">🔍 <?= htmlspecialchars(substr($v['keyword'], 0, 30)) ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <small><?= $v['page_views'] ?> sayfa</small>
                    </td>
                    <td><small><?= date('d.m.y H:i', strtotime($v['created_at'])) ?></small></td>
                    <td>
                        <a href="?action=block&ip=<?= $v['ip_address'] ?>" class="btn btn-sm btn-danger" title="IP Engelle">
                            <i class="fas fa-ban"></i>
                        </a>
                        <a href="?action=delete&id=<?= $v['id'] ?>" class="btn btn-sm btn-secondary" onclick="return confirm('Silmek istediğinizden emin misiniz?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php if($total_pages > 1): ?>
    <nav class="mt-3">
        <ul class="pagination justify-content-center">
            <?php for($i = 1; $i <= min($total_pages, 10); $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?p=<?= $i ?>&date=<?= $date_filter ?>&device=<?= $device_filter ?>&source=<?= $source_filter ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<style>
.stat-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
}
.stat-card h3 {
    margin: 10px 0 5px 0;
    color: #1a237e;
}
.stat-card p {
    margin: 0;
    color: #666;
}
</style>

<?php include 'includes/footer.php'; ?>
