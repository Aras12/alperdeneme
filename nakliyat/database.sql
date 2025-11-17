-- Adana Oto Çekici CMS Database Schema
-- MySQL Database

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: adana_cekici

-- --------------------------------------------------------

-- Admins Table
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin (username: admin, password: admin123)
INSERT INTO `admins` (`username`, `password`, `email`, `full_name`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@adanacekici.com', 'Sistem Yöneticisi');

-- --------------------------------------------------------

-- Sliders Table
CREATE TABLE `sliders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255) NOT NULL,
  `button_text` varchar(100),
  `button_link` varchar(255),
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `sliders` (`title`, `description`, `image`, `button_text`, `button_link`, `display_order`, `is_active`) VALUES
('Adana\'da 7/24 Oto Çekici Hizmeti', 'Hızlı, Güvenilir ve Profesyonel Çözüm', 'https://images.unsplash.com/photo-1449130015084-2dc4a5e1c0b8?w=1920&q=80', 'Hemen Ara: 0537 409 2406', 'tel:05374092406', 1, 1),
('Akü Takviye ve Yol Yardım', 'En Kısa Sürede Yanınızdayız', 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=1920&q=80', 'Detaylı Bilgi', 'hizmetler/aku-takviye.php', 2, 1),
('Şehirler Arası Çekici', 'Türkiye Geneli Güvenli Taşıma', 'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?w=1920&q=80', 'Fiyat Al', 'hizmetler/sehirler-arasi-cekici.php', 3, 1);

-- --------------------------------------------------------

-- Services Table
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text,
  `content` longtext NOT NULL,
  `icon` varchar(100),
  `image` varchar(255),
  `meta_title` varchar(255),
  `meta_description` text,
  `meta_keywords` text,
  `canonical_url` varchar(255),
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `services` (`title`, `slug`, `short_description`, `content`, `icon`, `image`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`, `display_order`) VALUES
('Akü Takviye', 'aku-takviye', 'Aracınızın aküsü bittiğinde 7/24 akü takviye hizmeti ile yanınızdayız. Hızlı ve güvenli çözüm.', '<p>Adana\'da profesyonel akü takviye hizmeti...</p>', 'fas fa-car-battery', 'https://images.unsplash.com/photo-1487754180451-c456f719a1fc?w=1200&q=80', 'Akü Takviye Hizmeti Adana | 7/24 Akü Takviye | Adana Oto Çekici', 'Adana\'da 7/24 akü takviye hizmeti. Çukurova, Seyhan, Sarıçam, Yüreğir\'de hızlı müdahale.', 'adana akü takviye, akü takviye adana, çukurova akü takviye', 'https://www.adanaotocekici.com/hizmetler/aku-takviye.php', 1),
('Şehirler Arası Çekici', 'sehirler-arasi-cekici', 'Türkiye geneli güvenli araç taşıma hizmeti. Profesyonel ekip ve modern çekici araçlarımızla hizmetinizdeyiz.', '<p>Adana\'dan Türkiye geneline şehirler arası çekici...</p>', 'fas fa-truck-moving', 'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?w=1200&q=80', 'Şehirler Arası Çekici Adana | Türkiye Geneli Araç Taşıma', 'Adana\'dan Türkiye geneline şehirler arası çekici hizmeti. Güvenli taşıma, uygun fiyat.', 'şehirler arası çekici, adana şehirler arası çekici, araç nakli', 'https://www.adanaotocekici.com/hizmetler/sehirler-arasi-cekici.php', 2),
('Ahtapot Çekici', 'ahtapot-cekici', 'Kaza yapmış veya hasar görmüş araçların güvenli taşınması için özel ahtapot çekici hizmeti sunuyoruz.', '<p>Adana Ahtapot Çekici - Hasarlı Araçlar İçin Güvenli Taşıma...</p>', 'fas fa-car-crash', 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=1200&q=80', 'Ahtapot Çekici Adana | Kaza Sonrası Çekici | Adana Oto Çekici', 'Adana\'da 7/24 ahtapot çekici hizmeti. Kaza yapmış, hasarlı araçların güvenli taşınması.', 'ahtapot çekici adana, kaza çekici, hasarlı araç taşıma', 'https://www.adanaotocekici.com/hizmetler/ahtapot-cekici.php', 3);

-- --------------------------------------------------------

-- Blog Posts Table
CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text,
  `content` longtext NOT NULL,
  `image` varchar(255),
  `author` varchar(100) DEFAULT 'Admin',
  `meta_title` varchar(255),
  `meta_description` text,
  `meta_keywords` text,
  `canonical_url` varchar(255),
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `blog_posts` (`title`, `slug`, `excerpt`, `content`, `image`, `meta_title`, `meta_description`, `meta_keywords`, `canonical_url`) VALUES
('Çukurova Çekici Hizmeti', 'cukurova-cekici', 'Çukurova ilçesinde 7/24 kesintisiz oto çekici ve yol yardım hizmeti. En hızlı müdahale garantisi.', '<p>Çukurova, Adana\'nın en gelişmiş ilçelerinden biridir...</p>', 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=1200&q=80', 'Çukurova Çekici Hizmeti | 7/24 Çukurova Oto Çekici | Adana', 'Çukurova ilçesinde 7/24 oto çekici ve yol yardım hizmeti.', 'çukurova çekici, çukurova oto çekici, çukurova yol yardım', 'https://www.adanaotocekici.com/blog/cukurova-cekici.php'),
('Seyhan Çekici Hizmeti', 'seyhan-cekici', 'Seyhan merkez ve çevresinde profesyonel çekici hizmeti. Uygun fiyat, kaliteli hizmet.', '<p>Seyhan, Adana\'nın merkezi ve en köklü ilçesidir...</p>', 'https://images.unsplash.com/photo-1502877338535-766e1452684a?w=1200&q=80', 'Seyhan Çekici Hizmeti | 7/24 Seyhan Oto Çekici | Adana', 'Seyhan ilçesinde 7/24 oto çekici ve yol yardım hizmeti.', 'seyhan çekici, seyhan oto çekici, seyhan yol yardım', 'https://www.adanaotocekici.com/blog/seyhan-cekici.php'),
('Sarıçam Çekici Hizmeti', 'saricam-cekici', 'Sarıçam\'da acil çekici ve yol yardım ihtiyacınız için güvenilir çözüm ortağınız.', '<p>Sarıçam, Adana\'nın hızla gelişen ilçelerinden biridir...</p>', 'https://images.unsplash.com/photo-1489824904134-891ab64532f1?w=1200&q=80', 'Sarıçam Çekici Hizmeti | 7/24 Sarıçam Oto Çekici | Adana', 'Sarıçam ilçesinde 7/24 oto çekici ve yol yardım hizmeti.', 'sarıçam çekici, sarıçam oto çekici', 'https://www.adanaotocekici.com/blog/saricam-cekici.php'),
('Yüreğir Çekici Hizmeti', 'yuregir-cekici', 'Yüreğir\'de acil yol yardım ve çekici hizmeti.', '<p>Yüreğir, Adana\'nın en büyük nüfusa sahip ilçesidir...</p>', 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?w=1200&q=80', 'Yüreğir Çekici Hizmeti | 7/24 Yüreğir Oto Çekici | Adana', 'Yüreğir ilçesinde 7/24 oto çekici ve yol yardım hizmeti.', 'yüreğir çekici, yüreğir oto çekici', 'https://www.adanaotocekici.com/blog/yuregir-cekici.php');

-- --------------------------------------------------------

-- FAQs Table
CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `faqs` (`question`, `answer`, `display_order`, `is_active`) VALUES
('Çekici hizmeti ne kadar sürede gelir?', 'Adana şehir merkezi içerisinde ortalama 15-30 dakika içinde olay yerine ulaşıyoruz. Uzak ilçeler için süre konum ve mesafeye göre değişebilir. Çağrı anında size tahmini varış süresini bildiriyoruz.', 1, 1),
('Çekici ücreti nasıl hesaplanır?', 'Fiyatlandırma mesafe, araç tipi ve hizmet saatine göre belirlenir. Telefon ile aradığınızda size net bir fiyat teklifi sunuyoruz. Gizli ücret uygulaması yoktur, verdiğimiz fiyat kesindir.', 2, 1),
('Hangi araçları çekebiliyorsunuz?', 'Otomobil, SUV, hafif ticari araç, motosiklet, ATV ve benzeri tüm kara taşıtlarını çekebiliyoruz. Ağır tonajlı araçlar için özel çekici hizmeti sunuyoruz.', 3, 1),
('Şehirler arası çekici hizmeti veriyor musunuz?', 'Evet, Türkiye geneline şehirler arası araç nakil hizmeti veriyoruz. Adana\'dan İstanbul, Ankara, İzmir ve diğer tüm şehirlere güvenli taşıma yapıyoruz.', 4, 1),
('Gece yarısı hizmet veriyor musunuz?', 'Evet, 7 gün 24 saat kesintisiz hizmet veriyoruz. Gece, gündüz, hafta sonu veya tatil günleri fark etmeksizin arayabilirsiniz.', 5, 1),
('Aracım sigortalı mı taşınır?', 'Evet, tüm taşıma işlemlerimiz sigorta kapsamındadır. Aracınız profesyonel ekipmanlarla güvenli bir şekilde taşınır ve olası risklere karşı sigortalıdır.', 6, 1);

-- --------------------------------------------------------

-- Testimonials Table
CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `location` varchar(100),
  `rating` int(11) DEFAULT 5,
  `comment` text NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `testimonials` (`name`, `location`, `rating`, `comment`, `display_order`, `is_active`) VALUES
('Mehmet Y.', 'Çukurova', 5, 'Gece yarısı Çukurova\'da aracım arızalandı. 20 dakika içinde geldiler ve çok profesyonel hizmet verdiler. Teşekkürler!', 1, 1),
('Ayşe K.', 'Seyhan', 5, 'Akü takviye için aradım, çok hızlı geldiler. Fiyatları da çok uygun. Herkese tavsiye ederim.', 2, 1),
('Burak T.', 'Sarıçam', 5, 'İstanbul\'a araç nakli için kullandım. Aracım hiçbir hasar görmeden teslim edildi. Çok memnun kaldım.', 3, 1);

-- --------------------------------------------------------

-- Tabs Table
CREATE TABLE `tabs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tabs` (`title`, `slug`, `content`, `display_order`) VALUES
('Adana Oto Çekici', 'adana-oto-cekici', '<h3>Adana Oto Çekici - Profesyonel Araç Çekme Hizmeti</h3><p>Adana oto çekici hizmeti, araç sahiplerinin yolda karşılaştıkları beklenmedik durumlar için vazgeçilmez bir çözümdür...</p>', 1),
('Adana Yol Yardım', 'adana-yol-yardim', '<h3>Adana Yol Yardım - Kapsamlı Destek Hizmetleri</h3><p>Adana yol yardım hizmeti, sürücülerin yolculuk sırasında karşılaşabilecekleri her türlü soruna anında müdahale eden...</p>', 2),
('Adana Acil Çekici', 'adana-acil-cekici', '<h3>Adana Acil Çekici - Hızlı ve Güvenilir Müdahale</h3><p>Acil durumlarda zaman her şeyden önemlidir. Adana acil çekici hizmeti...</p>', 3);

-- --------------------------------------------------------

-- Messages Table
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100),
  `subject` varchar(255),
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

-- Settings Table
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  `setting_group` varchar(50) DEFAULT 'general',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('site_title', 'Adana Oto Çekici', 'general'),
('site_description', '7/24 kesintisiz hizmet veren, Adana\'nın en güvenilir oto çekici ve yol yardım firması.', 'general'),
('phone', '0537 409 2406', 'contact'),
('whatsapp', '905374092406', 'contact'),
('email', 'info@adanaotocekici.com', 'contact'),
('address', 'Adana, Türkiye', 'contact'),
('facebook_url', '#', 'social'),
('instagram_url', '#', 'social'),
('twitter_url', '#', 'social'),
('meta_keywords', 'adana oto çekici, adana çekici, adana yol yardım, adana akü takviye', 'seo'),
('google_analytics', '', 'seo'),
('homepage_content', '<div class=\"row align-items-center mb-5\">\n<div class=\"col-lg-6\">\n<h1>Adana Acil Çekici - Hızlı ve Güvenilir Müdahale</h1>\n<p class=\"lead\">Acil durumlarda zaman her şeyden önemlidir. Adana acil çekici hizmeti olarak, 7/24 kesintisiz hizmet veren profesyonel ekibimizle yanınızdayız.</p>\n<p>Modern araç filomuz ve deneyimli operatörlerimizle, aracınızı güvenle istediğiniz noktaya taşıyoruz. Tüm Adana ve çevre ilçelerde hızlı müdahale garantisi veriyoruz.</p>\n</div>\n<div class=\"col-lg-6\">\n<img src=\"https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=800\" class=\"img-fluid rounded shadow\" alt=\"Adana Oto Çekici\">\n</div>\n</div>', 'content'),
('about_content', '<p>Adana ve çevresinde yıllardır kesintisiz hizmet veren profesyonel oto çekici firmamız, modern araç filosu ve deneyimli ekibiyle 7/24 acil yol yardım hizmeti sunmaktadır.</p>\n<p>Müşteri memnuniyetini ön planda tutan anlayışımızla, her türlü araç çekme ve yol yardım ihtiyacınızda yanınızdayız. Uygun fiyat garantisi ve hızlı müdahale ile sektörde fark yaratıyoruz.</p>\n<h3>Neden Bizi Tercih Etmelisiniz?</h3>\n<ul>\n<li>7/24 kesintisiz hizmet</li>\n<li>Modern ve bakımlı araç filosu</li>\n<li>Deneyimli ve profesyonel ekip</li>\n<li>Uygun fiyat garantisi</li>\n<li>Hızlı müdahale</li>\n<li>Güvenli taşıma</li>\n<li>Tüm Adana ve çevre ilçelere hizmet</li>\n</ul>', 'content'),
('contact_info', '<div class=\"alert alert-info\">\n<i class=\"fas fa-info-circle me-2\"></i>\n<strong>Çalışma Saatlerimiz:</strong> 7 gün 24 saat hizmetinizdeyiz. Acil durumlarda hemen arayın!\n</div>', 'content'),
('announcement_text', 'Acil yol yardım için 7/24 hizmetinizdeyiz! Hemen arayın: 0537 409 2406', 'content'),
('copyright_text', '© 2024 Adana Oto Çekici. Tüm hakları saklıdır.', 'general');

-- --------------------------------------------------------

-- Gallery Table
CREATE TABLE `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `description` text,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample gallery images
INSERT INTO `gallery` (`image`, `alt_text`, `description`, `display_order`, `is_active`) VALUES
('https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=800', 'Oto çekici hizmeti', 'Profesyonel araç çekici hizmeti', 1, 1),
('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=800', 'Acil yol yardım', '7/24 acil yol yardım', 2, 1),
('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800', 'Modern araç filosu', 'Modern ve güvenli araç filosumuz', 3, 1),
('https://images.unsplash.com/photo-1506015391300-4802dc74de2a?w=800', 'Güvenli taşıma', 'Aracınızı güvenle taşıyoruz', 4, 1),
('https://images.unsplash.com/photo-1485291571150-772bcfc10da5?w=800', 'Profesyonel ekip', 'Deneyimli ve uzman ekibimiz', 5, 1),
('https://images.unsplash.com/photo-1550355191-aa8a80b41353?w=800', 'Hızlı müdahale', 'Her an yanınızdayız', 6, 1);

-- --------------------------------------------------------

-- Menus Table
CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `target` varchar(20) DEFAULT '_self',
  `icon` varchar(50) DEFAULT NULL,
  `parent_id` int(11) DEFAULT 0,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample menu items
INSERT INTO `menus` (`title`, `url`, `target`, `icon`, `parent_id`, `display_order`, `is_active`) VALUES
('Ana Sayfa', '/', '_self', 'fas fa-home', 0, 1, 1),
('Hizmetlerimiz', '#', '_self', 'fas fa-wrench', 0, 2, 1),
('Blog', '/blog', '_self', 'fas fa-newspaper', 0, 3, 1),
('Hakkımızda', '/hakkimizda', '_self', 'fas fa-info-circle', 0, 4, 1),
('İletişim', '/iletisim', '_self', 'fas fa-envelope', 0, 5, 1);

COMMIT;
