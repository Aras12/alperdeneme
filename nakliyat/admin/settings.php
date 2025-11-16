<?php
$page_title = 'Site Ayarları';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $value) {
        if ($key != 'submit') {
            $value = sanitize($value);
            $conn->query("UPDATE settings SET setting_value='$value' WHERE setting_key='$key'");
        }
    }
    echo alert('Ayarlar kaydedildi!', 'success');
    $settings = getSettings();
}
?>

<div class="content-card">
    <h5 class="mb-4"><i class="fas fa-cog me-2"></i>Genel Ayarlar</h5>
    <form method="POST" action="">
        <h6 class="border-bottom pb-2 mb-3">Genel Bilgiler</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Site Başlığı</label>
                    <input type="text" name="site_title" class="form-control"
                           value="<?php echo $settings['site_title'] ?? ''; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">E-posta</label>
                    <input type="email" name="email" class="form-control"
                           value="<?php echo $settings['email'] ?? ''; ?>">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Site Açıklaması</label>
            <textarea name="site_description" class="form-control" rows="2"><?php echo $settings['site_description'] ?? ''; ?></textarea>
        </div>

        <h6 class="border-bottom pb-2 mb-3 mt-4">İletişim Bilgileri</h6>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Telefon</label>
                    <input type="text" name="phone" class="form-control"
                           value="<?php echo $settings['phone'] ?? ''; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">WhatsApp (905374092406)</label>
                    <input type="text" name="whatsapp" class="form-control"
                           value="<?php echo $settings['whatsapp'] ?? ''; ?>">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Adres</label>
            <input type="text" name="address" class="form-control"
                   value="<?php echo $settings['address'] ?? ''; ?>">
        </div>

        <h6 class="border-bottom pb-2 mb-3 mt-4">Sosyal Medya</h6>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Facebook URL</label>
                    <input type="url" name="facebook_url" class="form-control"
                           value="<?php echo $settings['facebook_url'] ?? ''; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Instagram URL</label>
                    <input type="url" name="instagram_url" class="form-control"
                           value="<?php echo $settings['instagram_url'] ?? ''; ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Twitter URL</label>
                    <input type="url" name="twitter_url" class="form-control"
                           value="<?php echo $settings['twitter_url'] ?? ''; ?>">
                </div>
            </div>
        </div>

        <h6 class="border-bottom pb-2 mb-3 mt-4">Sayfa İçerikleri</h6>
        <div class="mb-3">
            <label class="form-label">Anasayfa İçeriği</label>
            <textarea name="homepage_content" class="form-control summernote" rows="8"><?php echo $settings['homepage_content'] ?? ''; ?></textarea>
            <small class="text-muted">Slider altında görünecek ana sayfa içeriği</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Hakkımızda Sayfası İçeriği</label>
            <textarea name="about_content" class="form-control summernote" rows="8"><?php echo $settings['about_content'] ?? ''; ?></textarea>
            <small class="text-muted">Hakkımızda sayfasında görünecek içerik</small>
        </div>

        <div class="mb-3">
            <label class="form-label">İletişim Sayfası Ek Bilgi</label>
            <textarea name="contact_info" class="form-control summernote" rows="5"><?php echo $settings['contact_info'] ?? ''; ?></textarea>
            <small class="text-muted">İletişim sayfasında ek bilgi alanı (isteğe bağlı)</small>
        </div>

        <h6 class="border-bottom pb-2 mb-3 mt-4">SEO Ayarları</h6>
        <div class="mb-3">
            <label class="form-label">Meta Keywords</label>
            <input type="text" name="meta_keywords" class="form-control"
                   value="<?php echo $settings['meta_keywords'] ?? ''; ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Google Analytics Kodu</label>
            <textarea name="google_analytics" class="form-control" rows="3"><?php echo $settings['google_analytics'] ?? ''; ?></textarea>
            <small class="text-muted">Google Analytics tracking kodunuzu buraya yapıştırın</small>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">
            <i class="fas fa-save me-2"></i>Kaydet
        </button>
    </form>
</div>

<script>
$(document).ready(function() {
    $('.summernote').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>

<?php include 'includes/footer.php'; ?>
