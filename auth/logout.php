<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
unset($_SESSION['user']); set_flash('success', 'Anda telah keluar.'); redirect('index.php');