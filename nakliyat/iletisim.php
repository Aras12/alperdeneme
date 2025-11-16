<?php require_once 'config/database.php';
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
?>
<!DOCTYPE html>
<html lang="tr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>İletişim | <?=$settings['site_title']?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="styles.css"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top"><div class="container">
<a class="navbar-brand" href="/"><i class="fas fa-truck-pickup"></i> <?=$settings['site_title']?></a></div></nav>
<section class="content-section"><div class="container"><h1 class="section-title text-center">İletişim</h1>
<div class="row g-4">
<div class="col-md-3"><div class="feature-box"><i class="fas fa-phone-alt"></i><h4>Telefon</h4>
<p><a href="tel:<?=$settings['phone']?>"><?=$settings['phone']?></a></p></div></div>
<div class="col-md-3"><div class="feature-box"><i class="fab fa-whatsapp"></i><h4>WhatsApp</h4>
<p><a href="https://wa.me/<?=$settings['whatsapp']?>"><?=$settings['phone']?></a></p></div></div>
<div class="col-md-3"><div class="feature-box"><i class="fas fa-map-marker-alt"></i><h4>Adres</h4><p><?=$settings['address']?></p></div></div>
<div class="col-md-3"><div class="feature-box"><i class="fas fa-clock"></i><h4>Çalışma Saatleri</h4><p>7/24 Hizmet</p></div></div>
</div></div></section>
<section class="content-section bg-light"><div class="container"><div class="row g-4">
<div class="col-lg-6"><?php if($success):?><div class="alert alert-success"><?=$success?></div><?php endif;?>
<div class="contact-form"><h3 class="mb-4">Bize Mesaj Gönderin</h3><form method="POST">
<div class="mb-3"><label class="form-label">Adınız Soyadınız *</label><input type="text" name="name" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Telefon *</label><input type="tel" name="phone" class="form-control" required></div>
<div class="mb-3"><label class="form-label">E-posta</label><input type="email" name="email" class="form-control"></div>
<div class="mb-3"><label class="form-label">Konu *</label><select name="subject" class="form-select" required>
<option value="">Konu Seçiniz</option><option value="Çekici Hizmeti">Çekici Hizmeti</option>
<option value="Akü Takviye">Akü Takviye</option><option value="Fiyat Teklifi">Fiyat Teklifi</option></select></div>
<div class="mb-3"><label class="form-label">Mesajınız *</label><textarea name="message" class="form-control" rows="5" required></textarea></div>
<button type="submit" class="btn btn-primary btn-lg w-100"><i class="fas fa-paper-plane"></i> Mesaj Gönder</button>
</form></div></div>
<div class="col-lg-6"><div class="map-container">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d402164.0324634229!2d34.77746692578116!3d37.00166697931548!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1527f85e5be54e59%3A0x40c68f475e72c26e!2sAdana!5e0!3m2!1str!2str!4v1635000000000!5m2!1str!2str" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</div></div></div></div></section>
<div class="sticky-buttons"><a href="https://wa.me/<?=$settings['whatsapp']?>" class="sticky-btn whatsapp-btn"><i class="fab fa-whatsapp"></i></a>
<a href="tel:<?=$settings['phone']?>" class="sticky-btn phone-btn"><i class="fas fa-phone-alt"></i></a></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
