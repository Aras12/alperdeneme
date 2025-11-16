<?php
$page_title = 'Dashboard';
include 'includes/header.php';

// Get statistics
$total_services = $conn->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];
$total_blog = $conn->query("SELECT COUNT(*) as count FROM blog_posts")->fetch_assoc()['count'];
$total_messages = $conn->query("SELECT COUNT(*) as count FROM messages")->fetch_assoc()['count'];
$unread_messages = $conn->query("SELECT COUNT(*) as count FROM messages WHERE is_read = 0")->fetch_assoc()['count'];

// Get recent messages
$recent_messages = $conn->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5");
?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="content-card text-center">
            <i class="fas fa-wrench fa-3x text-primary mb-3"></i>
            <h3><?php echo $total_services; ?></h3>
            <p class="text-muted mb-0">Toplam Hizmet</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="content-card text-center">
            <i class="fas fa-blog fa-3x text-success mb-3"></i>
            <h3><?php echo $total_blog; ?></h3>
            <p class="text-muted mb-0">Blog Yazısı</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="content-card text-center">
            <i class="fas fa-envelope fa-3x text-info mb-3"></i>
            <h3><?php echo $total_messages; ?></h3>
            <p class="text-muted mb-0">Toplam Mesaj</p>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="content-card text-center">
            <i class="fas fa-bell fa-3x text-warning mb-3"></i>
            <h3><?php echo $unread_messages; ?></h3>
            <p class="text-muted mb-0">Okunmamış Mesaj</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="content-card">
            <h5 class="mb-4">
                <i class="fas fa-envelope me-2"></i>Son Gelen Mesajlar
            </h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tarih</th>
                            <th>İsim</th>
                            <th>Telefon</th>
                            <th>Konu</th>
                            <th>Durum</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_messages->num_rows > 0): ?>
                            <?php while ($msg = $recent_messages->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('d.m.Y H:i', strtotime($msg['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($msg['name']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                                    <td>
                                        <?php if ($msg['is_read']): ?>
                                            <span class="badge bg-secondary">Okundu</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Yeni</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="messages.php?view=<?php echo $msg['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Görüntüle
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Henüz mesaj bulunmamaktadır.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-end">
                <a href="messages.php" class="btn btn-primary">
                    Tüm Mesajları Görüntüle <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="content-card">
            <h5 class="mb-4">
                <i class="fas fa-chart-line me-2"></i>Hızlı İşlemler
            </h5>
            <div class="d-grid gap-2">
                <a href="slider.php" class="btn btn-outline-primary">
                    <i class="fas fa-images me-2"></i>Slider Yönetimi
                </a>
                <a href="services.php" class="btn btn-outline-primary">
                    <i class="fas fa-wrench me-2"></i>Hizmet Ekle
                </a>
                <a href="blog.php" class="btn btn-outline-primary">
                    <i class="fas fa-blog me-2"></i>Blog Yazısı Ekle
                </a>
                <a href="settings.php" class="btn btn-outline-primary">
                    <i class="fas fa-cog me-2"></i>Site Ayarları
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="content-card">
            <h5 class="mb-4">
                <i class="fas fa-info-circle me-2"></i>Sistem Bilgileri
            </h5>
            <table class="table table-sm">
                <tr>
                    <td><strong>PHP Versiyonu:</strong></td>
                    <td><?php echo phpversion(); ?></td>
                </tr>
                <tr>
                    <td><strong>Sunucu:</strong></td>
                    <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                </tr>
                <tr>
                    <td><strong>Veritabanı:</strong></td>
                    <td>MySQL <?php echo $conn->server_info; ?></td>
                </tr>
                <tr>
                    <td><strong>Son Giriş:</strong></td>
                    <td><?php echo date('d.m.Y H:i'); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
