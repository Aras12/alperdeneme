<?php
require_once 'config/database.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$post = $conn->query("SELECT * FROM blog_posts WHERE slug='$slug' AND is_active=1")->fetch_assoc();

if (!$post) {
    header("Location: " . BASE_URL);
    exit;
}

// Set page meta data
$page_meta = [
    'title' => $post['meta_title'] ?: $post['title'],
    'description' => $post['meta_description'] ?: substr(strip_tags($post['content']), 0, 155),
    'keywords' => $post['meta_keywords'] ?? '',
    'canonical' => $post['canonical_url'] ?? BASE_URL . 'blog/' . $post['slug']
];

$active_page = 'blog';

// Include header
include 'includes/header.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <div class="container">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Ana Sayfa</a></li>
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Blog</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($post['title']) ?></li>
            </ol>
        </nav>
    </div>
</div>

<!-- Blog Post Content -->
<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <article class="blog-content">
                    <h1 class="section-title"><?= htmlspecialchars($post['title']) ?></h1>

                    <p class="text-muted mb-4">
                        <i class="fas fa-calendar-alt"></i>
                        <?= date('d.m.Y', strtotime($post['created_at'])) ?>
                    </p>

                    <?php if($post['image']): ?>
                    <img src="<?= htmlspecialchars($post['image']) ?>"
                         alt="<?= htmlspecialchars($post['title']) ?>"
                         class="blog-detail-img img-fluid rounded mb-4">
                    <?php endif; ?>

                    <div class="blog-content-text">
                        <?= $post['content'] ?>
                    </div>
                </article>

                <!-- Related Posts -->
                <?php
                $related_posts = $conn->query("SELECT * FROM blog_posts WHERE slug != '$slug' AND is_active=1 ORDER BY created_at DESC LIMIT 3");
                if($related_posts->num_rows > 0):
                ?>
                <div class="related-posts mt-5">
                    <h3 class="mb-4">İlgili Yazılar</h3>
                    <div class="row g-4">
                        <?php while($related = $related_posts->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="blog-card">
                                <?php if($related['image']): ?>
                                <img src="<?= htmlspecialchars($related['image']) ?>"
                                     alt="<?= htmlspecialchars($related['title']) ?>"
                                     class="img-fluid rounded mb-3">
                                <?php endif; ?>
                                <h5><?= htmlspecialchars($related['title']) ?></h5>
                                <p class="text-muted small">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?= date('d.m.Y', strtotime($related['created_at'])) ?>
                                </p>
                                <a href="<?= BASE_URL ?>blog/<?= $related['slug'] ?>" class="btn btn-outline-primary btn-sm">
                                    Devamını Oku
                                </a>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container text-center">
        <h2>Acil Yol Yardım mı Lazım?</h2>
        <p class="lead">7/24 Hizmetinizdeyiz</p>
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

<?php include 'includes/footer.php'; ?>
