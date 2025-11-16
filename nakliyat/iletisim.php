<?php
require_once 'config/database.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $phone = sanitize($_POST['phone']);
    $email = sanitize($_POST['email']);
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);

    $sql = "INSERT INTO messages (name, phone, email, subject, message) VALUES ('$name', '$phone', '$email', '$subject', '$message')";
    if ($conn->query($sql)) {
        $success = 'Mesajınız başarıyla gönderildi! En kısa sürede size dönüş yapacağız.';
    }
}

// Set page meta data
$page_meta = [
    'title' => 'İletişim | ' . $settings['site_title'],
    'description' => 'Adana Oto Çekici ile iletişime geçin. 7/24 acil yol yardım hizmeti.'
];

$active_page = 'contact';

// Include header
include 'includes/header.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <div class="container">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Ana Sayfa</a></li>
                <li class="breadcrumb-item active">İletişim</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Contact Info Section -->
<section class="content-section">
    <div class="container">
        <h1 class="section-title text-center">İletişim</h1>

        <?php if(isset($settings['contact_info']) && !empty($settings['contact_info'])): ?>
            <div class="mb-4">
                <?= $settings['contact_info'] ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="feature-box text-center">
                    <i class="fas fa-phone-alt fa-3x mb-3"></i>
                    <h4>Telefon</h4>
                    <p><a href="tel:<?= $settings['phone'] ?>"><?= $settings['phone'] ?></a></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-box text-center">
                    <i class="fab fa-whatsapp fa-3x mb-3"></i>
                    <h4>WhatsApp</h4>
                    <p><a href="https://wa.me/<?= $settings['whatsapp'] ?>" target="_blank"><?= $settings['phone'] ?></a></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-box text-center">
                    <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                    <h4>Adres</h4>
                    <p><?= $settings['address'] ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-box text-center">
                    <i class="fas fa-clock fa-3x mb-3"></i>
                    <h4>Çalışma Saatleri</h4>
                    <p>7/24 Hizmet</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section class="content-section bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <?php if($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="contact-form">
                    <h3 class="mb-4">Bize Mesaj Gönderin</h3>
                    <form method="POST" action="<?= BASE_URL ?>iletisim">
                        <div class="mb-3">
                            <label class="form-label">Adınız Soyadınız *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telefon *</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E-posta</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konu *</label>
                            <select name="subject" class="form-select" required>
                                <option value="">Konu Seçiniz</option>
                                <option value="Çekici Hizmeti">Çekici Hizmeti</option>
                                <option value="Akü Takviye">Akü Takviye</option>
                                <option value="Lastik Değişimi">Lastik Değişimi</option>
                                <option value="Fiyat Teklifi">Fiyat Teklifi</option>
                                <option value="Diğer">Diğer</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mesajınız *</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-paper-plane"></i> Mesaj Gönder
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d402164.0324634229!2d34.77746692578116!3d37.00166697931548!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1527f85e5be54e59%3A0x40c68f475e72c26e!2sAdana!5e0!3m2!1str!2str!4v1635000000000!5m2!1str!2str"
                            width="100%"
                            height="450"
                            style="border:0; border-radius: 10px;"
                            allowfullscreen=""
                            loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container text-center">
        <h2>Acil Durumda Hemen Arayın!</h2>
        <p class="lead">7/24 Kesintisiz Hizmet</p>
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
