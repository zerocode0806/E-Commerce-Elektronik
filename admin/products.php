<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_admin();
$products = $koneksi->query('SELECT * FROM barang ORDER BY created_at DESC')->fetchAll();
?>
<?php $pageTitle = 'Kelola Barang'; $activeMenu = 'products'; ?>
<?php include __DIR__ . '/../includes/layouts/admin_header.php'; ?>

<div class="admin-card">
    <div class="admin-card-head">
        <h3>Daftar Barang (<?= count($products) ?>)</h3>
        <a href="<?= base_url('admin/product_form.php?page=admin-product-add') ?>" class="table-actions btn-primary-mini" style="text-decoration:none;padding:10px 18px;border-radius:8px;">
            <i class="fa-solid fa-plus"></i> Tambah Barang
        </a>
    </div>
    <table class="data-table">
        <thead>
            <tr><th>Gambar</th><th>Nama Barang</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><img class="thumb" src="<?= product_image($p['gambar']) ?>" alt=""></td>
                <td><?= e($p['nama_barang']) ?></td>
                <td><?= e($p['kategori_barang']) ?></td>
                <td><?= format_rupiah($p['harga_barang']) ?></td>
                <td><?= (int)$p['stok'] ?></td>
                <td>
                    <div class="table-actions">
                        <a href="<?= base_url('admin/product_form.php?page=admin-product-edit&id=' . $p['id_barang']) ?>">Edit</a>
                        <form method="post" action="<?= base_url('admin/product_delete.php') ?>" data-confirm="Hapus produk '<?= e($p['nama_barang']) ?>'?">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= $p['id_barang'] ?>">
                            <button type="submit" class="btn-danger">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($products)): ?>
                <tr><td colspan="6" class="page-empty">Belum ada barang. Tambahkan barang pertama Anda.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../includes/layouts/admin_footer.php'; ?>
