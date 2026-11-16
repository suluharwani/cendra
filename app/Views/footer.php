    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <div class="brand-container">
                            <div class="brand-text">
                                <span class="brand-name"><?= esc(env('app.companyShortName', 'Cendratama')) ?></span>
                                <span class="brand-subtitle"><?= esc(env('app.companyName', 'Cendratama Teknologi')) ?></span>
                            </div>
                        </div>
                        <p class="footer-description"><?= esc(env('app.companyTagline', 'Solusi Teknologi Terpercaya untuk Bisnis Anda')) ?>. Melayani kebutuhan CCTV, kasir/POS, pengadaan perangkat, website, dan aplikasi sesuai kebutuhan bisnis.</p>
                        <div class="social-links">
                            <a href="https://wa.me/<?= esc(env('app.whatsapp', '6281327341834')) ?>" target="_blank" rel="noopener noreferrer" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            <a href="mailto:<?= esc(env('app.email', 'adm.ppa@cendratama.com')) ?>" title="Email"><i class="fas fa-envelope"></i></a>
                            <a href="tel:<?= esc(env('app.phone', '081327341834')) ?>" title="Telepon"><i class="fas fa-phone"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h4>Layanan</h4>
                    <ul class="footer-links">
                        <li><a href="<?= base_url('layanan/cctv') ?>">CCTV</a></li>
                        <li><a href="<?= base_url('layanan/kasir') ?>">Mesin & Aplikasi Kasir</a></li>
                        <li><a href="<?= base_url('layanan/website') ?>">Pembuatan Website</a></li>
                        <li><a href="<?= base_url('layanan/custom') ?>">Aplikasi Custom</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h4>Informasi</h4>
                    <ul class="footer-links">
                        <li><a href="<?= base_url('tentang') ?>">Tentang Kami</a></li>
                        <li><a href="<?= base_url('portfolio') ?>">Portofolio</a></li>
                        <li><a href="<?= base_url('kontak') ?>">Kontak</a></li>
                        <li><a href="<?= base_url('kebijakan-privasi') ?>">Kebijakan Privasi</a></li>
                        <li><a href="<?= base_url('syarat-ketentuan') ?>">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-12">
                    <h4>Kontak Kami</h4>
                    <ul class="footer-contact">
                        <li><i class="fas fa-map-marker-alt"></i> <span><?= esc(env('app.address', 'Klambu, Grobogan, Jawa Tengah')) ?></span></li>
                        <li><i class="fas fa-phone"></i> <a href="tel:<?= esc(env('app.phone', '081327341834')) ?>"><?= esc(env('app.phone', '081327341834')) ?></a></li>
                        <li><i class="fas fa-envelope"></i> <a href="mailto:<?= esc(env('app.email', 'adm.ppa@cendratama.com')) ?>"><?= esc(env('app.email', 'adm.ppa@cendratama.com')) ?></a></li>
                        <li><i class="fab fa-whatsapp"></i> <a href="https://wa.me/<?= esc(env('app.whatsapp', '6281327341834')) ?>" target="_blank" rel="noopener noreferrer">WhatsApp <?= esc(env('app.phone', '081327341834')) ?></a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= esc(env('app.companyName', 'Cendratama Teknologi')) ?>. Hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>