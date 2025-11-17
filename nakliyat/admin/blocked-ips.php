<?php
$page_title = 'IP Engelleme';
include 'includes/header.php';

// Handle add IP
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ip_address'])) {
    $ip = sanitize($_POST['ip_address']);
    $reason = sanitize($_POST['reason']);

    $check = $conn->query("SELECT id FROM blocked_ips WHERE ip_address='$ip'");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO blocked_ips (ip_address, reason, blocked_by) VALUES ('$ip', '$reason', {$_SESSION['admin_id']})");
        echo alert('IP adresi engellendi!', 'success');
    } else {
        echo alert('Bu IP zaten engelli!', 'warning');
    }
}

// Handle unblock
if (isset($_GET['action']) && $_GET['action'] == 'unblock' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("DELETE FROM blocked_ips WHERE id=$id");
    echo alert('IP engeli kaldırıldı!', 'success');
}

// Fetch blocked IPs
$blocked_ips = $conn->query("SELECT * FROM blocked_ips ORDER BY created_at DESC");
?>

<div class="row">
    <div class="col-md-4">
        <div class="content-card">
            <h5 class="mb-4"><i class="fas fa-ban me-2"></i>IP Adresi Engelle</h5>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">IP Adresi *</label>
                    <input type="text" name="ip_address" class="form-control" required
                           placeholder="192.168.1.1" pattern="^(\d{1,3}\.){3}\d{1,3}$">
                    <small class="text-muted">Örn: 192.168.1.1</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sebep</label>
                    <textarea name="reason" class="form-control" rows="2" placeholder="Engelleme sebebi..."></textarea>
                </div>
                <button type="submit" class="btn btn-danger w-100">
                    <i class="fas fa-ban me-2"></i>Engelle
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="content-card">
            <h5 class="mb-4">
                <i class="fas fa-shield-alt me-2"></i>Engelli IP Listesi
                <span class="badge bg-danger"><?= $blocked_ips->num_rows ?></span>
            </h5>

            <?php if($blocked_ips->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>IP Adresi</th>
                            <th>Sebep</th>
                            <th>Tarih</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($ip = $blocked_ips->fetch_assoc()): ?>
                        <tr>
                            <td><code class="text-danger"><?= $ip['ip_address'] ?></code></td>
                            <td><?= htmlspecialchars($ip['reason'] ?? 'Belirtilmemiş') ?></td>
                            <td><small><?= date('d.m.Y H:i', strtotime($ip['created_at'])) ?></small></td>
                            <td>
                                <a href="?action=unblock&id=<?= $ip['id'] ?>" class="btn btn-sm btn-success"
                                   onclick="return confirm('Engeli kaldırmak istediğinizden emin misiniz?')">
                                    <i class="fas fa-check"></i> Engeli Kaldır
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Henüz engellenmiş IP adresi bulunmamaktadır.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
