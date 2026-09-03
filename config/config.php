<?php
/**
 * Konfigurasi umum aplikasi.
 * BASE_URL otomatis terdeteksi berdasarkan folder tempat aplikasi diletakkan,
 * tapi bisa diganti manual jika perlu (misal saat di-deploy ke hosting).
 */

define('APP_NAME', 'GadgetHub');
if (!defined('BASE_DIR')) {
    define('BASE_DIR', dirname(__DIR__));
}

// Deteksi otomatis base URL (root proyek menjadi document root)
// Cek juga header X-Forwarded-Proto, karena reverse proxy seperti ngrok/nginx
// meneruskan request ke PHP sebagai HTTP biasa walau browser mengakses via HTTPS.
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
    || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

$protocol = $isHttps ? 'https://' : 'http://';

// Host juga sebaiknya mengikuti forwarded host jika ada (beberapa proxy mengirim ini).
$host = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? ($_SERVER['HTTP_HOST'] ?? 'localhost');

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$scriptDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
foreach (['/admin/', '/auth/', '/customer/', '/ajax/'] as $folderMarker) {
    $markerPosition = strpos($scriptName, $folderMarker);
    if ($markerPosition !== false) {
        $scriptDir = substr($scriptName, 0, $markerPosition);
        break;
    }
}
define('BASE_URL', $protocol . $host . rtrim($scriptDir, '/'));

define('UPLOAD_DIR', __DIR__ . '/../assets/img/products/');
define('UPLOAD_URL', BASE_URL . '/assets/img/products/');
