<?php require_once 'config/database.php';
$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$post = $conn->query("SELECT * FROM blog_posts WHERE slug='$slug' AND is_active=1")->fetch_assoc();
if (!$post) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="tr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?=$post['meta_title']?:$post['title']?></title>
<meta name="description" content="<?=$post['meta_description']?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="styles.css"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top"><div class="container">
<a class="navbar-brand" href="index.php"><i class="fas fa-truck-pickup"></i> Adana Oto Çekici</a></div></nav>
<section class="content-section"><div class="container"><div class="row justify-content-center"><div class="col-lg-10">
<article class="blog-content"><h1 class="section-title"><?=$post['title']?></h1>
<p class="text-muted mb-4"><i class="fas fa-calendar-alt"></i> <?=date('d.m.Y', strtotime($post['created_at']))?></p>
<img src="<?=$post['image']?>" alt="<?=$post['title']?>" class="blog-detail-img">
<?=$post['content']?></article></div></div></div></section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
