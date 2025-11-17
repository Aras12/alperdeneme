<?php
require_once 'config/database.php';

$page_meta = [
    'title' => 'Blog Yazıları - ' . ($settings['site_title'] ?? 'Adana Oto Çekici'),
    'description' => 'Oto çekici, araç taşıma ve acil yol yardım hakkında faydalı blog yazılarımızı okuyun.',
    'canonical' => BASE_URL . 'blog'
];

$active_page = 'blog';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 9;
$offset = ($page - 1) * $per_page;

// Get total count
$total_result = $conn->query("SELECT COUNT(*) as count FROM blog_posts WHERE is_active=1");
$total = $total_result->fetch_assoc()['count'];
$total_pages = ceil($total / $per_page);

// Fetch blog posts
$blogs = $conn->query("SELECT * FROM blog_posts WHERE is_active=1 ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");

include 'includes/header.php';
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="breadcrumb">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>"><i class="fas fa-home"></i> Ana Sayfa</a></li>
            <li class="breadcrumb-item active">Blog Yazıları</li>
        </ol>
    </div>
</nav>

<!-- Blog List Section -->
<section class="content-section">
    <div class="container">
        <h1 class="section-title text-center mb-5">Blog Yazıları</h1>

        <?php if($blogs->num_rows > 0): ?>
        <div class="row g-4">
            <?php while($blog = $blogs->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6">
                <div class="blog-card">
                    <?php if($blog['image']): ?>
                    <img src="<?= $blog['image'] ?>" alt="<?= htmlspecialchars($blog['title']) ?>" class="blog-image">
                    <?php endif; ?>
                    <div class="blog-content">
                        <h3><?= htmlspecialchars($blog['title']) ?></h3>
                        <p class="blog-meta">
                            <i class="fas fa-calendar-alt"></i> <?= date('d.m.Y', strtotime($blog['created_at'])) ?>
                        </p>
                        <?php if($blog['excerpt']): ?>
                        <p><?= htmlspecialchars($blog['excerpt']) ?></p>
                        <?php else: ?>
                        <p><?= htmlspecialchars(substr(strip_tags($blog['content']), 0, 150)) ?>...</p>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>blog/<?= $blog['slug'] ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-book-open"></i> Devamını Oku
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <nav aria-label="Blog pagination" class="mt-5">
            <ul class="pagination justify-content-center">
                <?php if($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">
                        <i class="fas fa-chevron-left"></i> Önceki
                    </a>
                </li>
                <?php endif; ?>

                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>

                <?php if($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">
                        Sonraki <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>

        <?php else: ?>
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle"></i> Henüz blog yazısı bulunmamaktadır.
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
