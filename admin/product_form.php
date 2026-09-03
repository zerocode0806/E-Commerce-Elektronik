<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_admin();

$isEdit=(($_GET['page']??'')==='admin-product-edit'); $id=(int)($_GET['id']??0); $product=null; if($isEdit){$stmt=$koneksi->prepare('SELECT * FROM barang WHERE id_barang=?');$stmt->execute([$id]);$product=$stmt->fetch();if(!$product){set_flash('error','Barang tidak ditemukan.');redirect('admin/products.php');}}
if($_SERVER['REQUEST_METHOD']==='POST'){if(!verify_csrf()){set_flash('error','Sesi form tidak valid.');redirect($isEdit?'admin/product_form.php?page=admin-product-edit&id='.$id:'admin/product_form.php?page=admin-product-add');}$gambar=null;if(!empty($_FILES['gambar']['name'])){$ext=strtolower(pathinfo($_FILES['gambar']['name'],PATHINFO_EXTENSION));if(!in_array($ext,['jpg','jpeg','png','webp'],true)){set_flash('error','Format gambar tidak valid.');redirect('admin/products.php');}$gambar=uniqid('barang_').'.'.$ext;if(!is_dir(UPLOAD_DIR))mkdir(UPLOAD_DIR,0775,true);move_uploaded_file($_FILES['gambar']['tmp_name'],UPLOAD_DIR.$gambar);}if(!$isEdit&&!$gambar){set_flash('error','Gambar produk wajib diunggah.');redirect('admin/product_form.php?page=admin-product-add');}$data=[trim($_POST['nama_barang']??''),trim($_POST['kategori_barang']??''),trim($_POST['deskripsi']??''),trim($_POST['spesifikasi']??''),(float)($_POST['harga_barang']??0),(int)($_POST['stok']??0)];if($isEdit){$sql='UPDATE barang SET nama_barang=?,kategori_barang=?,deskripsi=?,spesifikasi=?,harga_barang=?,stok=?';if($gambar){$sql.=',gambar=?';$data[]=$gambar;}$data[]=$id;$stmt=$koneksi->prepare($sql.' WHERE id_barang=?');$stmt->execute($data);}else{$stmt=$koneksi->prepare('INSERT INTO barang (nama_barang,kategori_barang,deskripsi,spesifikasi,harga_barang,stok,gambar,id_admin) VALUES (?,?,?,?,?,?,?,?)');$stmt->execute([...$data,$gambar,(int)current_admin()['id_admin']]);}set_flash('success',$isEdit?'Barang berhasil diperbarui.':'Barang berhasil ditambahkan.');redirect('admin/products.php');}

?>
<?php
$isEdit = !empty($product);
$pageTitle = $isEdit ? 'Edit Barang' : 'Tambah Barang';
$activeMenu = 'products';
?>
<?php include __DIR__ . '/../includes/layouts/admin_header.php'; ?>

<div class="admin-card" style="max-width:700px;">
    <form method="post"
          action="<?= base_url('admin/product_form.php?page=' . ($isEdit ? 'admin-product-edit&id=' . $product['id_barang'] : 'admin-product-add')) ?>"
          enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="<?= old('nama_barang', $product['nama_barang'] ?? '') ?>" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori_barang" required>
                    <?php foreach (['Smartphones','Laptops','Audio','Wearables','Accessories'] as $cat): ?>
                        <option value="<?= $cat ?>" <?= (($product['kategori_barang'] ?? '') === $cat) ? 'selected' : '' ?>><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga_barang" value="<?= old('harga_barang', $product['harga_barang'] ?? '') ?>" min="0" required>
            </div>
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" value="<?= old('stok', $product['stok'] ?? 0) ?>" min="0" required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi"><?= old('deskripsi', $product['deskripsi'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Spesifikasi</label>
            <textarea name="spesifikasi"><?= old('spesifikasi', $product['spesifikasi'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label>Gambar Produk <?= $isEdit ? '(kosongkan jika tidak ingin mengubah)' : '' ?></label>
            <input type="file" id="gambar-input" name="gambar" accept="image/*" <?= $isEdit ? '' : 'required' ?>>
            <img id="gambar-preview" src="<?= product_image($product['gambar'] ?? null) ?>"
                 style="<?= $isEdit ? '' : 'display:none;' ?>margin-top:10px;width:120px;border-radius:8px;">
        </div>

        <button type="submit" class="btn-primary" style="padding:12px 30px;border-radius:8px;">
            <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Barang' ?>
        </button>
        <a href="<?= base_url('admin/products.php') ?>" class="btn-secondary" style="padding:12px 30px;border-radius:8px;margin-left:10px;">
            Batal
        </a>
    </form>
</div>

<?php include __DIR__ . '/../includes/layouts/admin_footer.php'; ?>
