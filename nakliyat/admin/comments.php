<?php
$page_title = 'Yorum Yönetimi';
include 'includes/header.php';

// Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    switch ($action) {
        case 'approve':
            $conn->query("UPDATE comments SET is_approved=1 WHERE id=$id");
            echo alert('Yorum onaylandı!', 'success');
            break;
        case 'reject':
            $conn->query("UPDATE comments SET is_approved=0 WHERE id=$id");
            echo alert('Yorum reddedildi!', 'warning');
            break;
        case 'delete':
            $conn->query("DELETE FROM comments WHERE id=$id");
            echo alert('Yorum silindi!', 'success');
            break;
    }
}

// Get filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Build query
$where = "1=1";
switch ($filter) {
    case 'pending':
        $where = "is_approved=0 AND is_verified=1";
        break;
    case 'approved':
        $where = "is_approved=1 AND is_verified=1";
        break;
    case 'unverified':
        $where = "is_verified=0";
        break;
}

// Fetch comments
$comments = $conn->query("SELECT * FROM comments WHERE $where ORDER BY created_at DESC");

// Get counts
$count_all = $conn->query("SELECT COUNT(*) as count FROM comments")->fetch_assoc()['count'];
$count_pending = $conn->query("SELECT COUNT(*) as count FROM comments WHERE is_approved=0 AND is_verified=1")->fetch_assoc()['count'];
$count_approved = $conn->query("SELECT COUNT(*) as count FROM comments WHERE is_approved=1 AND is_verified=1")->fetch_assoc()['count'];
$count_unverified = $conn->query("SELECT COUNT(*) as count FROM comments WHERE is_verified=0")->fetch_assoc()['count'];
?>

<div class="content-card">
    <h5 class="mb-4"><i class="fas fa-comments me-2"></i>Yorum Yönetimi</h5>

    <!-- Filter Tabs -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link <?= $filter == 'all' ? 'active' : '' ?>" href="?filter=all">
                Tümü <span class="badge bg-secondary"><?= $count_all ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filter == 'pending' ? 'active' : '' ?>" href="?filter=pending">
                Beklemede <span class="badge bg-warning"><?= $count_pending ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filter == 'approved' ? 'active' : '' ?>" href="?filter=approved">
                Onaylı <span class="badge bg-success"><?= $count_approved ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $filter == 'unverified' ? 'active' : '' ?>" href="?filter=unverified">
                Doğrulanmamış <span class="badge bg-danger"><?= $count_unverified ?></span>
            </a>
        </li>
    </ul>

    <?php if ($comments->num_rows > 0): ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Ad</th>
                    <th>E-posta</th>
                    <th>Yorum</th>
                    <th>Puan</th>
                    <th>Sayfa</th>
                    <th>Durum</th>
                    <th>Tarih</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($comment = $comments->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($comment['name']) ?></td>
                    <td><small><?= htmlspecialchars($comment['email']) ?></small></td>
                    <td>
                        <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?= htmlspecialchars(substr($comment['comment'], 0, 100)) ?><?= strlen($comment['comment']) > 100 ? '...' : '' ?>
                        </div>
                    </td>
                    <td>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= $comment['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                        <?php endfor; ?>
                    </td>
                    <td>
                        <?php
                        if ($comment['page_type'] == 'homepage') {
                            echo '<span class="badge bg-primary">Anasayfa</span>';
                        } elseif ($comment['page_type'] == 'blog') {
                            $blog = $conn->query("SELECT title FROM blog_posts WHERE id={$comment['page_id']}")->fetch_assoc();
                            echo '<span class="badge bg-info">Blog: ' . htmlspecialchars($blog['title'] ?? 'Silinmiş') . '</span>';
                        } elseif ($comment['page_type'] == 'service') {
                            $service = $conn->query("SELECT title FROM services WHERE id={$comment['page_id']}")->fetch_assoc();
                            echo '<span class="badge bg-success">Hizmet: ' . htmlspecialchars($service['title'] ?? 'Silinmiş') . '</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <?php if (!$comment['is_verified']): ?>
                            <span class="badge bg-danger">Doğrulanmadı</span>
                        <?php elseif ($comment['is_approved']): ?>
                            <span class="badge bg-success">Onaylı</span>
                        <?php else: ?>
                            <span class="badge bg-warning">Beklemede</span>
                        <?php endif; ?>
                    </td>
                    <td><small><?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?></small></td>
                    <td class="table-actions">
                        <?php if ($comment['is_verified'] && !$comment['is_approved']): ?>
                        <a href="?action=approve&id=<?= $comment['id'] ?>&filter=<?= $filter ?>" class="btn btn-sm btn-success" title="Onayla">
                            <i class="fas fa-check"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($comment['is_approved']): ?>
                        <a href="?action=reject&id=<?= $comment['id'] ?>&filter=<?= $filter ?>" class="btn btn-sm btn-warning" title="Reddet">
                            <i class="fas fa-times"></i>
                        </a>
                        <?php endif; ?>
                        <a href="?action=delete&id=<?= $comment['id'] ?>&filter=<?= $filter ?>" class="btn btn-sm btn-danger delete-confirm" title="Sil">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Henüz yorum bulunmamaktadır.
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
