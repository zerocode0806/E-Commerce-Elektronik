<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_admin();
$orders = $koneksi->query('SELECT p.*, u.nama AS nama_user FROM pesanan p JOIN users u ON u.id_user=p.id_user ORDER BY p.tanggal DESC')->fetchAll();
?>
<?php $pageTitle = 'Kelola Pesanan'; $activeMenu = 'orders'; ?>
<?php include __DIR__ . '/../includes/layouts/admin_header.php'; ?>

<div class="admin-card">
    <div class="admin-card-head">
        <h3>Daftar Pesanan (<?= count($orders) ?>)</h3>
    </div>
    <table class="data-table">
        <thead>
            <tr><th>ID</th><th>Pelanggan</th><th>Grand Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
            <tr>
                <td>#<?= $o['id_pesanan'] ?></td>
                <td><?= e($o['nama_user']) ?></td>
                <td><?= format_rupiah($o['grand_total']) ?></td>
                <td><span class="badge badge-<?= e($o['status']) ?>"><?= e(str_replace('_',' ',$o['status'])) ?></span></td>
                <td><?= date('d M Y', strtotime($o['tanggal'])) ?></td>
                <td>
                    <form method="post" action="<?= base_url('admin/order_status.php') ?>" style="display:flex;gap:6px;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_pesanan" value="<?= $o['id_pesanan'] ?>">
                        <select name="status" style="padding:6px;border-radius:6px;border:1px solid #ddd;">
                            <?php foreach (['menunggu_pembayaran','diproses','dikirim','selesai','dibatalkan'] as $st): ?>
                                <option value="<?= $st ?>" <?= $o['status'] === $st ? 'selected' : '' ?>><?= str_replace('_',' ',$st) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn-primary-mini" style="border-radius:6px;padding:6px 12px;border:1px solid #0c1410;">Update</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($orders)): ?>
                <tr><td colspan="6" class="page-empty">Belum ada pesanan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/layouts/admin_footer.php'; ?>
