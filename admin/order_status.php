<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_admin();
if ($_SERVER['REQUEST_METHOD']==='POST' && verify_csrf()) { $status=$_POST['status']??''; if (in_array($status,['menunggu_pembayaran','diproses','dikirim','selesai','dibatalkan'],true)) { $stmt=$koneksi->prepare('UPDATE pesanan SET status=? WHERE id_pesanan=?'); $stmt->execute([$status,(int)$_POST['id_pesanan']]); set_flash('success','Status pesanan diperbarui.'); } } redirect('admin/orders.php');