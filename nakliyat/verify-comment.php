<?php
require_once 'config/database.php';

$page_meta = [
    'title' => 'Yorum Doğrulama - ' . ($settings['site_title'] ?? 'Adana Oto Çekici'),
    'description' => 'E-posta adresinizi doğrulayın'
];

$success = false;
$error = false;
$message = '';

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = sanitize($_GET['token']);

    // Find comment with this token
    $result = $conn->query("SELECT * FROM comments WHERE verification_token='$token' AND is_verified=0 LIMIT 1");

    if ($result->num_rows > 0) {
        // Verify the comment
        $conn->query("UPDATE comments SET is_verified=1, verification_token=NULL WHERE verification_token='$token'");
        $success = true;
        $message = 'E-posta adresiniz başarıyla doğrulandı! Yorumunuz yönetici onayından sonra yayınlanacaktır.';
    } else {
        $error = true;
        $message = 'Geçersiz veya süresi dolmuş doğrulama bağlantısı.';
    }
} else {
    $error = true;
    $message = 'Doğrulama kodu bulunamadı.';
}

include 'includes/header.php';
?>

<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center">
                    <?php if ($success): ?>
                    <div class="verification-success">
                        <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
                        <h2>Doğrulama Başarılı!</h2>
                        <p class="lead"><?= $message ?></p>
                        <a href="<?= BASE_URL ?>" class="btn btn-primary mt-3">
                            <i class="fas fa-home"></i> Ana Sayfaya Dön
                        </a>
                    </div>
                    <?php else: ?>
                    <div class="verification-error">
                        <i class="fas fa-times-circle fa-5x text-danger mb-4"></i>
                        <h2>Doğrulama Hatası</h2>
                        <p class="lead"><?= $message ?></p>
                        <a href="<?= BASE_URL ?>" class="btn btn-secondary mt-3">
                            <i class="fas fa-home"></i> Ana Sayfaya Dön
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
