# Endpoint AJAX

Folder ini disediakan untuk endpoint yang dipanggil menggunakan JavaScript secara asynchronous.
Saat ini seluruh proses utama GadgetHub berjalan melalui file halaman langsung. Endpoint baru
dapat memanggil koneksi dengan `require_once __DIR__ . '/../config/koneksi.php';` tanpa membuat
router atau model tambahan.
