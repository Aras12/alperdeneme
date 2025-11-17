<?php
/**
 * Comment Section Component
 * Usage: include this file and pass $page_type and $page_id variables
 * Example:
 *   $comment_page_type = 'homepage';
 *   $comment_page_id = 0;
 *   include 'includes/comment-section.php';
 */

// Get approved and verified comments
$comments_query = "SELECT * FROM comments
    WHERE page_type='$comment_page_type'
    AND page_id=$comment_page_id
    AND is_approved=1
    AND is_verified=1
    ORDER BY created_at DESC";
$comments_result = $conn->query($comments_query);

// Calculate average rating
$avg_rating_query = "SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM comments
    WHERE page_type='$comment_page_type'
    AND page_id=$comment_page_id
    AND is_approved=1
    AND is_verified=1";
$avg_result = $conn->query($avg_rating_query);
$avg_data = $avg_result->fetch_assoc();
$avg_rating = round($avg_data['avg_rating'], 1);
$total_comments = $avg_data['total'];

// Generate Schema.org JSON-LD for Google SEO (if there are comments)
$schema_org_json = '';
if ($total_comments > 0) {
    $schema_reviews = [];
    $comments_result_copy = $conn->query($comments_query);
    while($schema_comment = $comments_result_copy->fetch_assoc()) {
        $schema_reviews[] = [
            "@type" => "Review",
            "author" => [
                "@type" => "Person",
                "name" => htmlspecialchars($schema_comment['name'])
            ],
            "datePublished" => date('Y-m-d', strtotime($schema_comment['created_at'])),
            "reviewBody" => htmlspecialchars($schema_comment['comment']),
            "reviewRating" => [
                "@type" => "Rating",
                "ratingValue" => (string)$schema_comment['rating'],
                "bestRating" => "5",
                "worstRating" => "1"
            ]
        ];
    }

    $schema_org = [
        "@context" => "https://schema.org",
        "@type" => "LocalBusiness",
        "name" => $settings['site_title'] ?? 'Adana Oto Çekici',
        "telephone" => $settings['phone'] ?? '',
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => "Adana",
            "addressCountry" => "TR"
        ],
        "aggregateRating" => [
            "@type" => "AggregateRating",
            "ratingValue" => (string)$avg_rating,
            "reviewCount" => (string)$total_comments,
            "bestRating" => "5",
            "worstRating" => "1"
        ],
        "review" => $schema_reviews
    ];

    $schema_org_json = '<script type="application/ld+json">' . json_encode($schema_org, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
}
?>

<?php if (!empty($schema_org_json)): ?>
<!-- Schema.org Structured Data for Google -->
<?= $schema_org_json ?>
<?php endif; ?>

<!-- Comments Section -->
<section class="comments-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h3 class="section-title mb-4">
                    <i class="fas fa-comments"></i> Müşteri Yorumları
                    <?php if ($total_comments > 0): ?>
                    <span class="badge bg-primary"><?= $total_comments ?></span>
                    <?php endif; ?>
                </h3>

                <?php if ($total_comments > 0): ?>
                <div class="average-rating mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rating-stars">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= floor($avg_rating)): ?>
                                    <i class="fas fa-star text-warning"></i>
                                <?php elseif ($i <= ceil($avg_rating) && $avg_rating - floor($avg_rating) >= 0.5): ?>
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                <?php else: ?>
                                    <i class="far fa-star text-warning"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-text">
                            <strong><?= $avg_rating ?></strong> / 5 (<?= $total_comments ?> yorum)
                        </span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Comment List -->
                <div class="comments-list mb-5">
                    <?php if ($comments_result->num_rows > 0): ?>
                        <?php while($comment = $comments_result->fetch_assoc()): ?>
                        <div class="comment-item">
                            <div class="comment-header">
                                <div class="comment-author">
                                    <i class="fas fa-user-circle fa-2x text-primary me-2"></i>
                                    <div>
                                        <strong><?= htmlspecialchars($comment['name']) ?></strong>
                                        <div class="comment-rating">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?= $i <= $comment['rating'] ? 'text-warning' : 'text-muted' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i>
                                    <?= date('d.m.Y', strtotime($comment['created_at'])) ?>
                                </small>
                            </div>
                            <div class="comment-body">
                                <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Henüz yorum yapılmamış. İlk yorumu siz yapın!
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Comment Form -->
                <div class="comment-form-wrapper">
                    <h4 class="mb-3"><i class="fas fa-pen"></i> Yorum Yap</h4>
                    <p class="text-muted mb-4">Deneyiminizi paylaşın ve puan verin</p>

                    <div id="comment-response" class="mb-3"></div>

                    <form id="commentForm" class="comment-form">
                        <input type="hidden" name="page_type" value="<?= $comment_page_type ?>">
                        <input type="hidden" name="page_id" value="<?= $comment_page_id ?>">

                        <!-- Honeypot for spam protection -->
                        <input type="text" name="website" style="display:none;" tabindex="-1" autocomplete="off">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Adınız *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">E-posta *</label>
                                <input type="email" name="email" class="form-control" required>
                                <small class="text-muted">Doğrulama linki gönderilecektir</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Puanınız *</label>
                            <div class="rating-input">
                                <input type="hidden" name="rating" id="rating-value" value="5">
                                <div class="star-rating">
                                    <i class="fas fa-star star" data-rating="1"></i>
                                    <i class="fas fa-star star" data-rating="2"></i>
                                    <i class="fas fa-star star" data-rating="3"></i>
                                    <i class="fas fa-star star" data-rating="4"></i>
                                    <i class="fas fa-star star" data-rating="5"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Yorumunuz *</label>
                            <textarea name="comment" class="form-control" rows="4" required
                                      placeholder="Deneyiminizi detaylı şekilde anlatın..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Yorum Gönder
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Star rating functionality
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star-rating .star');
    const ratingInput = document.getElementById('rating-value');

    // Set all stars to active initially (5 stars)
    stars.forEach(star => star.classList.add('active'));

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            ratingInput.value = rating;

            // Update star display
            stars.forEach(s => {
                if (s.getAttribute('data-rating') <= rating) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });

        star.addEventListener('mouseover', function() {
            const rating = this.getAttribute('data-rating');
            stars.forEach(s => {
                if (s.getAttribute('data-rating') <= rating) {
                    s.classList.add('hover');
                } else {
                    s.classList.remove('hover');
                }
            });
        });
    });

    document.querySelector('.star-rating').addEventListener('mouseout', function() {
        stars.forEach(s => s.classList.remove('hover'));
    });

    // Form submission
    const commentForm = document.getElementById('commentForm');
    const responseDiv = document.getElementById('comment-response');

    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gönderiliyor...';

        fetch('<?= BASE_URL ?>submit-comment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                responseDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> ${data.message}
                    </div>
                `;
                commentForm.reset();
                ratingInput.value = 5;
                stars.forEach(star => star.classList.add('active'));
            } else {
                responseDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> ${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            responseDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Bir hata oluştu. Lütfen tekrar deneyin.
                </div>
            `;
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Yorum Gönder';

            // Scroll to response
            responseDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });
});
</script>
