<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/helpers.php';

require_login();
$idUser = (int)current_user()['id_user'];
$stmt = $koneksi->prepare('SELECT * FROM pesanan WHERE id_user = ? ORDER BY tanggal DESC'); $stmt->execute([$idUser]); $orders = $stmt->fetchAll(); $details = [];
$stmtDetail = $koneksi->prepare('SELECT * FROM detail_pesanan WHERE id_pesanan = ?');
foreach ($orders as $o) { $stmtDetail->execute([$o['id_pesanan']]); $details[$o['id_pesanan']] = $stmtDetail->fetchAll(); }

?>
<?php $pageTitle = 'Riwayat Pesanan'; ?>
<?php include __DIR__ . '/../../includes/layouts/header.php'; ?>

<div class="container cart-page">
    <h1>Riwayat Pesanan</h1>

    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <i class="fa-solid fa-receipt"></i>
            <p>Anda belum memiliki pesanan.</p>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <div class="order-card-head">
                    <div>
                        <strong>#<?= $order['id_pesanan'] ?></strong>
                        <span style="color:#777;font-size:0.85rem;margin-left:10px;"><?= date('d M Y H:i', strtotime($order['tanggal'])) ?></span>
                    </div>
                    <span class="badge badge-<?= e($order['status']) ?>"><?= e(str_replace('_', ' ', $order['status'])) ?></span>
                </div>
                <?php foreach ($details[$order['id_pesanan']] ?? [] as $d): ?>
                    <div class="order-item-row">
                        <span><?= e($d['nama_barang']) ?> &times; <?= (int)$d['qty'] ?></span>
                        <span><?= format_rupiah($d['subtotal']) ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="summary-row total" style="margin-top:10px;">
                    <span>Grand Total</span>
                    <span><?= format_rupiah($order['grand_total']) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
