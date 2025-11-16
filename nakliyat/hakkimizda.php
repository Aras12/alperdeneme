<?php
require_once 'config/database.php';

// Set page meta data
$page_meta = [
    'title' => 'Hakkımızda | ' . $settings['site_title'],
    'description' => $settings['site_description'] ?? 'Adana\'da 7/24 profesyonel oto çekici hizmeti'
];

$active_page = 'about';

// Include header
include 'includes/header.php';
?>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <div class="container">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Ana Sayfa</a></li>
                <li class="breadcrumb-item active">Hakkımızda</li>
            </ol>
        </nav>
    </div>
</div>

<!-- About Content -->
<section class="content-section">
    <div class="container">
        <h1 class="section-title text-center">Hakkımızda</h1>

        <?php if(isset($settings['about_content']) && !empty($settings['about_content'])): ?>
            <div class="about-content">
                <?= $settings['about_content'] ?>
            </div>
        <?php else: ?>
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=800"
                     alt="Adana Oto Çekici"
                     class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6">
                <h2><?= $settings['site_title'] ?></h2>
                <p class="lead"><?= $settings['site_description'] ?></p>
                <p>Adana ve çevresinde yıllardır kesintisiz hizmet veren profesyonel oto çekici firmamız, 7/24 acil yol yardım hizmeti sunmaktadır.</p>
                <p>Modern araç filomuz ve deneyimli ekibimizle, her türlü araç çekme ve yol yardım ihtiyacınızda yanınızdayız.</p>
            </div>
        </div>
        <?php endif; ?>

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="feature-box text-center">
                    <i class="fas fa-clock fa-3x mb-3"></i>
                    <h4>7/24 Hizmet</h4>
                    <p>Kesintisiz acil yol yardım</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-box text-center">
                    <i class="fas fa-truck-pickup fa-3x mb-3"></i>
                    <h4>Modern Filo</h4>
                    <p>Son model çekici araçlar</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-box text-center">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <h4>Deneyimli Ekip</h4>
                    <p>Profesyonel operatörler</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-box text-center">
                    <i class="fas fa-shield-alt fa-3x mb-3"></i>
                    <h4>Güvenli Taşıma</h4>
                    <p>Tam sigortalı hizmet</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h3>Neden Bizi Tercih Etmelisiniz?</h3>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Hızlı ve güvenilir hizmet</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Uygun fiyat garantisi</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Profesyonel ekip</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Modern araç filosu</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success"></i> 7/24 kesintisiz hizmet</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-success"></i> Tüm Adana ve çevre ilçelere hizmet</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container text-center">
        <h2>Acil Yol Yardım mı Lazım?</h2>
        <p class="lead">Hemen Bizi Arayın!</p>
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
