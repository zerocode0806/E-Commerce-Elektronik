<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_admin();
if ($_SERVER['REQUEST_METHOD']==='POST' && verify_csrf()) { $stmt=$koneksi->prepare('DELETE FROM barang WHERE id_barang=?'); $stmt->execute([(int)$_POST['id']]); set_flash('success','Barang berhasil dihapus.'); } redirect('admin/products.php');