<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';

if (is_logged_in()) redirect('index.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') { if (!verify_csrf()) { set_flash('error', 'Sesi form tidak valid, silakan coba lagi.'); redirect('auth/login.php'); } $stmt = $koneksi->prepare('SELECT * FROM users WHERE email = ? LIMIT 1'); $stmt->execute([trim($_POST['email'] ?? '')]); $user = $stmt->fetch(); if ($user && password_verify($_POST['password'] ?? '', $user['password'])) { unset($user['password']); $_SESSION['user'] = $user; unset($_SESSION['old']); set_flash('success', 'Login berhasil, selamat belanja!'); redirect('index.php'); } set_flash('error', 'Email atau password salah.'); redirect('auth/login.php'); }

?>
<?php $pageTitle = 'Login'; ?>
<?php include __DIR__ . '/../includes/layouts/header.php'; ?>

<div class="auth-wrapper">
    <h1>Selamat Datang</h1>
    <p class="subtitle">Masuk ke akun <?= APP_NAME ?> Anda untuk mulai belanja.</p>

    <form method="post" action="<?= base_url('auth/login.php') ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= old('email') ?>" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn-primary btn-block">Login</button>
    </form>

    <p class="auth-switch">Belum punya akun? <a href="<?= base_url('auth/register.php') ?>">Daftar di sini</a></p>
    <p class="auth-switch" style="margin-top:6px;">
        <a href="<?= base_url('auth/admin_login.php') ?>" style="color:#777;font-weight:600;">Login sebagai Admin &rarr;</a>
    </p>
</div>

<?php include __DIR__ . '/../includes/layouts/footer.php'; ?>
