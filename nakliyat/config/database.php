<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'adana_cekici');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Auto-detect Base URL (works on any domain)
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];

// Get base path - works for root or subdirectory installations
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = '';

// If in subdirectory (e.g., localhost/nakliyat/)
if (strpos($script_name, '/nakliyat/') !== false) {
    $base_path = '/nakliyat';
}
// If in admin folder, go up one level
elseif (strpos($script_name, '/admin/') !== false) {
    $base_path = rtrim(str_replace('/admin', '', dirname($script_name)), '/');
}
// Otherwise use parent directory of script
else {
    $base_path = rtrim(dirname($script_name), '/');
}

// Clean up base path
$base_path = str_replace('\\', '/', $base_path);
$base_path = ($base_path === '/' || $base_path === '') ? '' : $base_path;

define('BASE_URL', $protocol . '://' . $host . $base_path . '/');
define('ADMIN_URL', BASE_URL . 'admin/');

// Upload Directory - Using img/ instead of uploads/
define('UPLOAD_DIR', __DIR__ . '/../img/');
define('UPLOAD_URL', BASE_URL . 'img/');

// Session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper functions
function redirect($url) {
    header("Location: " . $url);
    exit();
}

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

function alert($message, $type = 'success') {
    return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                ' . $message . '
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect(ADMIN_URL . 'login.php');
    }
}

function getSettings() {
    global $conn;
    $settings = [];
    $result = $conn->query("SELECT setting_key, setting_value FROM settings");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $settings;
}

// File upload helper
function uploadImage($file, $prefix = 'img') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $filename = $file['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return false;
    }

    // Create img directory if not exists
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    $new_filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $ext;
    $destination = UPLOAD_DIR . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return UPLOAD_URL . $new_filename;
    }

    return false;
}

$settings = getSettings();
?>
