<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/helpers.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $koneksi->prepare('SELECT * FROM barang WHERE id_barang = ? LIMIT 1');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { http_response_code(404); exit('<h2 style="font-family:sans-serif;text-align:center;margin-top:100px;">Produk tidak ditemukan</h2>'); }
$stmt = $koneksi->prepare('SELECT * FROM barang WHERE id_barang != ? ORDER BY RAND() LIMIT 4');
$stmt->execute([$id]);
$recommended = $stmt->fetchAll();

?>
<?php $pageTitle = $product['nama_barang']; ?>
<?php include __DIR__ . '/../../includes/layouts/header.php'; ?>

<main class="container" style="padding-top:40px;">
    <section class="product-detail">
        <div class="product-gallery">
            <div class="thumbnails">
                <img src="<?= product_image($product['gambar']) ?>" class="active" alt="Thumb 1">
            </div>
            <div class="main-image-container">
                <img src="<?= product_image($product['gambar']) ?>" class="main-image" alt="<?= e($product['nama_barang']) ?>">
            </div>
        </div>

        <div class="product-info">
            <h1><?= e($product['nama_barang']) ?></h1>
            <p style="color:#777;margin:8px 0 20px;">Kategori: <?= e($product['kategori_barang']) ?> &middot; Stok: <?= (int)$product['stok'] ?></p>

            <div class="product-price">
                <h2><?= format_rupiah($product['harga_barang']) ?></h2>
            </div>

            <?php if ($product['stok'] > 0): ?>
                <form method="post" action="<?= base_url('customer/cart/customer/cart/index.php-add') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_barang" value="<?= $product['id_barang'] ?>">
                    <div class="product-summary">
                        <div class="variant-text">
                            <small>Jumlah</small>
                        </div>
                        <div class="quantity-selector">
                            <button type="button" class="btn-mini" onclick="this.nextElementSibling.stepDown()"><i class="fa-solid fa-minus"></i></button>
                            <input type="number" name="qty" value="1" min="1" max="<?= (int)$product['stok'] ?>"
                                   style="width:60px;text-align:center;border:none;font-weight:700;">
                            <button type="button" class="btn-mini" onclick="this.previousElementSibling.stepUp()"><i class="fa-solid fa-plus"></i></button>
                        </div>
                    </div>
                    <button type="submit" class="btn-add-cart">Add To Cart</button>
                </form>
            <?php else: ?>
                <p style="color:#b3261e;font-weight:700;">Stok habis</p>
            <?php endif; ?>
        </div>
    </section>

    <hr class="section-divider">

    <section class="product-tabs">
        <div class="tab-headers">
            <button class="tab-btn active" data-tab="deskripsi">Description</button>
            <button class="tab-btn" data-tab="spesifikasi">Specification</button>
        </div>
        <div class="tab-content">
            <div data-tab-panel="deskripsi" style="display:block;">
                <p><?= nl2br(e($product['deskripsi'] ?: 'Belum ada deskripsi untuk produk ini.')) ?></p>
            </div>
            <div data-tab-panel="spesifikasi" style="display:none;">
                <p><?= nl2br(e($product['spesifikasi'] ?: 'Belum ada spesifikasi untuk produk ini.')) ?></p>
            </div>
        </div>
    </section>

    <?php if (!empty($recommended)): ?>
    <section class="recommended">
        <h2>Direkomendasikan Untuk Anda</h2>
        <div class="recommended-grid">
            <?php foreach ($recommended as $r): ?>
                <a href="<?= base_url('customer/product/detail.php&id=' . $r['id_barang']) ?>" style="text-decoration:none;color:inherit;">
                    <div class="trend-card">
                        <img src="<?= product_image($r['gambar']) ?>" alt="<?= e($r['nama_barang']) ?>">
                        <div class="trend-info">
                            <h3><?= e($r['nama_barang']) ?></h3>
                            <p><?= format_rupiah($r['harga_barang']) ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../../includes/layouts/footer.php'; ?>
