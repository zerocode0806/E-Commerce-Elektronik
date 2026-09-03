<?php
/** Layout: admin header + sidebar. Variabel opsional: $pageTitle, $activeMenu */
$activeMenu = $activeMenu ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? e($pageTitle) . ' - Admin ' . APP_NAME : 'Admin ' . APP_NAME ?></title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="logo"><strong><?= APP_NAME ?></strong></div>
            <a href="<?= base_url('admin/dashboard.php') ?>" class="<?= $activeMenu === 'dashboard' ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge"></i> Dashboard
            </a>
            <a href="<?= base_url('admin/products.php') ?>" class="<?= $activeMenu === 'products' ? 'active' : '' ?>">
                <i class="fa-solid fa-box-open"></i> Kelola Barang
            </a>
            <a href="<?= base_url('admin/orders.php') ?>" class="<?= $activeMenu === 'orders' ? 'active' : '' ?>">
                <i class="fa-solid fa-receipt"></i> Pesanan
            </a>
            <a href="<?= base_url('admin/payments.php') ?>" class="<?= $activeMenu === 'payments' ? 'active' : '' ?>">
                <i class="fa-solid fa-money-check-dollar"></i> Verifikasi Pembayaran
            </a>
            <a href="<?= base_url('auth/admin_logout.php') ?>">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </aside>
        <main class="admin-main">
            <div class="admin-topbar">
                <h1><?= isset($pageTitle) ? e($pageTitle) : 'Dashboard' ?></h1>
                <div class="nav-account"><i class="fa-solid fa-user-tie"></i> <?= e(current_admin()['nama'] ?? '') ?></div>
            </div>
            <?php $flash = get_flash(); ?>
            <?php if ($flash): ?>
                <div class="alert alert-<?= e($flash['type']) ?>" style="margin:0 0 20px;"><?= e($flash['message']) ?></div>
            <?php endif; ?>
