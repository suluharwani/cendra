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
                        <h1 class="hero-title">Solusi Teknologi <span class="highlight">Terpercaya</span> untuk Bisnis Anda</h1>
                        <p class="hero-subtitle"><?= esc(env('app.companyName', 'Cendratama Teknologi')) ?> membantu kebutuhan teknologi bisnis Anda, mulai dari CCTV, mesin & aplikasi kasir, pengadaan perangkat elektronik, pembuatan website, hingga aplikasi custom.</p>
                        <div class="hero-buttons">
                            <a href="<?= base_url('konsultasi') ?>" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Konsultasi Kebutuhan
                            </a>
                            <a href="<?= base_url('layanan') ?>" class="btn btn-outline">
                                <i class="fas fa-cogs me-2"></i>Lihat Layanan
                            </a>
                        </div>
                        
                        <!-- Hero Features -->
                        <div class="hero-features mt-4">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Solusi disesuaikan dengan kebutuhan dan lokasi</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Pendampingan instalasi dan penggunaan sistem</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Dukungan teknis dan konsultasi via WhatsApp/telepon</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-image">
                            <img src="<?= base_url('assets/images/hero-illustration.svg') ?>" alt="Layanan Teknologi Cendratama" class="img-fluid rounded" onerror="this.src='<?= base_url('logo.png') ?>'">
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
                            <img src="<?= base_url('assets/images/about-illustration.svg') ?>" alt="Tentang Cendratama" class="img-fluid rounded" onerror="this.src='<?= base_url('logo.png') ?>'">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="features-content">
                            <h2>Mengapa Memilih <span class="brand-highlight"><?= esc(env('app.companyShortName', 'Cendratama')) ?></span>?</h2>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Solusi Sesuai Kebutuhan</h4>
                                    <p>Rekomendasi perangkat dan sistem disesuaikan dengan kondisi lokasi, skala usaha, dan tujuan operasional Anda.</p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Pendampingan & Dukungan</h4>
                                    <p>Kami membantu proses instalasi, pengaturan, hingga penggunaan agar sistem dapat berjalan dengan baik.</p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Perangkat & Garansi</h4>
                                    <p>Pengadaan perangkat dengan pilihan produk bergaransi resmi serta penjelasan spesifikasi yang transparan.</p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="feature-text">
                                    <h4>Layanan Terintegrasi</h4>
                                    <p>Dari keamanan (CCTV), operasional (kasir/POS), hingga website dan aplikasi, Anda bisa berkonsultasi dalam satu tempat.</p>
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
                                <img src="<?= $portfolio['image'] ?>" alt="<?= esc($portfolio['title']) ?>" class="img-fluid" onerror="this.src='<?= base_url('logo.png') ?>'">
                                <div class="portfolio-overlay">
                                    <a href="<?= $portfolio['url'] ?>" class="portfolio-link" title="Lihat portofolio">
                                        <i class="fas fa-eye"></i>
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
                    <h2>Fokus Layanan Kami</h2>
                    <p>Pendekatan kerja yang kami terapkan dalam setiap kebutuhan klien</p>
                </div>
                
                <div class="testimonials-slider">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="client-avatar" style="display:flex;align-items:center;justify-content:center;background:#059669;color:#fff;font-weight:700;">
                                <i class="fas fa-video"></i>
                            </div>
                            <div class="client-info">
                                <h5>Keamanan & Monitoring</h5>
                                <p>Solusi CCTV</p>
                            </div>
                        </div>
                        <div class="testimonial-body">
                            <p>Perencanaan titik kamera, instalasi rapi, serta konfigurasi akses monitoring disesuaikan dengan kebutuhan pengawasan di lokasi.</p>
                        </div>
                    </div>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="client-avatar" style="display:flex;align-items:center;justify-content:center;background:#059669;color:#fff;font-weight:700;">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <div class="client-info">
                                <h5>Operasional Penjualan</h5>
                                <p>Kasir & POS</p>
                            </div>
                        </div>
                        <div class="testimonial-body">
                            <p>Pemilihan perangkat dan setup sistem kasir membantu pencatatan transaksi serta laporan penjualan berjalan lebih teratur.</p>
                        </div>
                    </div>
                    
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="client-avatar" style="display:flex;align-items:center;justify-content:center;background:#059669;color:#fff;font-weight:700;">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <div class="client-info">
                                <h5>Digitalisasi Bisnis</h5>
                                <p>Website & Aplikasi</p>
                            </div>
                        </div>
                        <div class="testimonial-body">
                            <p>Pembuatan website dan aplikasi disesuaikan dengan proses bisnis, dengan pendampingan agar solusi mudah digunakan.</p>
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
                        <h2>Butuh Solusi Teknologi untuk Bisnis Anda?</h2>
                        <p class="mb-0">Diskusikan kebutuhan CCTV, kasir/POS, pengadaan perangkat, website, atau aplikasi bersama tim <?= esc(env('app.companyShortName', 'Cendratama')) ?>.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="<?= base_url('kontak') ?>" class="btn btn-cta-large">
                            <i class="fas fa-envelope me-2"></i>Hubungi Kami
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
                            <h3>Dapatkan Informasi Terbaru</h3>
                            <p>Terima informasi seputar layanan, solusi teknologi, dan pembaruan dari <?= esc(env('app.companyShortName', 'Cendratama')) ?>.</p>
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
                                        Saya setuju dengan <a href="<?= base_url('kebijakan-privasi') ?>">kebijakan privasi</a>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>