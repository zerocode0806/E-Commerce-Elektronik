<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';

if (is_logged_in()) redirect('index.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') { if (!verify_csrf()) { set_flash('error', 'Sesi form tidak valid, silakan coba lagi.'); redirect('auth/register.php'); } $nama=trim($_POST['nama']??''); $email=trim($_POST['email']??''); $password=$_POST['password']??''; $alamat=trim($_POST['alamat']??''); $noTelepon=trim($_POST['no_telepon']??''); $_SESSION['old'] = $_POST;
    if ($nama === '' || $email === '' || strlen($password) < 6 || $alamat === '' || $noTelepon === '') { set_flash('error', 'Semua field wajib diisi dan password minimal 6 karakter.'); redirect('auth/register.php'); } $stmt=$koneksi->prepare('SELECT id_user FROM users WHERE email = ? LIMIT 1'); $stmt->execute([$email]); if ($stmt->fetch()) { set_flash('error', 'Email sudah terdaftar, silakan login.'); redirect('auth/register.php'); } $stmt=$koneksi->prepare('INSERT INTO users (nama,email,password,alamat,no_telepon) VALUES (?,?,?,?,?)'); $stmt->execute([$nama,$email,password_hash($password,PASSWORD_BCRYPT),$alamat,$noTelepon]); unset($_SESSION['old']); set_flash('success','Registrasi berhasil! Silakan login.'); redirect('auth/login.php'); }

?>
<?php $pageTitle = 'Daftar Akun'; ?>
<?php include __DIR__ . '/../includes/layouts/header.php'; ?>

<div class="auth-wrapper">
    <h1>Buat Akun Baru</h1>
    <p class="subtitle">Daftar untuk mulai belanja gadget favorit Anda di <?= APP_NAME ?>.</p>

    <form method="post" action="<?= base_url('auth/register.php') ?>">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" value="<?= old('nama') ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= old('email') ?>" required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <label>No. Telepon</label>
                <input type="text" name="no_telepon" value="<?= old('no_telepon') ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" required><?= old('alamat') ?></textarea>
        </div>
        <button type="submit" class="btn-primary btn-block">Daftar</button>
    </form>

    <p class="auth-switch">Sudah punya akun? <a href="<?= base_url('auth/login.php') ?>">Login di sini</a></p>
</div>

<?php include __DIR__ . '/../includes/layouts/footer.php'; ?>
