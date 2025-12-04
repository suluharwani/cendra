    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="hero-title">Solusi Digital <span class="highlight">Terpercaya</span> untuk Bisnis Anda</h1>
                        <p class="hero-subtitle">PT Cendrawasih Digikarya Pertama (CENDRATAMA) menyediakan layanan pembuatan website, IT support, dan pengadaan perangkat TI terbaik untuk mendukung perkembangan bisnis Anda.</p>
                        <div class="hero-buttons">
                            <a href="<?= base_url('layanan') ?>" class="btn btn-primary">Lihat Layanan</a>
                            <a href="<?= base_url('kontak') ?>" class="btn btn-outline">Hubungi Kami</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-image">
                            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Solusi Digital" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="services-section" id="layanan">
            <div class="container">
                <div class="section-header">
                    <h2>Layanan Unggulan Kami</h2>
                    <p>Kami menyediakan berbagai solusi teknologi informasi untuk kebutuhan bisnis Anda</p>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-code"></i>
                            </div>
                            <h3>Website Custom</h3>
                            <p>Pembuatan website sesuai kebutuhan bisnis Anda dengan teknologi terkini dan desain responsif.</p>
                            <a href="<?= base_url('layanan/website') ?>" class="service-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3>IT Support</h3>
                            <p>Layanan dukungan teknis IT untuk menjaga sistem teknologi informasi Anda berjalan optimal.</p>
                            <a href="<?= base_url('layanan/it-support') ?>" class="service-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <h3>Pasang Mesin Kasir</h3>
                            <p>Instalasi dan setup mesin kasir dengan sistem yang terintegrasi untuk usaha retail dan hospitality.</p>
                            <a href="<?= base_url('layanan/mesin-kasir') ?>" class="service-link">Selengkapnya <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section class="products-section" id="produk">
            <div class="container">
                <div class="section-header">
                    <h2>Pengadaan Barang & Perangkat</h2>
                    <p>Kami menyediakan perangkat teknologi berkualitas untuk mendukung operasional bisnis Anda</p>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://images.unsplash.com/photo-1590959651373-a3db0f38a961?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="CCTV" class="img-fluid">
                            </div>
                            <h3>Pengadaan CCTV</h3>
                            <p>Pemasangan sistem keamanan CCTV untuk kantor, toko, pabrik, dan properti lainnya.</p>
                            <a href="<?= base_url('produk/cctv') ?>" class="product-btn">Detail Produk</a>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Komputer" class="img-fluid">
                            </div>
                            <h3>Pengadaan Komputer</h3>
                            <p>Supply komputer, laptop, dan perangkat pendukung untuk kebutuhan kantor dan bisnis.</p>
                            <a href="<?= base_url('produk/komputer') ?>" class="product-btn">Detail Produk</a>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="Jaringan" class="img-fluid">
                            </div>
                            <h3>Pengadaan Jaringan</h3>
                            <p>Instalasi dan konfigurasi jaringan internet, intranet, dan sistem komunikasi data.</p>
                            <a href="<?= base_url('produk/jaringan') ?>" class="product-btn">Detail Produk</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section class="about-section" id="tentang">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="about-content">
                            <h2>Tentang <span class="brand-highlight">CENDRATAMA</span></h2>
                            <p><strong>CENDRATAMA</strong> adalah singkatan dari <strong>Cendrawasih Digikarya Pertama</strong>, sebuah perusahaan yang berfokus pada penyediaan solusi teknologi informasi dan komunikasi.</p>
                            <p>Sebagai <strong>PT Cendrawasih Digikarya Pertama</strong>, kami berkomitmen untuk memberikan layanan terbaik dalam bidang pengembangan website, IT support, dan pengadaan perangkat teknologi.</p>
                            <p>Dengan tim profesional yang berpengalaman, kami siap membantu bisnis Anda berkembang dengan dukungan teknologi yang tepat.</p>
                            <a href="<?= base_url('tentang') ?>" class="btn btn-about">Selengkapnya Tentang Kami</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-stats">
                            <div class="row">
                                <div class="col-6">
                                    <div class="stat-box">
                                        <h3>50+</h3>
                                        <p>Proyek Selesai</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-box">
                                        <h3>30+</h3>
                                        <p>Klien Puas</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-box">
                                        <h3>5+</h3>
                                        <p>Tahun Pengalaman</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-box">
                                        <h3>24/7</h3>
                                        <p>Support Teknis</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Siap Mengembangkan Bisnis Anda dengan Teknologi?</h2>
                    <p>Hubungi kami sekarang untuk konsultasi gratis dan dapatkan solusi terbaik untuk kebutuhan IT bisnis Anda.</p>
                    <a href="<?= base_url('kontak') ?>" class="btn btn-cta-large">Konsultasi Gratis <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </section>
    </main>