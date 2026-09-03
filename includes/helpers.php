<?php
/**
 * Kumpulan fungsi bantu (helper) global.
 */

function base_url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return base_url('assets/' . ltrim($path, '/'));
}

function redirect(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}

function format_rupiah($angka): string
{
    return 'Rp' . number_format((float)$angka, 0, ',', '.');
}

function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/** Bangun URL gambar produk dari nama file di kolom `gambar`.
 *  Mendukung juga data lama yang menyimpan URL absolut (mis. dari layanan eksternal). */
function product_image(?string $gambar): string
{
    if (empty($gambar)) {
        return asset('img/products/no-image.png');
    }
    if (preg_match('#^https?://#i', $gambar)) {
        return $gambar; // URL eksternal (data lama), pakai apa adanya
    }
    return asset('img/products/' . ltrim($gambar, '/'));
}

/** Potong teks dengan aman, tidak bergantung pada ekstensi mbstring */
function truncate(string $text, int $length = 100, string $suffix = '...'): string
{
    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        return mb_strlen($text) > $length ? mb_substr($text, 0, $length) . $suffix : $text;
    }
    return strlen($text) > $length ? substr($text, 0, $length) . $suffix : $text;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user']);
}

function is_admin(): bool
{
    return !empty($_SESSION['admin']);
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function current_admin(): ?array
{
    return $_SESSION['admin'] ?? null;
}

/** Wajib login sebagai user (costumer), kalau tidak redirect ke halaman login */
function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('error', 'Silakan login terlebih dahulu.');
        redirect('auth/login.php');
    }
}

/** Wajib login sebagai admin */
function require_admin(): void
{
    if (!is_admin()) {
        set_flash('error', 'Silakan login sebagai admin terlebih dahulu.');
        redirect('auth/admin_login.php');
    }
}

function old(string $key, $default = '')
{
    return e($_SESSION['old'][$key] ?? $default);
}

function csrf_field(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

function verify_csrf(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
