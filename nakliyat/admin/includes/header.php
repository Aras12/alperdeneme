<?php
require_once '../config/database.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin Panel - Adana Oto Çekici</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-navy: #1a237e;
            --primary-yellow: #ffd700;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-navy) 0%, #283593 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h4 {
            color: var(--primary-yellow);
            margin: 0;
        }

        .sidebar-menu {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .sidebar-menu li a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.1);
            border-left-color: var(--primary-yellow);
        }

        .sidebar-menu li a i {
            width: 25px;
            margin-right: 10px;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .top-navbar {
            background: white;
            padding: 15px 20px;
            margin: -20px -20px 20px -20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .content-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .btn-primary {
            background: var(--primary-navy);
            border-color: var(--primary-navy);
        }

        .btn-primary:hover {
            background: #283593;
            border-color: #283593;
        }

        .btn-warning {
            background: var(--primary-yellow);
            border-color: var(--primary-yellow);
            color: var(--primary-navy);
            font-weight: bold;
        }

        .table-actions a {
            margin: 0 5px;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-truck-pickup fa-2x mb-2" style="color: var(--primary-yellow);"></i>
            <h4>Admin Panel</h4>
            <small><?php echo $_SESSION['admin_name']; ?></small>
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Dashboard
            </a></li>
            <li><a href="slider.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'slider.php' ? 'active' : ''; ?>">
                <i class="fas fa-images"></i> Slider Yönetimi
            </a></li>
            <li><a href="services.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">
                <i class="fas fa-wrench"></i> Hizmetler
            </a></li>
            <li><a href="blog.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : ''; ?>">
                <i class="fas fa-blog"></i> Blog Yazıları
            </a></li>
            <li><a href="faq.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'faq.php' ? 'active' : ''; ?>">
                <i class="fas fa-question-circle"></i> SSS
            </a></li>
            <li><a href="testimonials.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'testimonials.php' ? 'active' : ''; ?>">
                <i class="fas fa-comments"></i> Müşteri Yorumları
            </a></li>
            <li><a href="tabs.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'tabs.php' ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i> Tab İçerikleri
            </a></li>
            <li><a href="menus.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'menus.php' ? 'active' : ''; ?>">
                <i class="fas fa-bars"></i> Menü Yönetimi
            </a></li>
            <li><a href="gallery.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'gallery.php' ? 'active' : ''; ?>">
                <i class="fas fa-images"></i> Galeri
            </a></li>
            <li><a href="comments.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'comments.php' ? 'active' : ''; ?>">
                <i class="fas fa-star"></i> Yorumlar
                <?php
                $pending_comments = $conn->query("SELECT COUNT(*) as count FROM comments WHERE is_approved=0 AND is_verified=1")->fetch_assoc();
                if ($pending_comments['count'] > 0):
                ?>
                <span class="badge bg-warning"><?php echo $pending_comments['count']; ?></span>
                <?php endif; ?>
            </a></li>
            <li><a href="messages.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'messages.php' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i> Mesajlar
                <?php
                $unread = $conn->query("SELECT COUNT(*) as count FROM messages WHERE is_read = 0")->fetch_assoc();
                if ($unread['count'] > 0):
                ?>
                <span class="badge bg-danger"><?php echo $unread['count']; ?></span>
                <?php endif; ?>
            </a></li>
            <li><a href="settings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> Site Ayarları
            </a></li>
            <li><a href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Çıkış Yap
            </a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h5>
            <div>
                <a href="<?php echo BASE_URL; ?>" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                    <i class="fas fa-external-link-alt"></i> Siteyi Görüntüle
                </a>
                <span class="text-muted">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['admin_name']; ?>
                </span>
            </div>
        </div>
