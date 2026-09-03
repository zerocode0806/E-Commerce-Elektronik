<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_admin();
$payments = $koneksi->query('SELECT pb.*, u.nama AS nama_user FROM pembayaran pb JOIN users u ON u.id_user=pb.id_user ORDER BY pb.tanggal DESC')->fetchAll();
?>
<?php $pageTitle = 'Verifikasi Pembayaran'; $activeMenu = 'payments'; ?>
<?php include __DIR__ . '/../includes/layouts/admin_header.php'; ?>

<div class="admin-card">
    <div class="admin-card-head">
        <h3>Daftar Pembayaran (<?= count($payments) ?>)</h3>
    </div>
    <table class="data-table">
        <thead>
            <tr><th>ID</th><th>Pesanan</th><th>Pelanggan</th><th>Metode</th><th>Jumlah Bayar</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $pay): ?>
            <tr>
                <td>#<?= $pay['id_pembayaran'] ?></td>
                <td>#<?= $pay['id_pesanan'] ?></td>
                <td><?= e($pay['nama_user']) ?></td>
                <td><?= e(str_replace('_',' ',$pay['metode_pembayaran'])) ?></td>
                <td><?= format_rupiah($pay['jumlah_bayar']) ?></td>
                <td><span class="badge badge-<?= e($pay['status_verifikasi']) ?>"><?= e($pay['status_verifikasi']) ?></span></td>
                <td>
                    <?php if ($pay['status_verifikasi'] === 'menunggu'): ?>
                    <div class="table-actions">
                        <form method="post" action="<?= base_url('admin/payment_verify.php') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_pembayaran" value="<?= $pay['id_pembayaran'] ?>">
                            <input type="hidden" name="keputusan" value="terima">
                            <button type="submit" class="btn-primary-mini">Terima</button>
                        </form>
                        <form method="post" action="<?= base_url('admin/payment_verify.php') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_pembayaran" value="<?= $pay['id_pembayaran'] ?>">
                            <input type="hidden" name="keputusan" value="tolak">
                            <button type="submit" class="btn-danger">Tolak</button>
                        </form>
                    </div>
                    <?php else: ?>
                        &mdash;
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($payments)): ?>
                <tr><td colspan="7" class="page-empty">Belum ada data pembayaran.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/layouts/admin_footer.php'; ?>
