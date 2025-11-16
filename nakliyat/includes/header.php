<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_meta['title']) ? $page_meta['title'] : ($settings['site_title'] ?? 'Adana Oto Çekici') ?></title>
    <meta name="description" content="<?= isset($page_meta['description']) ? $page_meta['description'] : ($settings['site_description'] ?? '') ?>">
    <?php if(isset($page_meta['keywords'])): ?>
    <meta name="keywords" content="<?= $page_meta['keywords'] ?>">
    <?php endif; ?>
    <?php if(isset($page_meta['canonical'])): ?>
    <link rel="canonical" href="<?= $page_meta['canonical'] ?>">
    <?php endif; ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>">
            <i class="fas fa-truck-pickup"></i> <?= $settings['site_title'] ?? 'Adana Oto Çekici' ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= !isset($active_page) || $active_page == 'home' ? 'active' : '' ?>" href="<?= BASE_URL ?>">Ana Sayfa</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= isset($active_page) && $active_page == 'services' ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                        Hizmetlerimiz
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        $nav_services = $conn->query("SELECT * FROM services WHERE is_active=1 ORDER BY display_order ASC");
                        while($nav_svc = $nav_services->fetch_assoc()):
                        ?>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>hizmet/<?= $nav_svc['slug'] ?>">
                                <?= htmlspecialchars($nav_svc['title']) ?>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isset($active_page) && $active_page == 'about' ? 'active' : '' ?>" href="<?= BASE_URL ?>hakkimizda">Hakkımızda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= isset($active_page) && $active_page == 'contact' ? 'active' : '' ?>" href="<?= BASE_URL ?>iletisim">İletişim</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="tel:<?= $settings['phone'] ?? '' ?>">
                        <i class="fas fa-phone"></i> <?= $settings['phone'] ?? '' ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
