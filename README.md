# GadgetHub — E-Commerce Native PHP

Aplikasi e-commerce sederhana yang dikembangkan dari desain (index & detail produk) dan
diagram **ERD** serta **Class Diagram** yang telah disediakan. Dibangun dengan **native PHP**
(tanpa framework). Setiap halaman memiliki file PHP sendiri yang memuat fungsi dan proses
fiturnya, sedangkan layout bersama dipanggil menggunakan `include`.

## Fitur

### Customer
- Melihat produk (beranda, filter kategori, pencarian, detail produk)
- Registrasi & login akun
- Keranjang belanja (tambah, ubah jumlah, hapus)
- Checkout (isi alamat, pilih metode pembayaran, catat jumlah bayar)
- Riwayat pesanan & status pesanan

### Admin
- Login admin terpisah dari customer
- Dashboard ringkasan (total produk, pesanan, pembayaran menunggu verifikasi, dsb.)
- Kelola barang: tambah, edit, hapus (termasuk upload gambar)
- Kelola pesanan: ubah status (menunggu pembayaran → diproses → dikirim → selesai/dibatalkan)
- Verifikasi pembayaran (terima/tolak); jika diterima, status pesanan otomatis menjadi "diproses"

Query untuk setiap fungsi ditulis langsung di halaman yang memprosesnya menggunakan PDO
prepared statement. Dengan demikian, proyek tidak menggunakan folder model maupun controller.

## Struktur Folder

```
gadgethub/
├── admin/                 # Halaman dashboard dan pengelolaan admin
├── customer/              # Halaman customer berdasarkan fitur
│   ├── product/, cart/, order/
├── auth/                  # Login, register, dan logout
├── ajax/                  # Endpoint AJAX (disiapkan untuk kebutuhan asynchronous)
├── config/                # config.php dan koneksi database (koneksi.php)
├── includes/              # helpers dan layout bersama
├── assets/                # CSS, JavaScript, dan gambar produk
├── index.php              # Halaman utama toko dan daftar produk
├── .htaccess
└── database/
    └── gadgethub.sql      # Struktur tabel + data contoh (seed)
```

## Cara Instalasi (XAMPP / Laragon / LAMP)

1. **Salin folder** `gadgethub` ke dalam `htdocs` (XAMPP) atau `www` (Laragon).
2. **Buat database**: buka phpMyAdmin → Import → pilih file `database/gadgethub.sql`.
   File ini otomatis membuat database `gadgethub`, seluruh tabel, dan data contoh.
3. **Atur koneksi database** di `config/koneksi.php` bila perlu (default sudah cocok
   dengan XAMPP: host `localhost`, user `root`, password kosong).
4. Akses aplikasi melalui:
   ```
   http://localhost/gadgethub/index.php
   ```
5. Selesai! Aplikasi siap digunakan.

### Catatan struktur dan routing

Struktur halaman mengikuti pola proyek sederhana berbasis peran dan fitur:
`admin/`, `customer/`, `auth/`, `ajax/`, `config/`, dan `database/`. `index.php` adalah halaman
utama toko yang menampilkan hero, kategori, pencarian, dan produk. Tidak ada router, controller,
atau autoloader tambahan. Logika proses dan query berada di file masing-masing, misalnya
`admin/product_form.php` untuk tambah/edit barang dan `customer/order/checkout.php` untuk
checkout. Setiap halaman memuat koneksi menggunakan `require_once` berbasis `__DIR__`.

## Akun Default (dari seed database)

| Peran     | Email                  | Password      |
|-----------|-------------------------|---------------|
| Admin     | admin@gadgethub.com     | admin123      |
| Customer  | budi@mail.com           | password123   |

Atau silakan **Daftar** akun customer baru melalui halaman Register.

## Catatan Teknis

- Semua query database menggunakan **PDO + prepared statement** (aman dari SQL Injection).
- Password disimpan dengan **`password_hash()` (bcrypt)**.
- Proteksi **CSRF token** pada setiap form POST.
- Session PHP dipakai untuk autentikasi customer (`$_SESSION['user']`) dan admin
  (`$_SESSION['admin']`) secara terpisah.
- Stok barang otomatis berkurang saat pesanan dibuat (di dalam transaksi database).
- Aplikasi sudah diuji end-to-end (register, login, tambah keranjang, checkout, verifikasi
  pembayaran, CRUD barang oleh admin) menggunakan PHP 8.3 + MariaDB.

## Pengembangan Lanjutan (opsional)

- Tambah halaman edit profil customer.
- Tambah fitur upload bukti transfer (saat ini jumlah bayar diinput manual).
- Tambah multi-gambar produk (saat ini 1 gambar per produk, sesuai kolom `gambar` pada ERD).
