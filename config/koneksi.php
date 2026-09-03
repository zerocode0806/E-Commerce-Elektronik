<?php
/**
 * Koneksi database utama GadgetHub.
 * Semua halaman PHP memanggil file ini secara langsung.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$koneksi = new PDO(
    'mysql:host=localhost;dbname=gadgethub;charset=utf8mb4',
    'root',
    '',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
