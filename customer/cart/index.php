<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/helpers.php';

require_login();
$idUser = (int)current_user()['id_user'];
if (($_GET['page'] ?? '') === 'cart-add') {
    $action = $_POST['action'] ?? 'add_to_cart';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) {
        $idBarang = (int)($_POST['id_barang'] ?? 0); $qty = max(1, (int)($_POST['qty'] ?? 1));
        $stmt = $koneksi->prepare('SELECT id_keranjang, qty FROM keranjang WHERE id_user = ? AND id_barang = ? LIMIT 1'); $stmt->execute([$idUser, $idBarang]); $existing = $stmt->fetch();
        $stmt = $koneksi->prepare('SELECT harga_barang FROM barang WHERE id_barang = ?'); $stmt->execute([$idBarang]); $harga = (float)($stmt->fetchColumn() ?: 0);
        if ($existing) { $newQty = $existing['qty'] + $qty; $stmt = $koneksi->prepare('UPDATE keranjang SET qty = ?, subtotal = ? WHERE id_keranjang = ?'); $stmt->execute([$newQty, $newQty * $harga, $existing['id_keranjang']]); }
        else { $stmt = $koneksi->prepare('INSERT INTO keranjang (id_user, id_barang, qty, subtotal) VALUES (?, ?, ?, ?)'); $stmt->execute([$idUser, $idBarang, $qty, $qty * $harga]); }
        set_flash('success', 'Produk ditambahkan ke keranjang.');
    }
    if ($action === 'buy_now') {
        redirect('customer/cart/index.php');
    }
    redirect('customer/product/detail.php?id=' . (int)($_POST['id_barang'] ?? 0));
}
if (($_GET['page'] ?? '') === 'cart-update') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) { $stmt = $koneksi->prepare('SELECT b.harga_barang FROM keranjang k JOIN barang b ON b.id_barang = k.id_barang WHERE k.id_keranjang = ? AND k.id_user = ?'); $stmt->execute([(int)$_POST['id_keranjang'], $idUser]); $harga = $stmt->fetchColumn(); if ($harga !== false) { $qty = max(1, (int)$_POST['qty']); $stmt = $koneksi->prepare('UPDATE keranjang SET qty = ?, subtotal = ? WHERE id_keranjang = ?'); $stmt->execute([$qty, $qty * $harga, (int)$_POST['id_keranjang']]); } }
    redirect('customer/cart/index.php');
}
if (($_GET['page'] ?? '') === 'cart-delete') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf()) { $stmt = $koneksi->prepare('DELETE FROM keranjang WHERE id_keranjang = ? AND id_user = ?'); $stmt->execute([(int)$_POST['id_keranjang'], $idUser]); set_flash('success', 'Produk dihapus dari keranjang.'); }
    redirect('customer/cart/index.php');
}
$stmt = $koneksi->prepare('SELECT k.*, b.nama_barang, b.harga_barang, b.gambar, b.stok FROM keranjang k JOIN barang b ON b.id_barang = k.id_barang WHERE k.id_user = ? ORDER BY k.id_keranjang DESC'); $stmt->execute([$idUser]); $cartItems = $stmt->fetchAll();
$grandTotal = array_sum(array_column($cartItems, 'subtotal'));



?>
<?php $pageTitle = 'Keranjang Saya'; ?>
<?php include __DIR__ . '/../../includes/layouts/header.php'; ?>

<div class="container cart-page">
    <h1>Keranjang Saya</h1>

    <?php if (empty($cartItems)): ?>
        <div class="empty-state">
            <i class="fa-solid fa-cart-shopping"></i>
            <p>Keranjang Anda masih kosong.</p>
            <br>
            <a href="<?= base_url('index.php') ?>" class="btn-primary" style="padding:14px 30px;border-radius:8px;">Mulai Belanja</a>
        </div>
    <?php else: ?>
    <div class="cart-layout">
        <div class="cart-items">
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <img src="<?= product_image($item['gambar']) ?>" alt="<?= e($item['nama_barang']) ?>">
                    <div class="cart-item-info">
                        <h4><?= e($item['nama_barang']) ?></h4>
                        <div class="price"><?= format_rupiah($item['harga_barang']) ?> / item</div>
                    </div>
                    <form class="qty-form" method="post" action="<?= base_url('customer/cart/index.php?page=cart-update') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_keranjang" value="<?= $item['id_keranjang'] ?>">
                        <button type="button" class="btn-mini qty-minus"><i class="fa-solid fa-minus"></i></button>
                        <input type="number" name="qty" value="<?= (int)$item['qty'] ?>" min="1" max="<?= (int)$item['stok'] ?>">
                        <button type="button" class="btn-mini qty-plus"><i class="fa-solid fa-plus"></i></button>
                    </form>
                    <strong style="width:130px;text-align:right;display:inline-block;"><?= format_rupiah($item['subtotal']) ?></strong>
                    <form method="post" action="<?= base_url('customer/cart/index.php?page=cart-delete') ?>" data-confirm="Hapus produk ini dari keranjang?">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_keranjang" value="<?= $item['id_keranjang'] ?>">
                        <button type="submit" class="btn-mini btn-remove"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary">
            <h3>Ringkasan Belanja</h3>
            <div class="summary-row">
                <span>Total Item</span>
                <span><?= count($cartItems) ?> produk</span>
            </div>
            <div class="summary-row total">
                <span>Grand Total</span>
                <span><?= format_rupiah($grandTotal) ?></span>
            </div>
            <a href="<?= base_url('customer/order/checkout.php') ?>" class="btn-primary btn-block" style="padding:14px;border-radius:8px;margin-top:15px;">
                Checkout
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>    