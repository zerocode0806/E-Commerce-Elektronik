<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_admin();
if ($_SERVER['REQUEST_METHOD']==='POST' && verify_csrf()) { $id=(int)$_POST['id_pembayaran']; $diterima=($_POST['keputusan']??'')==='terima'; $stmt=$koneksi->prepare('UPDATE pembayaran SET status_verifikasi=? WHERE id_pembayaran=?'); $stmt->execute([$diterima?'terverifikasi':'ditolak',$id]); if($diterima){$stmt=$koneksi->prepare('SELECT id_pesanan FROM pembayaran WHERE id_pembayaran=?');$stmt->execute([$id]);$pesanan=$stmt->fetchColumn();if($pesanan){$stmt=$koneksi->prepare("UPDATE pesanan SET status='diproses' WHERE id_pesanan=?");$stmt->execute([$pesanan]);}} set_flash('success','Status pembayaran diperbarui.'); } redirect('admin/payments.php');