<?php require_once 'config/database.php';
$page_meta = ['title'=>'Adana Oto Çekici | 7/24 Çekici Hizmeti | Acil Yol Yardım', 'description'=>'Adana\'da 7/24 oto çekici hizmeti.'];
$sliders = $conn->query("SELECT * FROM sliders WHERE is_active=1 ORDER BY display_order ASC");
$services = $conn->query("SELECT * FROM services WHERE is_active=1 ORDER BY display_order ASC LIMIT 3");
$blogs = $conn->query("SELECT * FROM blog_posts WHERE is_active=1 ORDER BY created_at DESC LIMIT 3");
$faqs = $conn->query("SELECT * FROM faqs WHERE is_active=1 ORDER BY display_order ASC");
$testimonials = $conn->query("SELECT * FROM testimonials WHERE is_active=1 ORDER BY display_order ASC LIMIT 3");
$tabs = $conn->query("SELECT * FROM tabs WHERE is_active=1 ORDER BY display_order ASC");
?>
<!DOCTYPE html>
<html lang="tr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?=$page_meta['title']?></title><meta name="description" content="<?=$page_meta['description']?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="styles.css"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top"><div class="container">
<a class="navbar-brand" href="<?=BASE_URL?>"><i class="fas fa-truck-pickup"></i> Adana Oto Çekici</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="navbarNav"><ul class="navbar-nav ms-auto">
<li class="nav-item"><a class="nav-link active" href="<?=BASE_URL?>">Ana Sayfa</a></li>
<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Hizmetlerimiz</a>
<ul class="dropdown-menu"><?php $svcs=$conn->query("SELECT * FROM services WHERE is_active=1"); while($s=$svcs->fetch_assoc()):?>
<li><a class="dropdown-item" href="<?=BASE_URL?>hizmet/<?=$s['slug']?>"><?=htmlspecialchars($s['title'])?></a></li>
<?php endwhile;?></ul></li>
<li class="nav-item"><a class="nav-link" href="<?=BASE_URL?>hakkimizda">Hakkımızda</a></li>
<li class="nav-item"><a class="nav-link" href="<?=BASE_URL?>iletisim">İletişim</a></li>
<li class="nav-item"><a class="nav-link" href="tel:<?=$settings['phone']?>"><i class="fas fa-phone"></i> <?=$settings['phone']?></a></li>
</ul></div></div></nav>
<div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
<div class="carousel-indicators"><?php $i=0; while($sl=$sliders->fetch_assoc()):?>
<button type="button" data-bs-target="#heroSlider" data-bs-slide-to="<?=$i?>" class="<?=$i==0?'active':''?>"></button>
<?php $i++; endwhile; $sliders->data_seek(0);?></div>
<div class="carousel-inner"><?php $i=0; while($sl=$sliders->fetch_assoc()):?>
<div class="carousel-item <?=$i==0?'active':''?>" style="background-image:url('<?=$sl['image']?>')">
<div class="carousel-caption"><h2><?=htmlspecialchars($sl['title'])?></h2><p><?=htmlspecialchars($sl['description'])?></p>
<a href="<?=$sl['button_link']?>" class="btn btn-primary btn-lg"><?=$sl['button_text']?></a></div></div>
<?php $i++; endwhile;?></div></div>
<section class="content-section"><div class="container"><h1 class="section-title text-center">Adana Oto Çekici</h1>
<div class="row justify-content-center"><div class="col-lg-10"><p>Mevcut static içerik...</p></div></div></div></section>
<section class="content-section bg-light"><div class="container"><h2 class="section-title text-center">Hizmetlerimiz</h2>
<div class="row g-4"><?php while($srv=$services->fetch_assoc()):?>
<div class="col-lg-4 col-md-6"><div class="service-card"><i class="<?=$srv['icon']?> fa-4x"></i>
<h3><?=htmlspecialchars($srv['title'])?></h3><p><?=htmlspecialchars($srv['short_description'])?></p>
<a href="<?=BASE_URL?>hizmet/<?=$srv['slug']?>" class="btn btn-outline-primary">Detaylı Bilgi</a></div></div>
<?php endwhile;?></div></div></section>
<section class="cta-section"><div class="container text-center"><h2>Acil Yol Yardım mı Lazım?</h2>
<div class="phone-number"><a href="tel:<?=$settings['phone']?>" style="color:inherit;"><i class="fas fa-phone-alt"></i> <?=$settings['phone']?></a></div>
<a href="https://wa.me/<?=$settings['whatsapp']?>" class="btn btn-primary btn-lg mt-3"><i class="fab fa-whatsapp"></i> WhatsApp</a></div></section>
<div class="sticky-buttons"><a href="https://wa.me/<?=$settings['whatsapp']?>" class="sticky-btn whatsapp-btn"><i class="fab fa-whatsapp"></i></a>
<a href="tel:<?=$settings['phone']?>" class="sticky-btn phone-btn"><i class="fas fa-phone-alt"></i></a>
<a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a></div>
<footer><div class="container"><div class="row"><div class="col-lg-4 mb-4"><h5><?=$settings['site_title']?></h5><p><?=$settings['site_description']?></p></div>
<div class="col-lg-4 mb-4"><h5>Hızlı Linkler</h5><ul class="list-unstyled"><li><a href="<?=BASE_URL?>">Ana Sayfa</a></li><li><a href="<?=BASE_URL?>hakkimizda">Hakkımızda</a></li><li><a href="<?=BASE_URL?>iletisim">İletişim</a></li></ul></div>
<div class="col-lg-4 mb-4"><h5>İletişim</h5><ul class="list-unstyled"><li><i class="fas fa-phone"></i> <a href="tel:<?=$settings['phone']?>"><?=$settings['phone']?></a></li>
<li><i class="fas fa-map-marker-alt"></i> <?=$settings['address']?></li></ul></div></div></div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
