<?php
require_once '../config/database.php';
requireLogin();

// Get filters from URL
$date_filter = isset($_GET['date']) ? $_GET['date'] : 'all';
$device_filter = isset($_GET['device']) ? $_GET['device'] : 'all';
$source_filter = isset($_GET['source']) ? $_GET['source'] : 'all';

// Build where clause
$where_conditions = [];

// Date filter
switch($date_filter) {
    case 'today':
        $where_conditions[] = "DATE(created_at) = CURDATE()";
        break;
    case 'yesterday':
        $where_conditions[] = "DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        break;
    case 'week':
        $where_conditions[] = "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        break;
    case 'month':
        $where_conditions[] = "created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        break;
}

// Device filter
if ($device_filter == 'mobile') {
    $where_conditions[] = "is_mobile = 1";
} elseif ($device_filter == 'tablet') {
    $where_conditions[] = "is_tablet = 1";
} elseif ($device_filter == 'desktop') {
    $where_conditions[] = "is_mobile = 0 AND is_tablet = 0";
}

// Source filter
if ($source_filter != 'all') {
    if ($source_filter == 'direct') {
        $where_conditions[] = "(referrer IS NULL OR referrer = '')";
    } elseif ($source_filter == 'google') {
        $where_conditions[] = "referrer_domain LIKE '%google%'";
    } elseif ($source_filter == 'facebook') {
        $where_conditions[] = "referrer_domain LIKE '%facebook%'";
    } elseif ($source_filter == 'ads') {
        $where_conditions[] = "utm_source IS NOT NULL";
    }
}

$where_sql = count($where_conditions) > 0 ? implode(' AND ', $where_conditions) : '1=1';

// Fetch data
$visitors = $conn->query("SELECT * FROM visitors WHERE $where_sql ORDER BY created_at DESC");

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=ziyaretciler_' . date('Y-m-d_H-i-s') . '.csv');

// Create output stream
$output = fopen('php://output', 'w');

// Add BOM for UTF-8 Excel compatibility
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// CSV headers
fputcsv($output, [
    'ID',
    'Ziyaretçi ID',
    'IP Adresi',
    'Ülke',
    'Şehir',
    'Bölge',
    'Cihaz Türü',
    'Cihaz Markası',
    'Cihaz Modeli',
    'İşletim Sistemi',
    'OS Versiyonu',
    'Tarayıcı',
    'Tarayıcı Versiyonu',
    'Referrer',
    'Referrer Domain',
    'İlk Sayfa',
    'Mevcut Sayfa',
    'UTM Source',
    'UTM Medium',
    'UTM Campaign',
    'UTM Term',
    'UTM Content',
    'Anahtar Kelime',
    'Bot mu?',
    'Mobil mi?',
    'Tablet mi?',
    'Oturum Süresi (sn)',
    'Sayfa Görüntüleme',
    'İlk Ziyaret',
    'Son Görülme'
]);

// CSV data rows
while ($visitor = $visitors->fetch_assoc()) {
    fputcsv($output, [
        $visitor['id'],
        $visitor['visitor_id'],
        $visitor['ip_address'],
        $visitor['country'],
        $visitor['city'],
        $visitor['region'],
        $visitor['device_type'],
        $visitor['device_brand'],
        $visitor['device_model'],
        $visitor['os'],
        $visitor['os_version'],
        $visitor['browser'],
        $visitor['browser_version'],
        $visitor['referrer'],
        $visitor['referrer_domain'],
        $visitor['landing_page'],
        $visitor['current_page'],
        $visitor['utm_source'],
        $visitor['utm_medium'],
        $visitor['utm_campaign'],
        $visitor['utm_term'],
        $visitor['utm_content'],
        $visitor['keyword'],
        $visitor['is_bot'] ? 'Evet' : 'Hayır',
        $visitor['is_mobile'] ? 'Evet' : 'Hayır',
        $visitor['is_tablet'] ? 'Evet' : 'Hayır',
        $visitor['session_duration'],
        $visitor['page_views'],
        $visitor['created_at'],
        $visitor['last_seen']
    ]);
}

fclose($output);
exit;
?>
