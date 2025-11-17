
<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5><?= $settings['site_title'] ?? 'Adana Oto Çekici' ?></h5>
                <p><?= $settings['site_description'] ?? '' ?></p>
            </div>
            <div class="col-lg-4 mb-4">
                <h5>Hızlı Linkler</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= BASE_URL ?>">Ana Sayfa</a></li>
                    <li><a href="<?= BASE_URL ?>hakkimizda">Hakkımızda</a></li>
                    <li><a href="<?= BASE_URL ?>iletisim">İletişim</a></li>
                </ul>
            </div>
            <div class="col-lg-4 mb-4">
                <h5>İletişim</h5>
                <ul class="list-unstyled">
                    <li>
                        <i class="fas fa-phone"></i>
                        <a href="tel:<?= $settings['phone'] ?? '' ?>"><?= $settings['phone'] ?? '' ?></a>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <?= $settings['address'] ?? '' ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="text-center">
                <p class="mb-0"><?= $settings['copyright_text'] ?? '© 2024 Adana Oto Çekici. Tüm hakları saklıdır.' ?></p>
            </div>
        </div>
    </div>
</footer>

<!-- Quote Button Sticky -->
<a href="<?= BASE_URL ?>teklif-al" class="quote-btn-sticky">
    <i class="fas fa-file-invoice-dollar"></i> TEKLİF AL
</a>

<!-- Sticky Buttons -->
<div class="sticky-buttons">
    <a href="https://wa.me/<?= $settings['whatsapp'] ?? '' ?>" class="sticky-btn whatsapp-btn" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
    <a href="tel:<?= $settings['phone'] ?? '' ?>" class="sticky-btn phone-btn">
        <i class="fas fa-phone-alt"></i>
    </a>
    <a href="#" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Back to top button
document.querySelector('.back-to-top')?.addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({top: 0, behavior: 'smooth'});
});

// Show/hide back to top button
window.addEventListener('scroll', function() {
    const backToTop = document.querySelector('.back-to-top');
    if (window.pageYOffset > 300) {
        backToTop?.classList.add('show');
    } else {
        backToTop?.classList.remove('show');
    }
});
</script>
</body>
</html>
