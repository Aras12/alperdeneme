<?php
require_once 'config/database.php';

// Set page meta data
$page_meta = [
    'title' => 'Adana Oto Çekici | 7/24 Çekici Hizmeti | Acil Yol Yardım',
    'description' => 'Adana\'da 7/24 oto çekici hizmeti. Hızlı, güvenilir ve uygun fiyatlı acil yol yardım.'
];

$active_page = 'home';

// Fetch data
$sliders = $conn->query("SELECT * FROM sliders WHERE is_active=1 ORDER BY display_order ASC");
$services = $conn->query("SELECT * FROM services WHERE is_active=1 ORDER BY display_order ASC LIMIT 3");
$blogs = $conn->query("SELECT * FROM blog_posts WHERE is_active=1 ORDER BY created_at DESC LIMIT 3");
$faqs = $conn->query("SELECT * FROM faqs WHERE is_active=1 ORDER BY display_order ASC");
$testimonials = $conn->query("SELECT * FROM testimonials WHERE is_active=1 ORDER BY display_order ASC");
$tabs = $conn->query("SELECT * FROM tabs WHERE is_active=1 ORDER BY display_order ASC");

// Include header
include 'includes/header.php';
?>

<!-- Hero Slider -->
<div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php
        $i = 0;
        $slider_items = [];
        while($sl = $sliders->fetch_assoc()) {
            $slider_items[] = $sl;
            echo '<button type="button" data-bs-target="#heroSlider" data-bs-slide-to="'.$i.'" class="'.($i==0?'active':'').'"></button>';
            $i++;
        }
        ?>
    </div>
    <div class="carousel-inner">
        <?php foreach($slider_items as $index => $slider): ?>
        <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>" style="background-image: url('<?= $slider['image'] ?>')">
            <div class="carousel-caption">
                <h2><?= htmlspecialchars($slider['title']) ?></h2>
                <p><?= htmlspecialchars($slider['description']) ?></p>
                <?php if($slider['button_text'] && $slider['button_link']): ?>
                <a href="<?= $slider['button_link'] ?>" class="btn btn-primary btn-lg">
                    <?= $slider['button_text'] ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Announcement Banner -->
<?php if(!empty($settings['announcement_text'])): ?>
<div class="announcement-banner">
    <div class="announcement-content">
        <i class="fas fa-bullhorn"></i>
        <span class="announcement-text"><?= $settings['announcement_text'] ?></span>
    </div>
</div>
<?php endif; ?>

<!-- Intro Content Section -->
<?php if(isset($settings['homepage_content']) && !empty($settings['homepage_content'])): ?>
<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="homepage-intro">
                    <?= $settings['homepage_content'] ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Services Section -->
<section class="content-section bg-light">
    <div class="container">
        <h2 class="section-title text-center">Hizmetlerimiz</h2>
        <div class="row g-4">
            <?php
            $services->data_seek(0);
            while($srv = $services->fetch_assoc()):
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <i class="<?= $srv['icon'] ?> fa-4x"></i>
                    <h3><?= htmlspecialchars($srv['title']) ?></h3>
                    <p><?= htmlspecialchars($srv['short_description']) ?></p>
                    <a href="<?= BASE_URL ?>hizmet/<?= $srv['slug'] ?>" class="btn btn-outline-primary">
                        Detaylı Bilgi
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Tabs Section -->
<?php if($tabs->num_rows > 0): ?>
<section class="content-section">
    <div class="container">
        <h2 class="section-title text-center">Neden Bizi Tercih Etmelisiniz?</h2>
        <ul class="nav nav-tabs justify-content-center mb-4" role="tablist">
            <?php
            $tab_items = [];
            while($tab = $tabs->fetch_assoc()) {
                $tab_items[] = $tab;
            }
            foreach($tab_items as $index => $tab):
            ?>
            <li class="nav-item">
                <a class="nav-link <?= $index == 0 ? 'active' : '' ?>"
                   data-bs-toggle="tab"
                   href="#tab<?= $tab['id'] ?>">
                    <i class="<?= $tab['icon'] ?>"></i> <?= htmlspecialchars($tab['title']) ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        <div class="tab-content">
            <?php foreach($tab_items as $index => $tab): ?>
            <div class="tab-pane fade <?= $index == 0 ? 'show active' : '' ?>" id="tab<?= $tab['id'] ?>">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <?= $tab['content'] ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Blog Section -->
<?php if($blogs->num_rows > 0): ?>
<section class="content-section bg-light">
    <div class="container">
        <h2 class="section-title text-center">Blog Yazılarımız</h2>
        <div class="row g-4">
            <?php while($blog = $blogs->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6">
                <div class="blog-card">
                    <?php if($blog['image']): ?>
                    <img src="<?= $blog['image'] ?>" alt="<?= htmlspecialchars($blog['title']) ?>" class="img-fluid rounded mb-3">
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($blog['title']) ?></h4>
                    <p class="text-muted small">
                        <i class="fas fa-calendar-alt"></i> <?= date('d.m.Y', strtotime($blog['created_at'])) ?>
                    </p>
                    <p><?= htmlspecialchars(substr(strip_tags($blog['content']), 0, 150)) ?>...</p>
                    <a href="<?= BASE_URL ?>blog/<?= $blog['slug'] ?>" class="btn btn-outline-primary btn-sm">
                        Devamını Oku
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- FAQ Section -->
<?php if($faqs->num_rows > 0): ?>
<section class="content-section">
    <div class="container">
        <h2 class="section-title text-center">Sıkça Sorulan Sorular</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <?php
                    $faq_index = 0;
                    while($faq = $faqs->fetch_assoc()):
                    ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button <?= $faq_index > 0 ? 'collapsed' : '' ?>"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#faq<?= $faq['id'] ?>">
                                <?= htmlspecialchars($faq['question']) ?>
                            </button>
                        </h2>
                        <div id="faq<?= $faq['id'] ?>"
                             class="accordion-collapse collapse <?= $faq_index == 0 ? 'show' : '' ?>"
                             data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <?= $faq['answer'] ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $faq_index++;
                    endwhile;
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Testimonials Section -->
<?php if($testimonials->num_rows > 0): ?>
<section class="content-section bg-light">
    <div class="container">
        <h2 class="section-title text-center">Müşteri Yorumları</h2>
        <div class="row g-4">
            <?php while($testimonial = $testimonials->fetch_assoc()): ?>
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="rating mb-3">
                        <?php for($i = 0; $i < $testimonial['rating']; $i++): ?>
                        <i class="fas fa-star text-warning"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="testimonial-text">"<?= htmlspecialchars($testimonial['content']) ?>"</p>
                    <div class="testimonial-author">
                        <strong><?= htmlspecialchars($testimonial['customer_name']) ?></strong>
                        <?php if($testimonial['company']): ?>
                        <br><small class="text-muted"><?= htmlspecialchars($testimonial['company']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container text-center">
        <h2>Acil Yol Yardım mı Lazım?</h2>
        <p class="lead">7/24 Hizmetinizdeyiz!</p>
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

<!-- Gallery Section -->
<?php
$gallery_items = $conn->query("SELECT * FROM gallery WHERE is_active=1 ORDER BY display_order ASC LIMIT 6");
if($gallery_items->num_rows > 0):
?>
<section class="content-section bg-light">
    <div class="container">
        <h2 class="section-title text-center">Galeri</h2>
        <p class="text-center mb-5">Hizmetlerimizden ve çalışmalarımızdan kareler</p>
        <div class="row g-3">
            <?php while($item = $gallery_items->fetch_assoc()): ?>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="gallery-item">
                    <img src="<?= $item['image'] ?>"
                         alt="<?= htmlspecialchars($item['alt_text']) ?>"
                         class="img-fluid"
                         data-bs-toggle="tooltip"
                         title="<?= htmlspecialchars($item['description']) ?>">
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
