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

// Base URL Configuration
define('BASE_URL', 'http://localhost/nakliyat/');
define('ADMIN_URL', BASE_URL . 'admin/');

// Upload Directory
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

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
    while ($row = $result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

$settings = getSettings();
?>
