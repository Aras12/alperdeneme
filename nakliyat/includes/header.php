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
    <?php if(isset($page_meta['canonical']) && !empty($page_meta['canonical'])): ?>
    <link rel="canonical" href="<?= $page_meta['canonical'] ?>">
    <?php endif; ?>

    <?php if(!empty($settings['site_favicon'])): ?>
    <link rel="icon" type="image/x-icon" href="<?= $settings['site_favicon'] ?>">
    <?php endif; ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>styles.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>">
            <?php if(!empty($settings['site_logo'])): ?>
                <img src="<?= $settings['site_logo'] ?>" alt="<?= $settings['site_title'] ?>" style="height: 40px; margin-right: 10px;">
            <?php else: ?>
                <i class="fas fa-truck-pickup"></i>
            <?php endif; ?>
            <?= $settings['site_title'] ?? 'Adana Oto Çekici' ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php
                // Fetch main menu items
                $main_menus = $conn->query("SELECT * FROM menus WHERE parent_id=0 AND is_active=1 ORDER BY display_order ASC");
                while($menu = $main_menus->fetch_assoc()):
                    // Check if menu has children
                    $has_children = $conn->query("SELECT COUNT(*) as count FROM menus WHERE parent_id={$menu['id']} AND is_active=1")->fetch_assoc()['count'] > 0;

                    // Special handling for "Hizmetlerimiz" menu - add services as dropdown
                    if ($menu['url'] == '#' && $has_children):
                ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <?php if($menu['icon']): ?><i class="<?= $menu['icon'] ?>"></i><?php endif; ?>
                        <?= htmlspecialchars($menu['title']) ?>
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        // Show submenu items OR services if title contains "Hizmet"
                        if (stripos($menu['title'], 'hizmet') !== false):
                            $nav_services = $conn->query("SELECT * FROM services WHERE is_active=1 ORDER BY display_order ASC");
                            while($nav_svc = $nav_services->fetch_assoc()):
                        ?>
                        <li>
                            <a class="dropdown-item" href="<?= BASE_URL ?>hizmet/<?= $nav_svc['slug'] ?>">
                                <?= htmlspecialchars($nav_svc['title']) ?>
                            </a>
                        </li>
                        <?php
                            endwhile;
                        else:
                            // Show regular submenu items
                            $submenus = $conn->query("SELECT * FROM menus WHERE parent_id={$menu['id']} AND is_active=1 ORDER BY display_order ASC");
                            while($submenu = $submenus->fetch_assoc()):
                        ?>
                        <li>
                            <a class="dropdown-item" href="<?= $submenu['url'] ?>" target="<?= $submenu['target'] ?>">
                                <?php if($submenu['icon']): ?><i class="<?= $submenu['icon'] ?>"></i><?php endif; ?>
                                <?= htmlspecialchars($submenu['title']) ?>
                            </a>
                        </li>
                        <?php
                            endwhile;
                        endif;
                        ?>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $menu['url'] ?>" target="<?= $menu['target'] ?>">
                        <?php if($menu['icon']): ?><i class="<?= $menu['icon'] ?>"></i><?php endif; ?>
                        <?= htmlspecialchars($menu['title']) ?>
                    </a>
                </li>
                <?php
                    endif;
                endwhile;
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="tel:<?= $settings['phone'] ?? '' ?>">
                        <i class="fas fa-phone"></i> <?= $settings['phone'] ?? '' ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
