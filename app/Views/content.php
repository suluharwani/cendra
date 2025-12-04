    <?php
/**
 * Membatasi jumlah karakter dengan menjaga integritas tag HTML
 *
 * @param string $str String yang akan dibatasi
 * @param int $limit Jumlah maksimal karakter
 * @param string $suffix Akhiran yang ditambahkan jika string dipotong
 * @return string String yang sudah dibatasi
 */
function character_limiter($str, $limit = 100, $suffix = '...')
{
    if (empty($str)) {
        return '';
    }
    
    // Jika string tanpa tag HTML lebih pendek dari limit
    if (mb_strlen(strip_tags($str)) <= $limit) {
        return $str;
    }
    
    // Potong dengan menjaga tag HTML
    $truncated = mb_substr($str, 0, $limit);
    
    // Tutup tag HTML yang terbuka
    $truncated = preg_replace('/<[^>]*$/u', '', $truncated);
    
    // Cari tag terakhir yang dibuka
    preg_match_all('/<([a-z]+)[^>]*>/i', $truncated, $open_tags);
    preg_match_all('/<\/([a-z]+)>/i', $truncated, $close_tags);
    
    // Tutup semua tag yang masih terbuka
    $open_tags_count = count($open_tags[1]);
    $close_tags_count = count($close_tags[1]);
    
    if ($open_tags_count > $close_tags_count) {
        for ($i = $close_tags_count; $i < $open_tags_count; $i++) {
            $truncated .= '</' . $open_tags[1][$i] . '>';
        }
    }
    
    return $truncated . $suffix;
}
?>
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
                            <a href="<?= base_url('konsultasi') ?>" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Konsultasi Gratis
                            </a>
                            <a href="<?= base_url('portfolio') ?>" class="btn btn-outline">
                                <i class="fas fa-eye me-2"></i>Lihat Portfolio
                            </a>
                        </div>
                        
                        <!-- Hero Features -->
                        <div class="hero-features mt-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Garansi 1 tahun untuk semua layanan</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Support 24/7 via WhatsApp & Telepon</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Gratis konsultasi awal</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-image">
                            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Solusi Digital" class="img-fluid rounded">
                            <div class="hero-badge">
                                <div class="badge-content">
                                    <span class="badge-title">500+</span>
                                    <span class="badge-subtitle">Proyek Selesai</span>
                                </div>
                            </div>
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
                    <?php foreach($services as $service): ?>
                    <div class="col-md-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="<?= $service['icon'] ?>"></i>
                            </div>
                            <h3><?= $service['title'] ?></h3>
                            <p><?= $service['description'] ?></p>
                            <div class="service-features">
                                <?php foreach($service['features'] as $feature): ?>
                                <span class="feature-badge"><?= $feature ?></span>
                                <?php endforeach; ?>
                            </div>
                            <a href="<?= base_url('layanan/' . $service['slug']) ?>" class="service-link">
                                Selengkapnya <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-5">
                    <a href="<?= base_url('layanan/semua') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>Lihat Semua Layanan
                    </a>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="features-image">
                            <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Features" class="img-fluid rounded">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="features-content">
                            <h2>Mengapa Memilih <span class="brand-highlight">CENDRATAMA</span>?</h2>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-award"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Profesional & Berpengalaman</h4>
                                    <p>Tim ahli dengan pengalaman lebih dari 5 tahun di bidang IT dan pengembangan software.</p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Support 24/7</h4>
                                    <p>Layanan dukungan teknis tersedia 24 jam untuk memastikan bisnis Anda berjalan lancar.</p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Garansi & Maintenance</h4>
                                    <p>Semua produk dan layanan dilengkapi dengan garansi dan layanan perawatan berkala.</p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Harga Kompetitif</h4>
                                    <p>Penawaran harga yang kompetitif dengan kualitas terjamin dan transparan.</p>
                                </div>
                            </div>
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
                    <?php foreach($products as $product): ?>
                    <div class="col-md-4">
                        <div class="product-card">
                            <div class="product-badge"><?= $product['badge'] ?></div>
                            <div class="product-image">
                                <img src="<?= $product['image'] ?>" alt="<?= $product['title'] ?>" class="img-fluid">
                            </div>
                            <div class="product-body">
                                <h3><?= $product['title'] ?></h3>
                                <p><?= $product['description'] ?></p>
                                
                                <?php if($product['price']): ?>
                                <div class="product-price">
                                    <span class="price"><?= $product['price'] ?></span>
                                    <?php if($product['old_price']): ?>
                                    <span class="old-price"><?= $product['old_price'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                
                                <div class="product-actions">
                                    <a href="<?= base_url('produk/' . $product['slug']) ?>" class="product-btn">
                                        <i class="fas fa-info-circle me-2"></i>Detail Produk
                                    </a>
                                    <button class="btn btn-wishlist" data-product-id="<?= $product['id'] ?>">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-5">
                    <a href="<?= base_url('produk/semua') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-box-open me-2"></i>Lihat Katalog Lengkap
                    </a>
                </div>
            </div>
        </section>

        <!-- Portfolio Section -->
        <section class="portfolio-section" id="portfolio">
            <div class="container">
                <div class="section-header">
                    <h2>Portfolio Kami</h2>
                    <p>Beberapa proyek yang telah kami selesaikan untuk klien-klien kami</p>
                </div>
                
                <div class="row">
                    <?php foreach($portfolios as $portfolio): ?>
                    <div class="col-md-4">
                        <div class="portfolio-card">
                            <div class="portfolio-image">
                                <img src="<?= $portfolio['image'] ?>" alt="<?= $portfolio['title'] ?>" class="img-fluid">
                                <div class="portfolio-overlay">
                                    <a href="<?= $portfolio['url'] ?>" target="_blank" class="portfolio-link">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="portfolio-body">
                                <h4><?= $portfolio['title'] ?></h4>
                                <p><?= $portfolio['category'] ?></p>
                                <div class="portfolio-tech">
                                    <?php foreach($portfolio['tech'] as $tech): ?>
                                    <span class="tech-badge"><?= $tech ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-5">
                    <a href="<?= base_url('portfolio') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-images me-2"></i>Lihat Semua Portfolio
                    </a>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials-section">
            <div class="container">
                <div class="section-header">
                    <h2>Apa Kata Klien Kami</h2>
                    <p>Testimoni dari klien yang telah menggunakan layanan kami</p>
                </div>
                
                <div class="testimonials-slider">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="client-avatar">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Client">
                            </div>
                            <div class="client-info">
                                <h5>Budi Santoso</h5>
                                <p>Owner Restoran Sederhana</p>
                            </div>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="testimonial-body">
                            <p>"Website yang dibuat CENDRATAMA sangat membantu bisnis restoran saya. Order online meningkat 40% sejak website diluncurkan."</p>
                        </div>
                    </div>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="client-avatar">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Client">
                            </div>
                            <div class="client-info">
                                <h5>Maya Wijaya</h5>
                                <p>Manager CV. Sejahtera Abadi</p>
                            </div>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                        <div class="testimonial-body">
                            <p>"IT Support dari CENDRATAMA sangat responsif dan profesional. Masalah jaringan cepat teratasi, tidak mengganggu operasional."</p>
                        </div>
                    </div>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="client-avatar">
                                <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Client">
                            </div>
                            <div class="client-info">
                                <h5>Hendra Pratama</h5>
                                <p>Direktur PT. Makmur Jaya</p>
                            </div>
                            <div class="rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="testimonial-body">
                            <p>"Instalasi CCTV untuk pabrik kami dilakukan dengan rapi dan profesional. Sistem berjalan lancar, monitoring lebih mudah."</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Blog Section -->
        <section class="blog-section">
            <div class="container">
                <div class="section-header">
                    <h2>Artikel & Tips Terbaru</h2>
                    <p>Informasi terkini seputar teknologi dan bisnis</p>
                </div>
                
                <div class="row">
                    <?php foreach($blogs as $blog): ?>
                    <div class="col-md-4">
                        <div class="blog-card">
                            <div class="blog-image">
                                <img src="<?= $blog['image'] ?>" alt="<?= $blog['title'] ?>" class="img-fluid">
                                <div class="blog-date">
                                    <span class="day"><?= date('d', strtotime($blog['date'])) ?></span>
                                    <span class="month"><?= date('M', strtotime($blog['date'])) ?></span>
                                </div>
                            </div>
                            <div class="blog-body">
                                <div class="blog-category"><?= $blog['category'] ?></div>
                                <h4><a href="<?= base_url('blog/' . $blog['slug']) ?>"><?= $blog['title'] ?></a></h4>
                                <p><?= character_limiter($blog['excerpt'], 100) ?></p>
                                <div class="blog-meta">
                                    <span><i class="far fa-eye me-1"></i> <?= $blog['views'] ?> views</span>
                                    <span><i class="far fa-clock me-1"></i> <?= $blog['read_time'] ?> min read</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-5">
                    <a href="<?= base_url('blog') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-newspaper me-2"></i>Baca Semua Artikel
                    </a>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2>Siap Mengembangkan Bisnis Anda dengan Teknologi?</h2>
                        <p class="mb-0">Hubungi kami sekarang untuk konsultasi gratis dan dapatkan solusi terbaik untuk kebutuhan IT bisnis Anda.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="<?= base_url('konsultasi') ?>" class="btn btn-cta-large">
                            <i class="fas fa-calendar-alt me-2"></i>Jadwalkan Konsultasi
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="newsletter-section">
            <div class="container">
                <div class="newsletter-box">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h3>Berlangganan Newsletter</h3>
                            <p>Dapatkan tips, promo, dan informasi terbaru seputar teknologi langsung di email Anda.</p>
                        </div>
                        <div class="col-lg-6">
                            <form id="newsletterForm" class="newsletter-form">
                                <div class="input-group">
                                    <input type="email" class="form-control" placeholder="Masukkan email Anda" required>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-paper-plane"></i> Berlangganan
                                    </button>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="privacyCheck" required>
                                    <label class="form-check-label" for="privacyCheck">
                                        Saya setuju dengan <a href="<?= base_url('privacy') ?>">kebijakan privasi</a>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>