<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/helpers.php';

require_login();
$idUser = (int)current_user()['id_user'];
$stmt = $koneksi->prepare('SELECT k.*, b.nama_barang, b.harga_barang, b.gambar, b.stok FROM keranjang k JOIN barang b ON b.id_barang = k.id_barang WHERE k.id_user = ? ORDER BY k.id_keranjang DESC'); $stmt->execute([$idUser]); $cartItems = $stmt->fetchAll();
if (empty($cartItems)) { set_flash('error', 'Keranjang Anda kosong, tambahkan produk terlebih dahulu.'); redirect('index.php'); }
$grandTotal = array_sum(array_map(fn($item) => (float)$item['harga_barang'] * (int)$item['qty'], $cartItems));
$stmt = $koneksi->prepare('SELECT * FROM users WHERE id_user = ?'); $stmt->execute([$idUser]); $user = $stmt->fetch();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) { set_flash('error', 'Sesi form tidak valid, silakan coba lagi.'); redirect('customer/order/checkout.php'); }
    $alamat = trim($_POST['alamat'] ?? ''); $metode = $_POST['metode_pembayaran'] ?? 'transfer_bank'; $jumlahBayar = (float)($_POST['jumlah_bayar'] ?? 0);
    if ($alamat === '') { set_flash('error', 'Alamat pengiriman wajib diisi.'); redirect('customer/order/checkout.php'); }
    if ($jumlahBayar < $grandTotal) { set_flash('error', 'Jumlah bayar tidak boleh kurang dari Grand Total.'); redirect('customer/order/checkout.php'); }
    $koneksi->beginTransaction();
    try {
        $stmt = $koneksi->prepare('INSERT INTO pesanan (id_user, grand_total, status, alamat) VALUES (?, ?, ?, ?)'); $stmt->execute([$idUser, $grandTotal, 'menunggu_pembayaran', $alamat]); $idPesanan = (int)$koneksi->lastInsertId();
        $detail = $koneksi->prepare('INSERT INTO detail_pesanan (id_pesanan, id_barang, nama_barang, qty, subtotal) VALUES (?, ?, ?, ?, ?)'); $stock = $koneksi->prepare('UPDATE barang SET stok = stok - ? WHERE id_barang = ? AND stok >= ?');
        foreach ($cartItems as $item) { $subtotal = (float)$item['harga_barang'] * (int)$item['qty']; $detail->execute([$idPesanan, $item['id_barang'], $item['nama_barang'], $item['qty'], $subtotal]); $stock->execute([(int)$item['qty'], (int)$item['id_barang'], (int)$item['qty']]); }
        $stmt = $koneksi->prepare('DELETE FROM keranjang WHERE id_user = ?'); $stmt->execute([$idUser]); $kembali = max(0, $jumlahBayar - $grandTotal); $stmt = $koneksi->prepare('INSERT INTO pembayaran (id_pesanan, id_user, grand_total, jumlah_bayar, jumlah_kembali, metode_pembayaran, status_verifikasi) VALUES (?, ?, ?, ?, ?, ?, ?)'); $stmt->execute([$idPesanan, $idUser, $grandTotal, $jumlahBayar, $kembali, $metode, 'menunggu']); $koneksi->commit();
        $stmt = $koneksi->prepare('SELECT * FROM pesanan WHERE id_pesanan = ?'); $stmt->execute([$idPesanan]); $order = $stmt->fetch(); redirect('customer/order/success.php?id=' . $idPesanan);
    } catch (Throwable $e) { $koneksi->rollBack(); throw $e; }
}

?>
<?php $pageTitle = 'Checkout'; ?>
<?php include __DIR__ . '/../../includes/layouts/header.php'; ?>

<div class="container cart-page">
    <h1>Checkout</h1>

    <form method="post" action="<?= base_url('customer/order/checkout.php') ?>">
        <?= csrf_field() ?>
        <div class="checkout-layout">
            <div class="checkout-form">
                <div class="form-group">
                    <label>Alamat Pengiriman</label>
                    <textarea name="alamat" required><?= old('alamat', $user['alamat']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" value="<?= e($user['no_telepon']) ?>" disabled>
                </div>

                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <div class="payment-methods">
                        <label>
                            <input type="radio" name="metode_pembayaran" value="transfer_bank" checked>
                            <i class="fa-solid fa-building-columns"></i>
                            Transfer Bank
                        </label>
                        <label>
                            <input type="radio" name="metode_pembayaran" value="e_wallet">
                            <i class="fa-solid fa-wallet"></i>
                            E-Wallet
                        </label>
                        <label>
                            <input type="radio" name="metode_pembayaran" value="cod">
                            <i class="fa-solid fa-money-bill-wave"></i>
                            COD
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Jumlah Bayar</label>
                    <input type="number" name="jumlah_bayar" min="<?= (int)$grandTotal ?>" value="<?= (int)$grandTotal ?>" required>
                    <small style="color:#777;">Minimal sebesar Grand Total pesanan.</small>
                </div>
            </div>

            <div class="order-summary-box">
                <h3 style="margin-bottom:15px;">Ringkasan Pesanan</h3>
                <?php foreach ($cartItems as $item): ?>
                    <div class="order-line">
                        <span><?= e($item['nama_barang']) ?> &times; <?= (int)$item['qty'] ?></span>
                        <span><?= format_rupiah($item['subtotal']) ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="summary-row total">
                    <span>Grand Total</span>
                    <span><?= format_rupiah($grandTotal) ?></span>
                </div>
                <button type="submit" class="btn-primary btn-block" style="padding:14px;border-radius:8px;margin-top:20px;">
                    Buat Pesanan
                </button>
            </div>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
