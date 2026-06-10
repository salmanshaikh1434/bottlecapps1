<?php
/**
 * Geliştirilmiş WordPress Cache Purger
 * Tüm cache sistemlerini agresif şekilde temizler
 */

// Güvenlik kontrolü - İsteğe bağlı, token ile koruma
$security_token = 'YOUR_SECRET_TOKEN_HERE'; // Değiştir!
if (isset($_GET['token']) && $_GET['token'] !== $security_token && $security_token !== 'YOUR_SECRET_TOKEN_HERE') {
    die('Yetkisiz erişim!');
}

// WordPress'i yükle
$wp_load_paths = [
    'wp-load.php',
    '../wp-load.php',
    '../../wp-load.php',
    '../../../wp-load.php'
];

foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once($path);
        break;
    }
}

$results = [];
$success_count = 0;
$fail_count = 0;
$start_time = microtime(true);

// Hata raporlama
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(600); // 10 dakika

// ==================== APACHE .HTACCESS CACHE TEMİZLE ====================

function clear_htaccess_cache() {
    global $results, $success_count;
    
    $htaccess_path = ABSPATH . '.htaccess';
    if (file_exists($htaccess_path)) {
        // .htaccess'i oku
        $content = file_get_contents($htaccess_path);
        
        // Cache header'larını kaldır
        $cache_patterns = [
            '/# BEGIN Cache.*?# END Cache/s',
            '/# BEGIN Browser Caching.*?# END Browser Caching/s',
            '/<IfModule mod_expires\.c>.*?<\/IfModule>/s',
            '/<IfModule mod_headers\.c>.*?Header set Cache-Control.*?<\/IfModule>/s'
        ];
        
        foreach ($cache_patterns as $pattern) {
            $content = preg_replace($pattern, '', $content);
        }
        
        // Geçici olarak cache'i devre dışı bırak
        $no_cache = "\n# TEMP NO CACHE\n<IfModule mod_headers.c>\n";
        $no_cache .= "    Header set Cache-Control \"no-cache, no-store, must-revalidate\"\n";
        $no_cache .= "    Header set Pragma \"no-cache\"\n";
        $no_cache .= "    Header set Expires 0\n";
        $no_cache .= "</IfModule>\n";
        
        if (file_put_contents($htaccess_path, $content . $no_cache)) {
            $results[] = "✅ .htaccess cache kuralları geçici olarak devre dışı bırakıldı.";
            $success_count++;
        }
    }
}

// ==================== SUNUCU CACHE'LERİ ====================

// OPcache - Agresif temizleme
if (function_exists('opcache_reset')) {
    opcache_reset();
    if (function_exists('opcache_invalidate')) {
        // Tüm PHP dosyalarını invalidate et
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(ABSPATH, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                opcache_invalidate($file->getRealPath(), true);
            }
        }
    }
    $results[] = "✅ OPcache agresif temizlik yapıldı.";
    $success_count++;
}

// APCu
if (function_exists('apcu_clear_cache')) {
    apcu_clear_cache();
    $results[] = "✅ APCu temizlendi.";
    $success_count++;
}

// Memcache
if (class_exists('Memcache')) {
    try {
        $memcache = new Memcache();
        $servers = [
            ['localhost', 11211],
            ['127.0.0.1', 11211],
        ];
        foreach ($servers as $server) {
            if (@$memcache->connect($server[0], $server[1])) {
                $memcache->flush();
                $memcache->close();
                $results[] = "✅ Memcache temizlendi ({$server[0]}).";
                $success_count++;
            }
        }
    } catch (Exception $e) {
        $results[] = "⚠️ Memcache: " . $e->getMessage();
    }
}

// Memcached
if (class_exists('Memcached')) {
    try {
        $memcached = new Memcached();
        $memcached->addServer('localhost', 11211);
        $memcached->addServer('127.0.0.1', 11211);
        $memcached->flush();
        $results[] = "✅ Memcached temizlendi.";
        $success_count++;
    } catch (Exception $e) {
        $results[] = "⚠️ Memcached: " . $e->getMessage();
    }
}

// Redis - Tüm database'leri temizle
if (class_exists('Redis')) {
    try {
        $redis = new Redis();
        $ports = [6379, 6380];
        foreach ($ports as $port) {
            if (@$redis->connect('127.0.0.1', $port, 2)) {
                // Tüm database'leri temizle (0-15)
                for ($db = 0; $db < 16; $db++) {
                    $redis->select($db);
                    $redis->flushDB();
                }
                $redis->close();
                $results[] = "✅ Redis temizlendi (port: $port).";
                $success_count++;
            }
        }
    } catch (Exception $e) {
        $results[] = "⚠️ Redis: " . $e->getMessage();
    }
}

// ==================== WORDPRESS CACHE'LERİ ====================

if (defined('ABSPATH')) {
    
    // WordPress Object Cache - Zorla temizle
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
        $results[] = "✅ WordPress Object Cache temizlendi.";
        $success_count++;
    }
    
    // Tüm cache'leri zorla temizle
    wp_cache_delete_multiple([
        'alloptions',
        'notoptions',
    ], 'options');
    
    // Transient'leri temizle
    global $wpdb;
    if ($wpdb) {
        // Tüm transient'leri sil
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_transient_%'");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_site_transient_%'");
        
        // Orphaned metadata temizle
        $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE '%_cache%'");
        
        // Cron lock'ları
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_cron%'");
        
        $results[] = "✅ Tüm transient'ler ve cache metadata silindi.";
        $success_count++;
    }
    
    // Rewrite rules
    delete_option('rewrite_rules');
    flush_rewrite_rules(true);
    $results[] = "✅ Rewrite rules zorlandı.";
    $success_count++;
    
    // Permalink cache temizle
    delete_option('permalink_structure');
    flush_rewrite_rules(true);
    
    // Theme mod cache
    remove_theme_mods();
    
    // WP Super Cache
    if (function_exists('wp_cache_clear_cache')) {
        @wp_cache_clear_cache();
        $results[] = "✅ WP Super Cache temizlendi.";
        $success_count++;
    }
    
    // W3 Total Cache - Tüm cache türleri
    if (function_exists('w3tc_flush_all')) {
        w3tc_flush_all();
        if (function_exists('w3tc_flush_posts')) w3tc_flush_posts();
        if (function_exists('w3tc_flush_dbcache')) w3tc_flush_dbcache();
        if (function_exists('w3tc_flush_objectcache')) w3tc_flush_objectcache();
        if (function_exists('w3tc_flush_minify')) w3tc_flush_minify();
        $results[] = "✅ W3 Total Cache (tüm türler) temizlendi.";
        $success_count++;
    }
    
    // WP Rocket
    if (function_exists('rocket_clean_domain')) {
        rocket_clean_domain();
        if (function_exists('rocket_clean_minify')) rocket_clean_minify();
        if (function_exists('rocket_clean_cache_busting')) rocket_clean_cache_busting();
        $results[] = "✅ WP Rocket temizlendi.";
        $success_count++;
    }
    
    // WP Fastest Cache
    if (class_exists('WpFastestCache')) {
        try {
            $wpfc = new WpFastestCache();
            $wpfc->deleteCache();
            $wpfc->deleteCache(true); // Minified files
            $results[] = "✅ WP Fastest Cache temizlendi.";
            $success_count++;
        } catch (Exception $e) {}
    }
    
    // Autoptimize
    if (class_exists('autoptimizeCache')) {
        autoptimizeCache::clearall();
        $results[] = "✅ Autoptimize temizlendi.";
        $success_count++;
    }
    
    // LiteSpeed Cache - Tüm purge seçenekleri
    if (defined('LSCWP_V') || class_exists('LiteSpeed_Cache')) {
        do_action('litespeed_purge_all');
        do_action('litespeed_purge_cssjs');
        do_action('litespeed_purge_ccss');
        do_action('litespeed_purge_ucss');
        do_action('litespeed_purge_object');
        do_action('litespeed_purge_opcache');
        $results[] = "✅ LiteSpeed Cache (WP Plugin) temizlendi.";
        $success_count++;
    }
    
    // Cache Enabler
    if (class_exists('Cache_Enabler')) {
        Cache_Enabler::clear_total_cache();
        $results[] = "✅ Cache Enabler temizlendi.";
        $success_count++;
    }
    
    // Comet Cache
    if (class_exists('comet_cache')) {
        comet_cache::clear();
        $results[] = "✅ Comet Cache temizlendi.";
        $success_count++;
    }
    
    // SG Optimizer (SiteGround)
    if (function_exists('sg_cachepress_purge_cache')) {
        sg_cachepress_purge_cache();
        $results[] = "✅ SG Optimizer temizlendi.";
        $success_count++;
    }
    
    // Hummingbird
    if (class_exists('Hummingbird\WP_Hummingbird')) {
        do_action('wphb_clear_page_cache');
        $results[] = "✅ Hummingbird temizlendi.";
        $success_count++;
    }
    
    // Swift Performance
    if (class_exists('Swift_Performance_Cache')) {
        Swift_Performance_Cache::clear_all_cache();
        $results[] = "✅ Swift Performance temizlendi.";
        $success_count++;
    }
}

// ==================== LITESPEED SUNUCU CACHE ====================

// LiteSpeed purge header'ları
if (!headers_sent()) {
    header('X-LiteSpeed-Purge: *');
    header('X-LiteSpeed-Cache-Control: no-cache');
    $results[] = "✅ LiteSpeed purge header'ları gönderildi.";
    $success_count++;
}

// LiteSpeed cache dizinleri
$litespeed_dirs = [
    '/tmp/lshttpd/',
    '/usr/local/lsws/cachedata/',
    '/home/*/lsws/cachedata/',
    ABSPATH . '.litespeed_cache/'
];

foreach ($litespeed_dirs as $pattern) {
    foreach (glob($pattern, GLOB_ONLYDIR) as $dir) {
        if (is_dir($dir) && is_writable($dir)) {
            $deleted = delete_directory_contents($dir);
            if ($deleted > 0) {
                $results[] = "✅ LiteSpeed cache: $deleted dosya silindi ($dir)";
                $success_count++;
            }
        }
    }
}

// ==================== CDN CACHE TEMİZLEME ====================

// Cloudflare - Gelişmiş temizleme
function purge_cloudflare() {
    global $results, $success_count;
    
    // WordPress'te kayıtlı CF bilgilerini kontrol et
    $cf_email = get_option('cloudflare_email', '');
    $cf_api_key = get_option('cloudflare_api_key', '');
    $cf_zone_id = get_option('cloudflare_zone_id', '');
    
    // Manuel değerler (eğer option'da yoksa)
    if (empty($cf_email)) $cf_email = ''; // BURAYA GİR
    if (empty($cf_api_key)) $cf_api_key = ''; // BURAYA GİR
    if (empty($cf_zone_id)) $cf_zone_id = ''; // BURAYA GİR
    
    if ($cf_email && $cf_api_key && $cf_zone_id) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.cloudflare.com/client/v4/zones/$cf_zone_id/purge_cache");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-Auth-Email: $cf_email",
            "X-Auth-Key: $cf_api_key",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "purge_everything" => true
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpcode == 200) {
            $results[] = "✅ Cloudflare cache API ile temizlendi.";
            $success_count++;
        } else {
            $results[] = "⚠️ Cloudflare API yanıtı: HTTP $httpcode";
        }
    }
}

purge_cloudflare();

// ==================== DOSYA BAZLI CACHE AGRESİF TEMİZLEME ====================

function delete_directory_contents($dir, $delete_dir = false) {
    $deleted = 0;
    if (!is_dir($dir)) return 0;
    
    try {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($iterator as $file) {
            try {
                if ($file->isFile()) {
                    if (@unlink($file->getRealPath())) {
                        $deleted++;
                    }
                } elseif ($file->isDir()) {
                    @rmdir($file->getRealPath());
                }
            } catch (Exception $e) {}
        }
        
        if ($delete_dir) {
            @rmdir($dir);
        }
    } catch (Exception $e) {}
    
    return $deleted;
}

// Tüm olası cache dizinleri
$cache_dirs = [
    ABSPATH . 'wp-content/cache/',
    ABSPATH . 'wp-content/uploads/cache/',
    ABSPATH . 'wp-content/w3tc-cache/',
    ABSPATH . 'wp-content/wp-cache/',
    ABSPATH . 'wp-content/wp-rocket-cache/',
    ABSPATH . 'wp-content/endurance-page-cache/',
    ABSPATH . 'wp-content/et-cache/',
    ABSPATH . 'wp-content/uploads/wp-fastest-cache/',
    ABSPATH . 'wp-content/wflogs/',
    ABSPATH . 'wp-content/autoptimize/',
    ABSPATH . 'wp-content/litespeed/',
    ABSPATH . 'wp-content/advanced-cache.php',
    ABSPATH . 'wp-content/object-cache.php',
];

foreach ($cache_dirs as $cache_path) {
    if (is_file($cache_path)) {
        if (@unlink($cache_path)) {
            $results[] = "✅ Cache dosyası silindi: " . basename($cache_path);
            $success_count++;
        }
    } elseif (is_dir($cache_path)) {
        $deleted = delete_directory_contents($cache_path);
        if ($deleted > 0) {
            $results[] = "✅ Cache dizini temizlendi: " . basename($cache_path) . " ($deleted dosya)";
            $success_count++;
        }
    }
}

// .htaccess cache temizle
clear_htaccess_cache();

// ==================== VERİTABANI OPTİMİZASYON ====================

if (defined('ABSPATH') && $wpdb) {
    // Optimize tables
    $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
    $optimized = 0;
    foreach ($tables as $table) {
        $wpdb->query("OPTIMIZE TABLE " . $table[0]);
        $optimized++;
    }
    $results[] = "✅ $optimized tablo optimize edildi.";
    $success_count++;
    
    // Revisions
    $revisions = $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type = 'revision'");
    if ($revisions > 0) $results[] = "✅ $revisions revision silindi.";
    
    // Auto-drafts
    $drafts = $wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_status = 'auto-draft' AND post_modified < DATE_SUB(NOW(), INTERVAL 7 DAY)");
    if ($drafts > 0) $results[] = "✅ $drafts auto-draft silindi.";
    
    // Spam comments
    $spam = $wpdb->query("DELETE FROM {$wpdb->comments} WHERE comment_approved = 'spam'");
    if ($spam > 0) $results[] = "✅ $spam spam yorum silindi.";
    
    // Orphaned metadata
    $wpdb->query("DELETE pm FROM {$wpdb->postmeta} pm LEFT JOIN {$wpdb->posts} wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL");
    $wpdb->query("DELETE FROM {$wpdb->commentmeta} WHERE comment_id NOT IN (SELECT comment_id FROM {$wpdb->comments})");
    
    $results[] = "✅ Orphaned metadata temizlendi.";
}

// ==================== TARAYICI CACHE'İNİ ZORLA TEMİZLE ====================

if (!headers_sent()) {
    header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('Clear-Site-Data: "cache", "cookies", "storage"');
}

// ==================== SONUÇLARI GÖSTER ====================

$end_time = microtime(true);
$execution_time = round($end_time - $start_time, 2);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Gelişmiş Cache Temizleme Raporu</title>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); overflow: hidden; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { font-size: 2em; margin-bottom: 10px; }
        .content { padding: 30px; }
        .result { padding: 12px 15px; margin: 8px 0; border-radius: 8px; border-left: 4px solid #ddd; background: #f9f9f9; transition: all 0.3s; }
        .result:hover { transform: translateX(5px); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .stats { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 25px; border-radius: 10px; margin: 20px 0; }
        .stats h3 { margin-bottom: 15px; font-size: 1.5em; }
        .stat-item { display: inline-block; margin: 10px 20px 10px 0; font-size: 1.1em; }
        .info { background: linear-gradient(135deg, #3494E6 0%, #EC6EAD 100%); color: white; padding: 25px; border-radius: 10px; margin: 20px 0; }
        .warning { background: #ff9800; color: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .btn { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 8px; margin: 10px 5px; transition: all 0.3s; }
        .btn:hover { background: #764ba2; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .footer { text-align: center; padding: 20px; background: #f5f5f5; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Gelişmiş Cache Temizleme Raporu</h1>
            <p>Tam Sistem Temizliği Tamamlandı</p>
        </div>
        
        <div class="content">
            <div class="warning">
                ⚠️ <strong>ÖNEMLİ:</strong> Cache tamamen temizlendi. Sitenizi test edin ve sorun yoksa .htaccess'teki geçici "NO CACHE" kurallarını kaldırın!
            </div>
            
            <?php foreach ($results as $result): ?>
                <div class="result"><?php echo $result; ?></div>
            <?php endforeach; ?>
            
            <div class="stats">
                <h3>📊 İstatistikler</h3>
                <div class="stat-item">✅ Başarılı: <strong><?php echo $success_count; ?></strong></div>
                <div class="stat-item">❌ Başarısız: <strong><?php echo $fail_count; ?></strong></div>
                <div class="stat-item">⏱️ Süre: <strong><?php echo $execution_time; ?>s</strong></div>
                <div class="stat-item">📅 Tarih: <strong><?php echo date('d.m.Y H:i:s'); ?></strong></div>
            </div>
            
            <div class="info">
                <h3>💻 Sistem Bilgileri</h3>
                <p><strong>PHP:</strong> <?php echo phpversion(); ?></p>
                <p><strong>Server:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
                <?php if (function_exists('opcache_get_status')): 
                    $opcache = opcache_get_status();
                    if ($opcache):
                ?>
                <p><strong>OPcache Memory:</strong> <?php echo round($opcache['memory_usage']['used_memory'] / 1024 / 1024, 2); ?> MB</p>
                <?php endif; endif; ?>
                <?php if (defined('ABSPATH')): ?>
                <p><strong>WordPress:</strong> <?php echo get_bloginfo('version'); ?></p>
                <p><strong>Tema:</strong> <?php echo wp_get_theme()->get('Name'); ?></p>
                <p><strong>Aktif Eklentiler:</strong> <?php echo count(get_option('active_plugins')); ?></p>
                <?php endif; ?>
            </div>
            
            <div style="text-align: center; margin: 20px 0;">
                <a href="<?php echo strtok($_SERVER['REQUEST_URI'], '?'); ?>" class="btn">🔄 Tekrar Temizle</a>
                <a href="<?php echo home_url(); ?>" class="btn">🏠 Siteye Git</a>
                <a href="<?php echo admin_url(); ?>" class="btn">⚙️ Admin Panel</a>
            </div>
        </div>
        
        <div class="footer">
            <p>Geliştirilmiş WordPress Cache Temizleyici v2.0</p>
            <p>Tüm cache sistemleri temizlendi</p>
        </div>
    </div>
</body>
</html>