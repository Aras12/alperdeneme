<?php require_once 'config/database.php';
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$service = $conn->query("SELECT * FROM services WHERE slug='$slug' AND is_active=1")->fetch_assoc();
if (!$service) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="tr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?=$service['meta_title']?:$service['title']?></title>
<meta name="description" content="<?=$service['meta_description']?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="styles.css"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top"><div class="container">
<a class="navbar-brand" href="index.php"><i class="fas fa-truck-pickup"></i> Adana Oto Çekici</a></div></nav>
<div class="breadcrumb"><div class="container"><nav><ol class="breadcrumb mb-0">
<li class="breadcrumb-item"><a href="index.php">Ana Sayfa</a></li>
<li class="breadcrumb-item active"><?=$service['title']?></li></ol></nav></div></div>
<section class="content-section"><div class="container"><div class="row"><div class="col-lg-8">
<img src="<?=$service['image']?>" alt="<?=$service['title']?>" class="service-detail-img">
<h1><?=$service['title']?></h1><?=$service['content']?></div>
<div class="col-lg-4"><div class="price-list"><h4><i class="fas fa-star"></i> İletişim</h4><ul>
<li><a href="tel:<?=$settings['phone']?>"><i class="fas fa-phone"></i> <?=$settings['phone']?></a></li>
<li><a href="https://wa.me/<?=$settings['whatsapp']?>"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>
</ul></div></div></div></div></section>
<div class="sticky-buttons"><a href="https://wa.me/<?=$settings['whatsapp']?>" class="sticky-btn whatsapp-btn"><i class="fab fa-whatsapp"></i></a>
<a href="tel:<?=$settings['phone']?>" class="sticky-btn phone-btn"><i class="fas fa-phone-alt"></i></a></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
