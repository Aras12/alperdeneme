<?php
require_once 'config/database.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Geçersiz istek.';
    echo json_encode($response);
    exit;
}

// Spam protection - honeypot field
if (!empty($_POST['website'])) {
    $response['message'] = 'Spam tespit edildi.';
    echo json_encode($response);
    exit;
}

// Validate required fields
$required = ['name', 'email', 'comment', 'rating', 'page_type'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        $response['message'] = 'Lütfen tüm alanları doldurun.';
        echo json_encode($response);
        exit;
    }
}

// Sanitize inputs
$name = sanitize($_POST['name']);
$email = sanitize($_POST['email']);
$comment = sanitize($_POST['comment']);
$rating = (int)$_POST['rating'];
$page_type = sanitize($_POST['page_type']);
$page_id = isset($_POST['page_id']) ? (int)$_POST['page_id'] : 0;
$ip_address = $_SERVER['REMOTE_ADDR'] ?? '';

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Geçersiz e-posta adresi.';
    echo json_encode($response);
    exit;
}

// Validate rating
if ($rating < 1 || $rating > 5) {
    $rating = 5;
}

// Validate page_type
if (!in_array($page_type, ['homepage', 'blog', 'service'])) {
    $response['message'] = 'Geçersiz sayfa tipi.';
    echo json_encode($response);
    exit;
}

// Check for duplicate comment (same email, same page, within 5 minutes)
$check_duplicate = $conn->query("SELECT id FROM comments
    WHERE email='$email'
    AND page_type='$page_type'
    AND page_id=$page_id
    AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)");

if ($check_duplicate->num_rows > 0) {
    $response['message'] = 'Kısa süre önce yorum yaptınız. Lütfen birkaç dakika bekleyin.';
    echo json_encode($response);
    exit;
}

// Generate verification token
$verification_token = bin2hex(random_bytes(32));

// Insert comment
$sql = "INSERT INTO comments (page_type, page_id, name, email, comment, rating, verification_token, ip_address, is_approved, is_verified)
        VALUES ('$page_type', $page_id, '$name', '$email', '$comment', $rating, '$verification_token', '$ip_address', 0, 0)";

if ($conn->query($sql)) {
    // Send verification email (simulated)
    $verification_url = BASE_URL . 'verify-comment?token=' . $verification_token;

    // In production, send real email here:
    // mail($email, 'Yorumunuzu Doğrulayın', "Doğrulama linki: $verification_url");

    // For development, we'll auto-verify after 1 hour (or you can manually verify via link)
    // Simulated email content would be:
    /*
    Merhaba $name,

    Yorumunuz için teşekkürler! E-posta adresinizi doğrulamak için aşağıdaki linke tıklayın:

    $verification_url

    Doğrulama yapıldıktan sonra, yorumunuz yönetici onayından geçecek ve yayınlanacaktır.
    */

    $response['success'] = true;
    $response['message'] = 'Yorumunuz alındı! E-posta adresinize doğrulama linki gönderildi. (Geliştirme modunda: ' . $verification_url . ')';
    $response['verification_url'] = $verification_url; // For demo purposes
} else {
    $response['message'] = 'Yorum gönderilirken bir hata oluştu. Lütfen tekrar deneyin.';
}

echo json_encode($response);
