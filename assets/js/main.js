document.addEventListener('DOMContentLoaded', function () {

    // ==== Tabs (Description / Specification) di halaman detail produk ====
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('[data-tab-panel]');
    tabBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            tabBtns.forEach((b) => b.classList.remove('active'));
            btn.classList.add('active');
            const target = btn.getAttribute('data-tab');
            tabPanels.forEach((panel) => {
                panel.style.display = panel.getAttribute('data-tab-panel') === target ? 'block' : 'none';
            });
        });
    });

    // ==== Galeri thumbnail produk ====
    const thumbs = document.querySelectorAll('.thumbnails img');
    const mainImage = document.querySelector('.main-image');
    thumbs.forEach((thumb) => {
        thumb.addEventListener('click', () => {
            thumbs.forEach((t) => t.classList.remove('active'));
            thumb.classList.add('active');
            if (mainImage) mainImage.src = thumb.src.replace('80x100', '400x500');
        });
    });

    // ==== Konfirmasi hapus (produk / item keranjang) ====
    document.querySelectorAll('[data-confirm]').forEach((el) => {
        el.addEventListener('submit', (e) => {
            if (!confirm(el.getAttribute('data-confirm'))) {
                e.preventDefault();
            }
        });
    });

    // ==== Auto submit form update qty keranjang saat tombol +/- ditekan ====
    document.querySelectorAll('.qty-form').forEach((form) => {
        const input = form.querySelector('input[name="qty"]');
        const minus = form.querySelector('.qty-minus');
        const plus = form.querySelector('.qty-plus');
        if (minus) minus.addEventListener('click', () => {
            input.value = Math.max(1, parseInt(input.value || '1') - 1);
            form.submit();
        });
        if (plus) plus.addEventListener('click', () => {
            const max = parseInt(input.getAttribute('max') || '9999');
            input.value = Math.min(max, parseInt(input.value || '1') + 1);
            form.submit();
        });
    });

    // ==== Preview gambar upload produk (admin) ====
    const imgInput = document.querySelector('#gambar-input');
    const imgPreview = document.querySelector('#gambar-preview');
    if (imgInput && imgPreview) {
        imgInput.addEventListener('change', () => {
            const file = imgInput.files[0];
            if (file) {
                imgPreview.src = URL.createObjectURL(file);
                imgPreview.style.display = 'block';
            }
        });
    }
});
