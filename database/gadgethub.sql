-- =========================================================
-- Database: gadgethub
-- Dibuat berdasarkan ER Diagram & Class Diagram yang diberikan
-- =========================================================

CREATE DATABASE IF NOT EXISTS gadgethub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gadgethub;

-- ---------------------------------------------------------
-- Tabel: users  (entitas "user" pada ERD)
-- ---------------------------------------------------------
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    alamat TEXT NULL,
    no_telepon VARCHAR(20) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel: admin
-- ---------------------------------------------------------
CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_telepon VARCHAR(20) NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel: barang
-- ---------------------------------------------------------
CREATE TABLE barang (
    id_barang INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(150) NOT NULL,
    kategori_barang VARCHAR(50) NOT NULL,
    deskripsi TEXT NULL,
    spesifikasi TEXT NULL,
    harga_barang DECIMAL(12,2) NOT NULL DEFAULT 0,
    stok INT NOT NULL DEFAULT 0,
    gambar VARCHAR(255) DEFAULT 'no-image.png',
    id_admin INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_admin) REFERENCES admin(id_admin) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel: keranjang
-- ---------------------------------------------------------
CREATE TABLE keranjang (
    id_keranjang INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_barang INT NOT NULL,
    qty INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_barang) REFERENCES barang(id_barang) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel: pesanan  (header order, dari class diagram "pesanan")
-- ---------------------------------------------------------
CREATE TABLE pesanan (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    grand_total DECIMAL(12,2) NOT NULL DEFAULT 0,
    status ENUM('menunggu_pembayaran','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'menunggu_pembayaran',
    alamat TEXT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel: detail_pesanan  (item per pesanan)
-- ---------------------------------------------------------
CREATE TABLE detail_pesanan (
    id_detail_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    id_barang INT NOT NULL,
    nama_barang VARCHAR(150) NOT NULL,
    qty INT NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
    FOREIGN KEY (id_barang) REFERENCES barang(id_barang)
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Tabel: pembayaran
-- ---------------------------------------------------------
CREATE TABLE pembayaran (
    id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    id_user INT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    grand_total DECIMAL(12,2) NOT NULL,
    jumlah_bayar DECIMAL(12,2) NOT NULL,
    jumlah_kembali DECIMAL(12,2) NOT NULL DEFAULT 0,
    metode_pembayaran ENUM('transfer_bank','cod','e_wallet') NOT NULL DEFAULT 'transfer_bank',
    status_verifikasi ENUM('menunggu','terverifikasi','ditolak') NOT NULL DEFAULT 'menunggu',
    FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================
-- SEED DATA
-- =========================================================

-- Admin default (email: admin@gadgethub.com | password: admin123)
INSERT INTO admin (nama, email, password, no_telepon) VALUES
('Administrator', 'admin@gadgethub.com', '$2b$10$G9EkfGqc3et4Q06CzxidwunyHO9ChFqkVF38XSa0oMA4OCQS/aDsm', '081234567890');

-- Contoh user (email: budi@mail.com | password: password123)
INSERT INTO users (nama, email, password, alamat, no_telepon) VALUES
('Budi Santoso', 'budi@mail.com', '$2b$10$FITeUTaxKC3tSizk4N2byu5rO5mlywVBIamh2iQruzCZU7kcvINN6', 'Jl. Merdeka No. 10, Sidoarjo', '081211112222');

-- Produk contoh (kategori sesuai nav pada index.html)
-- Kolom `gambar` menyimpan NAMA FILE saja (bukan URL penuh); file gambar sudah
-- disediakan di public/assets/img/products/. URL lengkap dibentuk otomatis oleh
-- aplikasi (lihat fungsi product_image() di app/core/helpers.php) sesuai domain
-- yang sedang diakses (localhost, IP LAN, ataupun tunnel seperti ngrok).
INSERT INTO barang (nama_barang, kategori_barang, deskripsi, spesifikasi, harga_barang, stok, gambar) VALUES
('Motorola Signature', 'Smartphones', 'Smartphone flagship dengan performa tinggi dan kamera mumpuni untuk kebutuhan harian maupun profesional.', 'RAM 12GB, Storage 256GB, Layar 6.7" AMOLED 120Hz, Baterai 5000mAh, Kamera 108MP', 12000000, 15, 'seed-motorola-signature.png'),
('Aurora Book Pro', 'Laptops', 'Laptop tipis dan ringan untuk produktivitas maksimal di mana saja.', 'Intel Core i7, RAM 16GB, SSD 512GB, Layar 14" QHD, Windows 11', 15500000, 8, 'seed-aurora-book-pro.png'),
('SoundWave Buds', 'Audio', 'TWS earbuds dengan Active Noise Cancelling dan suara jernih.', 'Bluetooth 5.3, ANC, Baterai 30 jam (case), IPX5', 1200000, 40, 'seed-soundwave-buds.png'),
('PulseFit Watch', 'Wearables', 'Smartwatch dengan monitor kesehatan lengkap dan baterai tahan lama.', 'Layar AMOLED 1.4", Heart Rate, SpO2, GPS, Baterai 10 hari', 1850000, 25, 'seed-pulsefit-watch.png'),
('AirCharge Stand', 'Accessories', 'Wireless charging stand 3-in-1 untuk HP, earbuds, dan smartwatch.', 'Fast Charging 15W, Kompatibel Qi', 450000, 60, 'seed-aircharge-stand.png'),
('Nova X12', 'Smartphones', 'Smartphone kelas menengah dengan kamera malam terbaik di kelasnya.', 'RAM 8GB, Storage 128GB, Layar 6.5" IPS 90Hz, Baterai 5000mAh', 3800000, 30, 'seed-nova-x12.png'),
('Zenith Air 13', 'Laptops', 'Ultrabook super ringan cocok untuk mahasiswa dan pekerja mobile.', 'Intel Core i5, RAM 8GB, SSD 256GB, Layar 13.3" FHD', 8900000, 12, 'seed-zenith-air-13.png'),
('BassLine Speaker', 'Audio', 'Speaker bluetooth portable dengan bass menggelegar dan tahan air.', 'Bluetooth 5.0, IPX7, Baterai 12 jam, 20W', 650000, 50, 'seed-bassline-speaker.png');
