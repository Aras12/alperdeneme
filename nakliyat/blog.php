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
        <div class="row">
            <div class="col-lg-8">
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
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Categories Widget -->
                <div class="sidebar-widget">
                    <h4><i class="fas fa-list"></i> Kategoriler</h4>
                    <ul class="list-unstyled">
                        <li><a href="<?= BASE_URL ?>"><i class="fas fa-chevron-right"></i> Oto Çekici</a></li>
                        <li><a href="<?= BASE_URL ?>"><i class="fas fa-chevron-right"></i> Yol Yardım</a></li>
                        <li><a href="<?= BASE_URL ?>"><i class="fas fa-chevron-right"></i> Akü Takviye</a></li>
                        <li><a href="<?= BASE_URL ?>"><i class="fas fa-chevron-right"></i> Lastik Değişimi</a></li>
                    </ul>
                </div>

                <!-- Recent Posts Widget -->
                <?php
                $recent_posts = $conn->query("SELECT * FROM blog_posts WHERE slug != '$slug' AND is_active=1 ORDER BY created_at DESC LIMIT 5");
                if($recent_posts->num_rows > 0):
                ?>
                <div class="sidebar-widget">
                    <h4><i class="fas fa-newspaper"></i> Son Yazılar</h4>
                    <ul class="list-unstyled">
                        <?php while($recent = $recent_posts->fetch_assoc()): ?>
                        <li class="mb-3">
                            <a href="<?= BASE_URL ?>blog/<?= $recent['slug'] ?>" class="sidebar-post">
                                <?php if($recent['image']): ?>
                                <img src="<?= $recent['image'] ?>" alt="<?= htmlspecialchars($recent['title']) ?>">
                                <?php endif; ?>
                                <div class="sidebar-post-content">
                                    <h6><?= htmlspecialchars($recent['title']) ?></h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> <?= date('d.m.Y', strtotime($recent['created_at'])) ?>
                                    </small>
                                </div>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Contact Widget -->
                <div class="sidebar-widget sidebar-contact">
                    <h4><i class="fas fa-phone-alt"></i> Bize Ulaşın</h4>
                    <p>7/24 acil yol yardım hizmeti</p>
                    <a href="tel:<?= $settings['phone'] ?>" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-phone"></i> <?= $settings['phone'] ?>
                    </a>
                    <a href="https://wa.me/<?= $settings['whatsapp'] ?>" class="btn btn-success w-100" target="_blank">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                </div>
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
