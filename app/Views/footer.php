    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <div class="brand-container">
                            <span class="brand-icon">C</span>
                            <div class="brand-text">
                                <span class="brand-name">CENDRATAMA</span>
                                <span class="brand-subtitle">Cendrawasih Digikarya Pertama</span>
                            </div>
                        </div>
                        <p class="footer-description">PT Cendrawasih Digikarya Pertama menyediakan solusi teknologi informasi terbaik untuk mendukung perkembangan bisnis Anda.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h4>Layanan</h4>
                    <ul class="footer-links">
                        <li><a href="<?= base_url('layanan/website') ?>">Website Custom</a></li>
                        <li><a href="<?= base_url('layanan/it-support') ?>">IT Support</a></li>
                        <li><a href="<?= base_url('layanan/mesin-kasir') ?>">Pasang Mesin Kasir</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h4>Produk</h4>
                    <ul class="footer-links">
                        <li><a href="<?= base_url('produk/cctv') ?>">Pengadaan CCTV</a></li>
                        <li><a href="<?= base_url('produk/komputer') ?>">Pengadaan Komputer</a></li>
                        <li><a href="<?= base_url('produk/jaringan') ?>">Pengadaan Jaringan</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-12">
                    <h4>Kontak Kami</h4>
                    <ul class="footer-contact">
                        <li><i class="fas fa-map-marker-alt"></i> <span>Jl. Teknologi No. 123, Jakarta, Indonesia</span></li>
                        <li><i class="fas fa-phone"></i> <span>+62 21 1234 5678</span></li>
                        <li><i class="fas fa-envelope"></i> <span>info@cendratama.co.id</span></li>
                        <li><i class="fas fa-clock"></i> <span>Senin - Jumat: 08:00 - 17:00</span></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> PT Cendrawasih Digikarya Pertama (CENDRATAMA). All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>