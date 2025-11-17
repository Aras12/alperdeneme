<?php
require_once __DIR__ . '/config/database.php';

// Check if IP is blocked
function isIPBlocked($ip) {
    global $conn;
    $result = $conn->query("SELECT id FROM blocked_ips WHERE ip_address='$ip' LIMIT 1");
    return $result->num_rows > 0;
}

// Get visitor IP
function getVisitorIP() {
    $ip = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// Get location from IP (using free ipapi.co service)
function getLocationFromIP($ip) {
    // Skip for localhost
    if ($ip == '127.0.0.1' || $ip == '::1' || strpos($ip, '192.168.') === 0 || strpos($ip, '10.') === 0) {
        return [
            'country' => 'Turkey',
            'city' => 'Adana',
            'region' => 'Adana'
        ];
    }

    // Use ipapi.co (free, no API key needed, 1000 req/day)
    $url = "https://ipapi.co/{$ip}/json/";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $data = json_decode($response, true);
        if ($data && !isset($data['error'])) {
            return [
                'country' => $data['country_name'] ?? 'Unknown',
                'city' => $data['city'] ?? 'Unknown',
                'region' => $data['region'] ?? 'Unknown'
            ];
        }
    }

    return ['country' => 'Unknown', 'city' => 'Unknown', 'region' => 'Unknown'];
}

// Parse User Agent
function parseUserAgent($ua) {
    $data = [
        'device_type' => 'Desktop',
        'device_brand' => 'Unknown',
        'device_model' => 'Unknown',
        'os' => 'Unknown',
        'os_version' => '',
        'browser' => 'Unknown',
        'browser_version' => '',
        'is_mobile' => 0,
        'is_tablet' => 0,
        'is_bot' => 0
    ];

    // Check for bots
    $bots = ['bot', 'crawl', 'spider', 'slurp', 'mediapartners', 'apis-google', 'adsbot', 'googlebot'];
    foreach ($bots as $bot) {
        if (stripos($ua, $bot) !== false) {
            $data['is_bot'] = 1;
            return $data;
        }
    }

    // Detect mobile/tablet
    if (preg_match('/(android|iphone|ipad|ipod|blackberry|windows phone)/i', $ua)) {
        $data['is_mobile'] = 1;
        if (preg_match('/(ipad|tablet|playbook)/i', $ua)) {
            $data['is_tablet'] = 1;
            $data['device_type'] = 'Tablet';
        } else {
            $data['device_type'] = 'Mobile';
        }
    }

    // Detect OS
    if (preg_match('/windows nt ([\d\.]+)/i', $ua, $match)) {
        $data['os'] = 'Windows';
        $data['os_version'] = $match[1];
    } elseif (preg_match('/android ([\d\.]+)/i', $ua, $match)) {
        $data['os'] = 'Android';
        $data['os_version'] = $match[1];
    } elseif (preg_match('/iphone os ([\d_]+)/i', $ua, $match)) {
        $data['os'] = 'iOS';
        $data['os_version'] = str_replace('_', '.', $match[1]);
    } elseif (preg_match('/mac os x ([\d_]+)/i', $ua, $match)) {
        $data['os'] = 'macOS';
        $data['os_version'] = str_replace('_', '.', $match[1]);
    } elseif (preg_match('/linux/i', $ua)) {
        $data['os'] = 'Linux';
    }

    // Detect Browser
    if (preg_match('/edg\/([\d\.]+)/i', $ua, $match)) {
        $data['browser'] = 'Edge';
        $data['browser_version'] = $match[1];
    } elseif (preg_match('/chrome\/([\d\.]+)/i', $ua, $match)) {
        $data['browser'] = 'Chrome';
        $data['browser_version'] = $match[1];
    } elseif (preg_match('/safari\/([\d\.]+)/i', $ua, $match)) {
        $data['browser'] = 'Safari';
        $data['browser_version'] = $match[1];
    } elseif (preg_match('/firefox\/([\d\.]+)/i', $ua, $match)) {
        $data['browser'] = 'Firefox';
        $data['browser_version'] = $match[1];
    } elseif (preg_match('/opera\/([\d\.]+)/i', $ua, $match)) {
        $data['browser'] = 'Opera';
        $data['browser_version'] = $match[1];
    }

    // Detect Device Brand (Mobile only)
    if ($data['is_mobile']) {
        if (preg_match('/(samsung|sm-)/i', $ua)) {
            $data['device_brand'] = 'Samsung';
        } elseif (preg_match('/(iphone|ipad|ipod)/i', $ua)) {
            $data['device_brand'] = 'Apple';
        } elseif (preg_match('/huawei/i', $ua)) {
            $data['device_brand'] = 'Huawei';
        } elseif (preg_match('/xiaomi/i', $ua)) {
            $data['device_brand'] = 'Xiaomi';
        } elseif (preg_match('/oppo/i', $ua)) {
            $data['device_brand'] = 'Oppo';
        } elseif (preg_match('/vivo/i', $ua)) {
            $data['device_brand'] = 'Vivo';
        } elseif (preg_match('/nokia/i', $ua)) {
            $data['device_brand'] = 'Nokia';
        } elseif (preg_match('/lg/i', $ua)) {
            $data['device_brand'] = 'LG';
        } elseif (preg_match('/motorola|moto/i', $ua)) {
            $data['device_brand'] = 'Motorola';
        }

        // Try to extract model
        if (preg_match('/(SM-[A-Z0-9]+)/i', $ua, $match)) {
            $data['device_model'] = $match[1];
        } elseif (preg_match('/(iPhone\d+,\d+)/i', $ua, $match)) {
            $data['device_model'] = $match[1];
        }
    }

    return $data;
}

// Track visitor
function trackVisitor() {
    global $conn;

    // Get IP
    $ip = getVisitorIP();

    // Check if blocked
    if (isIPBlocked($ip)) {
        http_response_code(403);
        die('Access denied');
    }

    // Get or create visitor ID (cookie-based)
    if (isset($_COOKIE['visitor_id'])) {
        $visitor_id = $_COOKIE['visitor_id'];
    } else {
        $visitor_id = md5($ip . time() . rand());
        setcookie('visitor_id', $visitor_id, time() + (365 * 24 * 60 * 60), '/'); // 1 year
    }

    // Get location
    $location = getLocationFromIP($ip);

    // Parse user agent
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $device_info = parseUserAgent($ua);

    // Get referrer info
    $referrer = $_SERVER['HTTP_REFERER'] ?? '';
    $referrer_domain = '';
    if ($referrer) {
        $parsed = parse_url($referrer);
        $referrer_domain = $parsed['host'] ?? '';
    }

    // Get current page
    $current_page = $_SERVER['REQUEST_URI'] ?? '';
    $full_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $current_page;

    // Extract UTM parameters
    $utm_source = $_GET['utm_source'] ?? null;
    $utm_medium = $_GET['utm_medium'] ?? null;
    $utm_campaign = $_GET['utm_campaign'] ?? null;
    $utm_term = $_GET['utm_term'] ?? null;
    $utm_content = $_GET['utm_content'] ?? null;

    // Extract keyword from Google referrer
    $keyword = null;
    if ($referrer_domain && (strpos($referrer_domain, 'google') !== false)) {
        if (preg_match('/[?&]q=([^&]+)/', $referrer, $match)) {
            $keyword = urldecode($match[1]);
        }
    }

    // Check if visitor exists
    $existing = $conn->query("SELECT id, page_views FROM visitors WHERE visitor_id='$visitor_id' LIMIT 1");

    if ($existing->num_rows > 0) {
        // Update existing visitor
        $row = $existing->fetch_assoc();
        $new_page_views = $row['page_views'] + 1;
        $conn->query("UPDATE visitors SET
            current_page='" . $conn->real_escape_string($current_page) . "',
            page_views=$new_page_views,
            last_seen=NOW()
            WHERE visitor_id='$visitor_id'");

        // Record page view
        $page_title = ''; // Will be filled by JavaScript
        $conn->query("INSERT INTO page_views (visitor_id, page_url, page_title) VALUES
            ('$visitor_id', '" . $conn->real_escape_string($full_url) . "', '')");

    } else {
        // Insert new visitor
        $sql = "INSERT INTO visitors (
            visitor_id, ip_address, country, city, region,
            user_agent, device_type, device_brand, device_model,
            os, os_version, browser, browser_version,
            referrer, referrer_domain, landing_page, current_page,
            utm_source, utm_medium, utm_campaign, utm_term, utm_content, keyword,
            is_bot, is_mobile, is_tablet
        ) VALUES (
            '$visitor_id',
            '" . $conn->real_escape_string($ip) . "',
            '" . $conn->real_escape_string($location['country']) . "',
            '" . $conn->real_escape_string($location['city']) . "',
            '" . $conn->real_escape_string($location['region']) . "',
            '" . $conn->real_escape_string($ua) . "',
            '" . $conn->real_escape_string($device_info['device_type']) . "',
            '" . $conn->real_escape_string($device_info['device_brand']) . "',
            '" . $conn->real_escape_string($device_info['device_model']) . "',
            '" . $conn->real_escape_string($device_info['os']) . "',
            '" . $conn->real_escape_string($device_info['os_version']) . "',
            '" . $conn->real_escape_string($device_info['browser']) . "',
            '" . $conn->real_escape_string($device_info['browser_version']) . "',
            '" . $conn->real_escape_string($referrer) . "',
            '" . $conn->real_escape_string($referrer_domain) . "',
            '" . $conn->real_escape_string($current_page) . "',
            '" . $conn->real_escape_string($current_page) . "',
            " . ($utm_source ? "'" . $conn->real_escape_string($utm_source) . "'" : "NULL") . ",
            " . ($utm_medium ? "'" . $conn->real_escape_string($utm_medium) . "'" : "NULL") . ",
            " . ($utm_campaign ? "'" . $conn->real_escape_string($utm_campaign) . "'" : "NULL") . ",
            " . ($utm_term ? "'" . $conn->real_escape_string($utm_term) . "'" : "NULL") . ",
            " . ($utm_content ? "'" . $conn->real_escape_string($utm_content) . "'" : "NULL") . ",
            " . ($keyword ? "'" . $conn->real_escape_string($keyword) . "'" : "NULL") . ",
            {$device_info['is_bot']},
            {$device_info['is_mobile']},
            {$device_info['is_tablet']}
        )";

        $conn->query($sql);

        // Record first page view
        $conn->query("INSERT INTO page_views (visitor_id, page_url, page_title) VALUES
            ('$visitor_id', '" . $conn->real_escape_string($full_url) . "', '')");
    }
}

// Run tracking (only if not in admin panel or tracking scripts)
if (!isset($_SERVER['REQUEST_URI']) || (
    strpos($_SERVER['REQUEST_URI'], '/admin/') === false &&
    strpos($_SERVER['REQUEST_URI'], 'track-visitor.php') === false &&
    strpos($_SERVER['REQUEST_URI'], 'submit-comment.php') === false
)) {
    trackVisitor();
}
?>
