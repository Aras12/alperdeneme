# Adana Oto Çekici - Tam Özellikli PHP CMS

Modern, SEO uyumlu, tam yönetilebilir oto çekici web sitesi.

## 🚀 Özellikler

### ✅ Tam Admin Panel
- **Login Sistemi** - Güvenli admin girişi (admin/admin123)
- **Dashboard** - İstatistikler ve son mesajlar
- **Slider Yönetimi** - Ana sayfa slider kontrolü
- **Hizmet Yönetimi** - CRUD işlemleri, SEO ayarları
- **Blog Yönetimi** - CRUD işlemleri, meta tag yönetimi
- **SSS Yönetimi** - Soru-cevap yönetimi
- **Yorumlar Yönetimi** - Müşteri yorumları
- **Tab İçerikleri** - Ana sayfa tab bölümü yönetimi
- **Mesaj Kutusu** - İletişim formundan gelen mesajlar
- **Site Ayarları** - Telefon, adres, sosyal medya, SEO

### ✅ Dinamik Frontend
- Veritabanından veri çeken dinamik sayfalar
- SEO uyumlu meta tag'ler
- Responsive tasarım (Bootstrap 5)
- İletişim formu (veritabanına kaydeder)
- WhatsApp & Telefon butonları

## 📦 Kurulum

### 1. Veritabanı Kurulumu
```sql
-- database.sql dosyasını MySQL'de çalıştırın
-- Varsayılan veritabanı adı: adana_cekici
```

### 2. Veritabanı Ayarları
`config/database.php` dosyasını düzenleyin:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'adana_cekici');
```

### 3. Admin Panel Erişimi
```
URL: http://localhost/nakliyat/admin/
Kullanıcı: admin
Şifre: admin123
```

## 📁 Dosya Yapısı

```
nakliyat/
├── admin/              # Admin panel
│   ├── login.php
│   ├── index.php       # Dashboard
│   ├── slider.php      # Slider yönetimi
│   ├── services.php    # Hizmet yönetimi
│   ├── blog.php        # Blog yönetimi
│   ├── faq.php         # SSS
│   ├── testimonials.php # Yorumlar
│   ├── tabs.php        # Tab içerikleri
│   ├── messages.php    # Mesajlar
│   ├── settings.php    # Site ayarları
│   └── includes/       # Header, footer
├── config/
│   └── database.php    # Veritabanı bağlantısı
├── index.php           # Ana sayfa (dinamik)
├── service.php         # Hizmet detay (dinamik)
├── blog.php            # Blog detay (dinamik)
├── hakkimizda.php      # Hakkımızda
├── iletisim.php        # İletişim (formu çalışır)
├── styles.css          # Özel CSS
└── database.sql        # Veritabanı şeması
```

## 🎨 Teknolojiler

- **Backend**: PHP 7.4+, MySQL
- **Frontend**: HTML5, CSS3, Bootstrap 5
- **Editor**: Summernote (WYSIWYG)
- **Icons**: Font Awesome 6
- **Session**: PHP Session Management

## 📊 Veritabanı Tabloları

1. **admins** - Yönetici kullanıcılar
2. **sliders** - Ana sayfa slider
3. **services** - Hizmetler (SEO destekli)
4. **blog_posts** - Blog yazıları (SEO destekli)
5. **faqs** - Sık sorulan sorular
6. **testimonials** - Müşteri yorumları
7. **tabs** - Tab içerikleri
8. **messages** - İletişim formu mesajları
9. **settings** - Site ayarları

## 🔐 Güvenlik

- SQL Injection koruması (prepared statements & sanitization)
- Session bazlı authentication
- Password hashing (bcrypt)
- XSS koruması (htmlspecialchars)

## 📱 İletişim Özellikleri

- Çalışan iletişim formu
- Veritabanına kayıt
- Admin panelde mesaj görüntüleme
- Okundu işaretleme
- WhatsApp & Telefon linkleri

## 🌟 Admin Panel Özellikleri

### Her Sayfada:
- Responsive tasarım
- CRUD işlemleri (Create, Read, Update, Delete)
- Summernote text editor
- Bootstrap 5 UI
- Kolay kullanım

### Hizmet & Blog Sayfalarında:
- Meta başlık
- Meta açıklama
- Meta keywords
- Canonical URL
- Slug (URL) yönetimi
- Aktif/Pasif durumu
- Sıralama

## 🎯 Kullanım

### Yeni Hizmet Eklemek
1. Admin panele giriş yapın
2. "Hizmetler" menüsüne tıklayın
3. "Yeni Hizmet" butonuna tıklayın
4. Bilgileri doldurun (başlık, slug, içerik, SEO)
5. "Ekle" butonuna tıklayın

### Slider Eklemek
1. "Slider Yönetimi" menüsüne tıklayın
2. Form'u doldurun
3. Unsplash'tan görsel URL ekleyin
4. "Ekle" butonuna tıklayın

### Mesajları Görüntülemek
1. "Mesajlar" menüsüne tıklayın
2. Mesaj satırına tıklayarak detayları görün
3. Telefon veya WhatsApp ile iletişime geçin

## 📞 Varsayılan Ayarlar

- **Site Başlığı**: Adana Oto Çekici
- **Telefon**: 0537 409 2406
- **WhatsApp**: 905374092406
- **Admin**: admin / admin123

## 🔧 Özelleştirme

### Renkleri Değiştirmek
`styles.css` dosyasında:
```css
:root {
    --primary-navy: #1a237e;
    --primary-yellow: #ffd700;
}
```

### Admin Şifresini Değiştirmek
```php
// Yeni şifre hash'i oluştur
echo password_hash('yeni_sifre', PASSWORD_DEFAULT);
// Çıktıyı database.sql'deki admin şifresine yapıştır
```

## 📝 Notlar

- Tüm HTML dosyaları _old.html olarak yedeklendi
- PHP sayfaları dinamik ve veritabanı entegreli
- Tüm sayfalar mobil uyumlu
- SEO optimizasyonu yapılmış
- Bootstrap 5 kullanıldı

## 🚀 Canlıya Almak İçin

1. Dosyaları sunucuya yükleyin
2. `database.sql`'i import edin
3. `config/database.php`'de veritabanı bilgilerini güncelleyin
4. `BASE_URL` ve `ADMIN_URL` sabitlerini güncelleyin
5. Uploads klasörüne yazma izni verin (chmod 755)

## 📄 Lisans

Bu proje özel bir sipariş için oluşturulmuştur.

## 🆘 Destek

Sorun yaşarsanız:
1. Veritabanı bağlantısını kontrol edin
2. PHP 7.4+ kullandığınızdan emin olun
3. Error log'larını kontrol edin

---

**Geliştirici**: Claude AI
**Tarih**: 2024
**Versiyon**: 1.0
