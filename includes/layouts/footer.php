    <footer>
        <div class="footer-container">
            <div class="footer-col">
                <h4>Kategori</h4>
                <ul>
                    <li><a href="<?= base_url('index.php?kategori=Smartphones') ?>">Smartphone</a></li>
                    <li><a href="<?= base_url('index.php?kategori=Audio') ?>">Audio</a></li>
                    <li><a href="<?= base_url('index.php?kategori=Wearables') ?>">Wearable</a></li>
                    <li><a href="<?= base_url('index.php?kategori=Accessories') ?>">Accessories</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Dukungan</h4>
                <ul>
                    <li><a href="#">Panduan Pengguna</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Live Chat</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Layanan</h4>
                <ul>
                    <li><a href="#">Garansi</a></li>
                    <li><a href="#">Service</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Tentang</h4>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                </ul>
            </div>
            <div class="footer-col social-contact">
                <h4>Ikuti Media Sosial Kami</h4>
                <div class="social-icons">
                    <i class="fa-brands fa-facebook"></i>
                    <i class="fa-brands fa-instagram"></i>
                    <i class="fa-brands fa-youtube"></i>
                </div>
                <h4 style="margin-top: 20px;">Customer Service</h4>
                <p><i class="fa-brands fa-whatsapp"></i> Chat Langsung</p>
                <p><i class="fa-solid fa-phone"></i> (+62) 8234567890</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Copyright &copy; <?= date('Y') ?> <?= APP_NAME ?>. All Rights Reserved.</p>
        </div>
    </footer>
    <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
