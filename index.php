<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/helpers.php';

$kategoriAktif = $_GET['kategori'] ?? null;
$searchQuery = trim($_GET['q'] ?? '');
$sql = 'SELECT * FROM barang WHERE 1=1';
$params = [];
if ($searchQuery !== '') { $sql .= ' AND nama_barang LIKE ?'; $params[] = '%' . $searchQuery . '%'; }
if ($kategoriAktif) { $sql .= ' AND kategori_barang = ?'; $params[] = $kategoriAktif; }
$sql .= ' ORDER BY created_at DESC';
if ($searchQuery === '' && !$kategoriAktif) $sql .= ' LIMIT 8';
$stmt = $koneksi->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<?php $pageTitle = 'Beranda'; ?>
<?php include __DIR__ . '/includes/layouts/header.php'; ?>

<?php if (empty($kategoriAktif) && empty($searchQuery)): ?>
<section class="hero">
    <div class="hero-content">
        <h1>The Future of<br>Tech<br><span class="highlight">In Your Hands</span></h1>
        <p>Temukan smartphone, laptop, audio, hingga aksesoris gadget terbaik dengan harga bersaing dan kualitas terjamin di <?= APP_NAME ?>.</p>
        <div class="hero-buttons">
            <a href="#produk"><button class="btn-primary">Shop Now</button></a>
            <a href="#kategori"><button class="btn-secondary">Learn More</button></a>
        </div>
    </div>
</section>

<section class="categories" id="kategori">
    <h2>Shop By Category</h2>
    <div class="category-grid">
        <a href="<?= base_url('index.php?kategori=Smartphones') ?>" style="text-decoration:none;color:inherit;"><div class="cat-card"><div class="icon-circle"><i class="fa-solid fa-mobile-screen-button"></i></div><h3>Smartphones</h3><p>Flagships & Essentials</p></div></a>
        <a href="<?= base_url('index.php?kategori=Laptops') ?>" style="text-decoration:none;color:inherit;"><div class="cat-card"><div class="icon-circle"><i class="fa-solid fa-laptop"></i></div><h3>Laptops</h3><p>Pro Performance</p></div></a>
        <a href="<?= base_url('index.php?kategori=Audio') ?>" style="text-decoration:none;color:inherit;"><div class="cat-card"><div class="icon-circle"><i class="fa-solid fa-headphones"></i></div><h3>Audio</h3><p>Immersive Sound</p></div></a>
        <a href="<?= base_url('index.php?kategori=Wearables') ?>" style="text-decoration:none;color:inherit;"><div class="cat-card"><div class="icon-circle"><i class="fa-solid fa-stopwatch"></i></div><h3>Wearables</h3><p>Smart Life</p></div></a>
    </div>
</section>
<?php endif; ?>

<section class="trending" id="produk">
    <h2><?php if (!empty($searchQuery)): ?>Hasil pencarian "<?= e($searchQuery) ?>"<?php elseif (!empty($kategoriAktif)): ?>Kategori: <?= e($kategoriAktif) ?><?php else: ?>Trending Gadgets<?php endif; ?></h2>
    <form method="get" action="<?= base_url('index.php') ?>" style="max-width:400px;margin:0 0 30px;display:flex;gap:10px;">
        <input type="text" name="q" placeholder="Cari produk..." value="<?= e($searchQuery) ?>" style="flex:1;padding:12px 14px;border:1px solid #ddd;border-radius:8px;">
        <button type="submit" class="btn-primary" style="padding:0 20px;border-radius:8px;">Cari</button>
    </form>
    <?php if (empty($products)): ?>
        <p class="page-empty">Belum ada produk yang tersedia.</p>
    <?php else: ?>
    <div class="trending-grid">
        <?php foreach ($products as $p): ?>
            <a href="<?= base_url('customer/product/detail.php?id=' . $p['id_barang']) ?>" style="text-decoration:none;color:inherit;"><div class="trend-card"><img src="<?= product_image($p['gambar']) ?>" alt="<?= e($p['nama_barang']) ?>"><div class="trend-info"><h3><?= e($p['nama_barang']) ?></h3><p><?= e(truncate($p['deskripsi'] ?? '', 90)) ?></p><strong style="display:block;margin-top:8px;"><?= format_rupiah($p['harga_barang']) ?></strong></div></div></a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/includes/layouts/footer.php'; ?>
