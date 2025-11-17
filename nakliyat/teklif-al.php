<?php
require_once 'config/database.php';

$page_meta = [
    'title' => 'Teklif Al - ' . ($settings['site_title'] ?? 'Adana Oto Çekici'),
    'description' => 'Oto çekici hizmeti için hemen teklif alın. Size en uygun fiyatı sunuyoruz.',
    'canonical' => BASE_URL . 'teklif-al'
];

$active_page = 'quote';
$success = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $service = sanitize($_POST['service']);
    $location = sanitize($_POST['location']);
    $message = sanitize($_POST['message']);

    $full_message = "Talep Edilen Hizmet: $service\nKonum: $location\n\nMesaj:\n$message";

    $sql = "INSERT INTO messages (name, email, phone, message, created_at)
            VALUES ('$name', '$email', '$phone', '$full_message', NOW())";

    if ($conn->query($sql)) {
        $success = true;
    } else {
        $error = true;
    }
}

include 'includes/header.php';
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="breadcrumb">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>"><i class="fas fa-home"></i> Ana Sayfa</a></li>
            <li class="breadcrumb-item active">Teklif Al</li>
        </ol>
    </div>
</nav>

<!-- Quote Form Section -->
<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="quote-form-container">
                    <h1 class="section-title text-center mb-4">
                        <i class="fas fa-file-invoice-dollar"></i> Teklif Al
                    </h1>
                    <p class="text-center mb-4 lead">
                        Size özel fiyat teklifi almak için formu doldurun. En kısa sürede size dönüş yapalım.
                    </p>

                    <?php if($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Teşekkürler!</strong> Teklif talebiniz başarıyla alındı. En kısa sürede size dönüş yapacağız.
                    </div>
                    <?php elseif($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Hata!</strong> Talebiniz gönderilirken bir hata oluştu. Lütfen tekrar deneyin.
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="" class="quote-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ad Soyad *</label>
                                <input type="text" name="name" class="form-control" required
                                       placeholder="Adınız ve soyadınız">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefon *</label>
                                <input type="tel" name="phone" class="form-control" required
                                       placeholder="0xxx xxx xx xx">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="email" class="form-control"
                                   placeholder="ornek@email.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hizmet Türü *</label>
                            <select name="service" class="form-select" required>
                                <option value="">Hizmet seçin...</option>
                                <?php
                                $services = $conn->query("SELECT * FROM services WHERE is_active=1 ORDER BY display_order ASC");
                                while($svc = $services->fetch_assoc()):
                                ?>
                                <option value="<?= htmlspecialchars($svc['title']) ?>">
                                    <?= htmlspecialchars($svc['title']) ?>
                                </option>
                                <?php endwhile; ?>
                                <option value="Diğer">Diğer</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konum/Adres *</label>
                            <input type="text" name="location" class="form-control" required
                                   placeholder="Aracınızın bulunduğu konum">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mesajınız</label>
                            <textarea name="message" class="form-control" rows="4"
                                      placeholder="Detaylı bilgi veya özel taleplerinizi yazın..."></textarea>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Teklif Talebini Gönder
                            </button>
                        </div>
                    </form>

                    <div class="quote-info mt-5">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <i class="fas fa-clock"></i>
                                    <h5>Hızlı Dönüş</h5>
                                    <p>En geç 1 saat içinde</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <i class="fas fa-tag"></i>
                                    <h5>Uygun Fiyat</h5>
                                    <p>Rekabetçi teklifler</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="info-box">
                                    <i class="fas fa-shield-alt"></i>
                                    <h5>Güvenli Hizmet</h5>
                                    <p>Sigortalı taşıma</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4">
                        <i class="fas fa-phone-alt me-2"></i>
                        <strong>Acil durumlarda:</strong> Direkt arayın
                        <a href="tel:<?= $settings['phone'] ?? '' ?>" class="alert-link">
                            <?= $settings['phone'] ?? '' ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
