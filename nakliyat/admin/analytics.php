<?php
$page_title = 'Analitik & İstatistikler';
include 'includes/header.php';

// Get date filter
$date_filter = isset($_GET['period']) ? $_GET['period'] : '30days';

$where_date = "1=1";
switch($date_filter) {
    case 'today':
        $where_date = "DATE(created_at) = CURDATE()";
        break;
    case 'yesterday':
        $where_date = "DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        break;
    case 'week':
        $where_date = "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        break;
    case 'month':
        $where_date = "created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        break;
    case '30days':
        $where_date = "created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        break;
    case 'year':
        $where_date = "created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
        break;
    case 'all':
        $where_date = "1=1";
        break;
}

// Overall Statistics
$total_visitors = $conn->query("SELECT COUNT(*) as count FROM visitors WHERE $where_date")->fetch_assoc()['count'];
$total_pageviews = $conn->query("SELECT SUM(page_views) as total FROM visitors WHERE $where_date")->fetch_assoc()['total'] ?? 0;
$unique_visitors = $conn->query("SELECT COUNT(DISTINCT visitor_id) as count FROM visitors WHERE $where_date")->fetch_assoc()['count'];
$avg_session = $conn->query("SELECT AVG(session_duration) as avg FROM visitors WHERE $where_date AND session_duration > 0")->fetch_assoc()['avg'] ?? 0;

// Traffic Sources
$sources_data = [];
$sources_query = $conn->query("SELECT
    CASE
        WHEN utm_source IS NOT NULL THEN utm_source
        WHEN referrer_domain LIKE '%google%' THEN 'Google (Organic)'
        WHEN referrer_domain LIKE '%facebook%' THEN 'Facebook'
        WHEN referrer_domain LIKE '%instagram%' THEN 'Instagram'
        WHEN referrer_domain LIKE '%youtube%' THEN 'YouTube'
        WHEN referrer_domain IS NULL OR referrer_domain = '' THEN 'Direct'
        ELSE 'Other'
    END as source,
    COUNT(*) as count
    FROM visitors
    WHERE $where_date
    GROUP BY source
    ORDER BY count DESC
    LIMIT 10");
while($row = $sources_query->fetch_assoc()) {
    $sources_data[] = $row;
}

// Device Types
$device_data = $conn->query("SELECT
    CASE
        WHEN is_mobile = 1 THEN 'Mobile'
        WHEN is_tablet = 1 THEN 'Tablet'
        ELSE 'Desktop'
    END as device,
    COUNT(*) as count
    FROM visitors
    WHERE $where_date
    GROUP BY device")->fetch_all(MYSQLI_ASSOC);

// Browser Distribution
$browser_data = $conn->query("SELECT browser, COUNT(*) as count
    FROM visitors
    WHERE $where_date AND browser IS NOT NULL AND browser != ''
    GROUP BY browser
    ORDER BY count DESC
    LIMIT 8")->fetch_all(MYSQLI_ASSOC);

// OS Distribution
$os_data = $conn->query("SELECT os, COUNT(*) as count
    FROM visitors
    WHERE $where_date AND os IS NOT NULL AND os != ''
    GROUP BY os
    ORDER BY count DESC
    LIMIT 8")->fetch_all(MYSQLI_ASSOC);

// Top Cities
$cities_data = $conn->query("SELECT city, country, COUNT(*) as count
    FROM visitors
    WHERE $where_date AND city IS NOT NULL AND city != ''
    GROUP BY city, country
    ORDER BY count DESC
    LIMIT 10")->fetch_all(MYSQLI_ASSOC);

// Top Pages
$pages_data = $conn->query("SELECT current_page, COUNT(*) as count
    FROM visitors
    WHERE $where_date AND current_page IS NOT NULL
    GROUP BY current_page
    ORDER BY count DESC
    LIMIT 10")->fetch_all(MYSQLI_ASSOC);

// Visitor Timeline (Last 30 days)
$timeline_data = $conn->query("SELECT DATE(created_at) as date, COUNT(*) as count
    FROM visitors
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC")->fetch_all(MYSQLI_ASSOC);

// Top Keywords
$keywords_data = $conn->query("SELECT keyword, COUNT(*) as count
    FROM visitors
    WHERE $where_date AND keyword IS NOT NULL AND keyword != ''
    GROUP BY keyword
    ORDER BY count DESC
    LIMIT 10")->fetch_all(MYSQLI_ASSOC);

// Device Brands
$brands_data = $conn->query("SELECT device_brand, COUNT(*) as count
    FROM visitors
    WHERE $where_date AND device_brand IS NOT NULL AND device_brand != ''
    GROUP BY device_brand
    ORDER BY count DESC
    LIMIT 8")->fetch_all(MYSQLI_ASSOC);
?>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<div class="content-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-chart-line me-2"></i>Analitik & İstatistikler</h5>
        <div>
            <select class="form-select" onchange="window.location.href='?period='+this.value">
                <option value="today" <?= $date_filter == 'today' ? 'selected' : '' ?>>Bugün</option>
                <option value="yesterday" <?= $date_filter == 'yesterday' ? 'selected' : '' ?>>Dün</option>
                <option value="week" <?= $date_filter == 'week' ? 'selected' : '' ?>>Son 7 Gün</option>
                <option value="30days" <?= $date_filter == '30days' ? 'selected' : '' ?>>Son 30 Gün</option>
                <option value="month" <?= $date_filter == 'month' ? 'selected' : '' ?>>Bu Ay</option>
                <option value="year" <?= $date_filter == 'year' ? 'selected' : '' ?>>Bu Yıl</option>
                <option value="all" <?= $date_filter == 'all' ? 'selected' : '' ?>>Tüm Zamanlar</option>
            </select>
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-primary">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-content">
                    <h3><?= number_format($total_visitors) ?></h3>
                    <p>Toplam Ziyaretçi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-success">
                <div class="stat-icon"><i class="fas fa-eye"></i></div>
                <div class="stat-content">
                    <h3><?= number_format($total_pageviews) ?></h3>
                    <p>Sayfa Görüntüleme</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-info">
                <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                <div class="stat-content">
                    <h3><?= number_format($unique_visitors) ?></h3>
                    <p>Benzersiz Ziyaretçi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-warning">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-content">
                    <h3><?= gmdate('i:s', $avg_session) ?></h3>
                    <p>Ort. Oturum Süresi</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="content-card">
            <h6 class="mb-3"><i class="fas fa-chart-area me-2"></i>Ziyaretçi Trendi (Son 30 Gün)</h6>
            <canvas id="timelineChart" height="80"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="content-card">
            <h6 class="mb-3"><i class="fas fa-mobile-alt me-2"></i>Cihaz Türleri</h6>
            <canvas id="deviceChart"></canvas>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="content-card">
            <h6 class="mb-3"><i class="fas fa-share-alt me-2"></i>Trafik Kaynakları</h6>
            <canvas id="sourcesChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="content-card">
            <h6 class="mb-3"><i class="fas fa-globe me-2"></i>Tarayıcı Dağılımı</h6>
            <canvas id="browserChart"></canvas>
        </div>
    </div>
</div>

<!-- Charts Row 3 -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="content-card">
            <h6 class="mb-3"><i class="fas fa-desktop me-2"></i>İşletim Sistemi</h6>
            <canvas id="osChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="content-card">
            <h6 class="mb-3"><i class="fas fa-mobile me-2"></i>Cihaz Markaları</h6>
            <canvas id="brandsChart"></canvas>
        </div>
    </div>
</div>

<!-- Data Tables Row -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="content-card">
            <h6 class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>En Çok Ziyaret Eden Şehirler</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Şehir</th>
                            <th>Ülke</th>
                            <th class="text-end">Ziyaretçi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($cities_data) > 0): ?>
                            <?php foreach($cities_data as $city): ?>
                            <tr>
                                <td><?= htmlspecialchars($city['city']) ?></td>
                                <td><?= htmlspecialchars($city['country']) ?></td>
                                <td class="text-end"><strong><?= number_format($city['count']) ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted">Veri yok</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="content-card">
            <h6 class="mb-3"><i class="fas fa-file me-2"></i>En Çok Ziyaret Edilen Sayfalar</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Sayfa</th>
                            <th class="text-end">Görüntülenme</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($pages_data) > 0): ?>
                            <?php foreach($pages_data as $page): ?>
                            <tr>
                                <td><small><?= htmlspecialchars(substr($page['current_page'], 0, 50)) ?><?= strlen($page['current_page']) > 50 ? '...' : '' ?></small></td>
                                <td class="text-end"><strong><?= number_format($page['count']) ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2" class="text-center text-muted">Veri yok</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Keywords Table -->
<?php if(count($keywords_data) > 0): ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="content-card">
            <h6 class="mb-3"><i class="fas fa-search me-2"></i>En Çok Aranan Anahtar Kelimeler</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Anahtar Kelime</th>
                            <th class="text-end">Arama Sayısı</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($keywords_data as $keyword): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($keyword['keyword']) ?></strong></td>
                            <td class="text-end"><span class="badge bg-primary"><?= number_format($keyword['count']) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// Chart.js configuration
Chart.defaults.font.family = 'Arial, sans-serif';
Chart.defaults.font.size = 12;

// Timeline Chart
const timelineCtx = document.getElementById('timelineChart').getContext('2d');
new Chart(timelineCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($timeline_data, 'date')) ?>,
        datasets: [{
            label: 'Ziyaretçi Sayısı',
            data: <?= json_encode(array_column($timeline_data, 'count')) ?>,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

// Device Chart
const deviceCtx = document.getElementById('deviceChart').getContext('2d');
new Chart(deviceCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($device_data, 'device')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($device_data, 'count')) ?>,
            backgroundColor: [
                'rgb(54, 162, 235)',
                'rgb(255, 99, 132)',
                'rgb(75, 192, 192)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Sources Chart
const sourcesCtx = document.getElementById('sourcesChart').getContext('2d');
new Chart(sourcesCtx, {
    type: 'pie',
    data: {
        labels: <?= json_encode(array_column($sources_data, 'source')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($sources_data, 'count')) ?>,
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)',
                'rgb(255, 159, 64)',
                'rgb(199, 199, 199)',
                'rgb(83, 102, 255)',
                'rgb(255, 99, 255)',
                'rgb(99, 255, 132)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Browser Chart
const browserCtx = document.getElementById('browserChart').getContext('2d');
new Chart(browserCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($browser_data, 'browser')) ?>,
        datasets: [{
            label: 'Ziyaretçi',
            data: <?= json_encode(array_column($browser_data, 'count')) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.8)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

// OS Chart
const osCtx = document.getElementById('osChart').getContext('2d');
new Chart(osCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($os_data, 'os')) ?>,
        datasets: [{
            label: 'Ziyaretçi',
            data: <?= json_encode(array_column($os_data, 'count')) ?>,
            backgroundColor: 'rgba(255, 99, 132, 0.8)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

// Brands Chart
const brandsCtx = document.getElementById('brandsChart').getContext('2d');
new Chart(brandsCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($brands_data, 'device_brand')) ?>,
        datasets: [{
            label: 'Ziyaretçi',
            data: <?= json_encode(array_column($brands_data, 'count')) ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.8)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>

<style>
.stat-card {
    border-radius: 8px;
    padding: 20px;
    color: white;
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}
.stat-card .stat-icon {
    font-size: 40px;
    margin-right: 15px;
    opacity: 0.8;
}
.stat-card .stat-content h3 {
    margin: 0;
    font-size: 28px;
    font-weight: bold;
}
.stat-card .stat-content p {
    margin: 0;
    font-size: 14px;
    opacity: 0.9;
}
.stat-card.bg-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card.bg-success { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card.bg-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-card.bg-warning { background: linear-gradient(135deg, #fad0c4 0%, #ffd1ff 100%); color: #333; }
</style>

<?php include 'includes/footer.php'; ?>
