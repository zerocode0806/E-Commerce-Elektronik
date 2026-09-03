<?php
/**
 * Layout: header (dipakai di semua halaman customer via include).
 * Variabel opsional yang bisa di-set sebelum include: $pageTitle
 */
$cartCount = 0;
if (is_logged_in()) {
    $stmtCartCount = $koneksi->prepare('SELECT COUNT(*) FROM keranjang WHERE id_user = ?');
    $stmtCartCount->execute([(int) current_user()['id_user']]);
    $cartCount = (int) $stmtCartCount->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - ' . APP_NAME : APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <a href="<?= base_url('index.php') ?>" style="text-decoration:none;color:inherit;">
            <div class="logo"><strong><?= APP_NAME ?></strong></div>
        </a>
        <nav>
            <a href="<?= base_url('index.php?kategori=Smartphones') ?>">Smartphones</a>
            <a href="<?= base_url('index.php?kategori=Laptops') ?>">Laptops</a>
            <a href="<?= base_url('index.php?kategori=Audio') ?>">Audio</a>
            <a href="<?= base_url('index.php?kategori=Wearables') ?>">Wearables</a>
            <a href="<?= base_url('index.php?kategori=Accessories') ?>">Accessories</a>
        </nav>
        <div class="header-icons">
            <a href="<?= base_url('customer/cart/index.php') ?>" title="Keranjang">
                <i class="fa-solid fa-cart-shopping"></i>
                <?php if ($cartCount > 0): ?>
                    <span class="cart-badge"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>
            <?php if (is_logged_in()): ?>
                <a href="<?= base_url('customer/order/history.php') ?>" title="Riwayat Pesanan"><i class="fa-solid fa-box"></i></a>
                <span class="nav-account"><?= e(current_user()['nama']) ?></span>
                <a href="<?= base_url('auth/logout.php') ?>" title="Keluar"><i class="fa-solid fa-right-from-bracket"></i></a>
            <?php else: ?>
                <a href="<?= base_url('auth/login.php') ?>" class="nav-account">Login</a>
            <?php endif; ?>
        </div>
    </header>

    <?php $flash = get_flash(); ?>
    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>
