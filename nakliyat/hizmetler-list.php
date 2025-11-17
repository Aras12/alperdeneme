<?php
require_once 'config/database.php';

$page_meta = [
    'title' => 'Hizmetlerimiz - ' . ($settings['site_title'] ?? 'Adana Oto Çekici'),
    'description' => 'Adana oto çekici hizmetleri: Araç çekici, acil yol yardım, lastik değişimi ve daha fazlası.',
    'canonical' => BASE_URL . 'hizmetler'
];

$active_page = 'services';

// Fetch all active services
$services = $conn->query("SELECT * FROM services WHERE is_active=1 ORDER BY display_order ASC");

include 'includes/header.php';
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="breadcrumb">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>"><i class="fas fa-home"></i> Ana Sayfa</a></li>
            <li class="breadcrumb-item active">Hizmetlerimiz</li>
        </ol>
    </div>
</nav>

<!-- Services List Section -->
<section class="content-section">
    <div class="container">
        <h1 class="section-title text-center mb-5">Hizmetlerimiz</h1>
        <p class="text-center mb-5 lead">Adana'da 7/24 profesyonel oto çekici ve acil yol yardım hizmetleri sunuyoruz</p>

        <?php if($services->num_rows > 0): ?>
        <div class="row g-4">
            <?php while($service = $services->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <?php if($service['image']): ?>
                    <div class="service-image">
                        <img src="<?= $service['image'] ?>" alt="<?= htmlspecialchars($service['title']) ?>">
                    </div>
                    <?php endif; ?>
                    <div class="service-content">
                        <div class="service-icon">
                            <i class="<?= $service['icon'] ?? 'fas fa-wrench' ?>"></i>
                        </div>
                        <h3><?= htmlspecialchars($service['title']) ?></h3>
                        <?php if($service['short_description']): ?>
                        <p><?= htmlspecialchars($service['short_description']) ?></p>
                        <?php else: ?>
                        <p><?= htmlspecialchars(substr(strip_tags($service['content']), 0, 120)) ?>...</p>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>hizmet/<?= $service['slug'] ?>" class="btn btn-primary">
                            <i class="fas fa-info-circle"></i> Detaylı Bilgi
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Henüz hizmet bulunmamaktadır.
        </div>
        <?php endif; ?>

        <!-- CTA Section -->
        <div class="cta-box mt-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3><i class="fas fa-phone-alt"></i> 7/24 Acil Yol Yardım</h3>
                    <p class="mb-0">Hızlı ve güvenilir oto çekici hizmeti için bizi arayın</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <a href="tel:<?= $settings['phone'] ?? '' ?>" class="btn btn-warning btn-lg">
                        <i class="fas fa-phone"></i> <?= $settings['phone'] ?? '' ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
