<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_admin();

$totalProduk=(int)$koneksi->query('SELECT COUNT(*) FROM barang')->fetchColumn(); $totalPesanan=(int)$koneksi->query('SELECT COUNT(*) FROM pesanan')->fetchColumn(); $pesananMenunggu=(int)$koneksi->query("SELECT COUNT(*) FROM pesanan WHERE status = 'menunggu_pembayaran'")->fetchColumn(); $pembayaranMenunggu=(int)$koneksi->query("SELECT COUNT(*) FROM pembayaran WHERE status_verifikasi = 'menunggu'")->fetchColumn(); $totalUser=(int)$koneksi->query('SELECT COUNT(*) FROM users')->fetchColumn(); $allOrders=$koneksi->query('SELECT p.*, u.nama AS nama_user FROM pesanan p JOIN users u ON u.id_user=p.id_user ORDER BY p.tanggal DESC')->fetchAll(); $recentOrders=array_slice($allOrders,0,5);

?>
<?php $pageTitle = 'Dashboard'; $activeMenu = 'dashboard'; ?>
<?php include __DIR__ . '/../includes/layouts/admin_header.php'; ?>

<div class="stat-grid">
    <div class="stat-card">
        <span>Total Produk</span>
        <strong><?= (int)$totalProduk ?></strong>
    </div>
    <div class="stat-card">
        <span>Total Pesanan</span>
        <strong><?= (int)$totalPesanan ?></strong>
    </div>
    <div class="stat-card">
        <span>Pesanan Menunggu Pembayaran</span>
        <strong><?= (int)$pesananMenunggu ?></strong>
    </div>
    <div class="stat-card">
        <span>Pembayaran Perlu Verifikasi</span>
        <strong><?= (int)$pembayaranMenunggu ?></strong>
    </div>
    <div class="stat-card">
        <span>Total Pengguna</span>
        <strong><?= (int)$totalUser ?></strong>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-head">
        <h3>Pesanan Terbaru</h3>
        <a href="<?= base_url('admin/orders.php') ?>" class="table-actions" style="text-decoration:none;color:#0c1410;font-weight:600;">Lihat Semua &rarr;</a>
    </div>
    <table class="data-table">
        <thead>
            <tr><th>ID</th><th>Pelanggan</th><th>Grand Total</th><th>Status</th><th>Tanggal</th></tr>
        </thead>
        <tbody>
            <?php foreach ($recentOrders as $o): ?>
            <tr>
                <td>#<?= $o['id_pesanan'] ?></td>
                <td><?= e($o['nama_user']) ?></td>
                <td><?= format_rupiah($o['grand_total']) ?></td>
                <td><span class="badge badge-<?= e($o['status']) ?>"><?= e(str_replace('_',' ',$o['status'])) ?></span></td>
                <td><?= date('d M Y', strtotime($o['tanggal'])) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($recentOrders)): ?>
                <tr><td colspan="5" class="page-empty">Belum ada pesanan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/layouts/admin_footer.php'; ?>
