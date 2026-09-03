<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_login();
$idPesanan = (int)($_GET['id'] ?? 0);
$stmt = $koneksi->prepare('SELECT * FROM pesanan WHERE id_pesanan = ? AND id_user = ? LIMIT 1');
$stmt->execute([$idPesanan, (int)current_user()['id_user']]);
$order = $stmt->fetch();
if (!$order) { redirect('customer/order/history.php'); }
$pageTitle = 'Pesanan Berhasil';
?>
<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?= e($pageTitle) ?> - <?= APP_NAME ?></title><link rel="stylesheet" href="<?= asset('css/style.css') ?>"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head>
<body>
<?php include __DIR__ . '/../../includes/layouts/header.php'; ?>
<div class="container"><div class="empty-state"><i class="fa-solid fa-circle-check" style="color:#1a7f43;"></i><h2 style="margin-bottom:10px;">Pesanan Berhasil Dibuat!</h2><p>Nomor Pesanan: <strong>#<?= $order['id_pesanan'] ?></strong></p><p>Grand Total: <strong><?= format_rupiah($order['grand_total']) ?></strong></p><p>Status: <span class="badge badge-<?= e($order['status']) ?>"><?= e(str_replace('_', ' ', $order['status'])) ?></span></p><br><a href="<?= base_url('customer/order/history.php') ?>" class="btn-primary" style="padding:14px 30px;border-radius:8px;">Lihat Riwayat Pesanan</a></div></div>
<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
</body></html>
