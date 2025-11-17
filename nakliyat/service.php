<?php
require_once 'config/database.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$service = $conn->query("SELECT * FROM services WHERE slug='$slug' AND is_active=1")->fetch_assoc();

if (!$service) {
    header("Location: " . BASE_URL);
    exit;
}

// Set page meta data
$page_meta = [
    'title' => $service['meta_title'] ?: $service['title'],
    'description' => $service['meta_description'] ?: $service['short_description'],
    'keywords' => $service['meta_keywords'] ?? '',
    'canonical' => $service['canonical_url'] ?? BASE_URL . 'hizmet/' . $service['slug']
];

$active_page = 'services';

// Include header
include 'includes/header.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <div class="container">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Ana Sayfa</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Hizmetlerimiz</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($service['title']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Service Content -->
<section class="content-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php if($service['image']): ?>
                <img src="<?= htmlspecialchars($service['image']) ?>"
                     alt="<?= htmlspecialchars($service['title']) ?>"
                     class="service-detail-img img-fluid rounded mb-4">
                <?php endif; ?>

                <h1 class="section-title"><?= htmlspecialchars($service['title']) ?></h1>

                <div class="service-content">
                    <?= $service['content'] ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="price-list">
                    <h4><i class="fas fa-phone-alt"></i> Acil İletişim</h4>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <a href="tel:<?= $settings['phone'] ?>" class="btn btn-primary w-100">
                                <i class="fas fa-phone"></i> <?= $settings['phone'] ?>
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="https://wa.me/<?= $settings['whatsapp'] ?>" class="btn btn-success w-100" target="_blank">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Other Services -->
                <div class="price-list mt-4">
                    <h4><i class="fas fa-list"></i> Diğer Hizmetlerimiz</h4>
                    <ul class="list-unstyled">
                        <?php
                        $other_services = $conn->query("SELECT * FROM services WHERE slug != '$slug' AND is_active=1 ORDER BY display_order ASC LIMIT 5");
                        while($other = $other_services->fetch_assoc()):
                        ?>
                        <li class="mb-2">
                            <a href="<?= BASE_URL ?>hizmet/<?= $other['slug'] ?>">
                                <i class="<?= $other['icon'] ?>"></i> <?= htmlspecialchars($other['title']) ?>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container text-center">
        <h2>Hemen Bize Ulaşın!</h2>
        <p class="lead">7/24 Acil Yol Yardım Hizmeti</p>
        <div class="phone-number">
            <a href="tel:<?= $settings['phone'] ?>" style="color: inherit;">
                <i class="fas fa-phone-alt"></i> <?= $settings['phone'] ?>
            </a>
        </div>
        <a href="https://wa.me/<?= $settings['whatsapp'] ?>" class="btn btn-primary btn-lg mt-3" target="_blank">
            <i class="fab fa-whatsapp"></i> WhatsApp ile İletişim
        </a>
    </div>
</section>

<!-- Comments Section -->
<?php
$comment_page_type = 'service';
$comment_page_id = $service['id'];
include 'includes/comment-section.php';
?>

<?php include 'includes/footer.php'; ?>
