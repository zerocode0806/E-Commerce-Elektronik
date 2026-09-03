<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';

if (is_admin()) redirect('admin/dashboard.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') { if (!verify_csrf()) { set_flash('error','Sesi form tidak valid, silakan coba lagi.'); redirect('auth/admin_login.php'); } $stmt=$koneksi->prepare('SELECT * FROM admin WHERE email = ? LIMIT 1'); $stmt->execute([trim($_POST['email']??'')]); $admin=$stmt->fetch(); if ($admin && password_verify($_POST['password']??'', $admin['password'])) { unset($admin['password']); $_SESSION['admin']=$admin; redirect('admin/dashboard.php'); } set_flash('error','Email atau password admin salah.'); redirect('auth/admin_login.php'); }

?>
<?php $pageTitle = 'Admin Login'; ?>
<?php include __DIR__ . '/../includes/layouts/header.php'; ?>

<div class="auth-wrapper">
    <h1>Admin Login</h1>
    <p class="subtitle">Masuk ke panel admin <?= APP_NAME ?> untuk mengelola toko.</p>

    <form method="post" action="<?= base_url('auth/admin_login.php') ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= old('email') ?>" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn-primary btn-block">Login Admin</button>
    </form>

    <p class="auth-switch" style="margin-top:20px;">
        <a href="<?= base_url('index.php') ?>" style="color:#777;font-weight:600;">&larr; Kembali ke toko</a>
    </p>
</div>

<?php include __DIR__ . '/../includes/layouts/footer.php'; ?>
