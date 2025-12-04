<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <!-- Logo/Brand -->
        <a class="navbar-brand" href="<?= base_url() ?>">
            <div class="brand-container">
                <img src="<?= base_url('logo.png') ?>" alt="CENDRATAMA Logo" 
     class="brand-icon me-2 img-fluid border-0 shadow-none logo-80">
                <div class="brand-text">
                    <span class="brand-name">CENDRATAMA</span>
                    <span class="brand-subtitle">Digital Solutions</span>
                </div>
            </div>
        </a>
        
        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Main Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Beranda -->
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url() ?>">
                        <i class="fas fa-home me-1 d-lg-none"></i>Beranda
                    </a>
                </li>
                
                <!-- Layanan & Produk (Konsolidasi) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cube me-1 d-lg-none"></i>Layanan & Produk
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg">
                        <li class="dropdown-header">
                            <i class="fas fa-handshake text-primary me-2"></i>Layanan
                        </li>
                        <li><a class="dropdown-item" href="<?= base_url('layanan/website') ?>">Website Custom</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('layanan/it-support') ?>">IT Support</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('layanan/mesin-kasir') ?>">Mesin Kasir</a></li>
                        
                        <li class="dropdown-header mt-3">
                            <i class="fas fa-box text-primary me-2"></i>Produk
                        </li>
                        <li><a class="dropdown-item" href="<?= base_url('produk/cctv') ?>">CCTV</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('produk/komputer') ?>">Komputer</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('produk/jaringan') ?>">Jaringan</a></li>
                        
                        <li class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-primary fw-bold" href="<?= base_url('layanan/semua') ?>">
                                <i class="fas fa-th-list me-2"></i>Semua Layanan & Produk
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Portfolio -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('portfolio') ?>">
                        <i class="fas fa-briefcase me-1 d-lg-none"></i>Portfolio
                    </a>
                </li>
                
                <!-- Tentang Kami -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-info-circle me-1 d-lg-none"></i>Tentang
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= base_url('tentang') ?>">Tentang Kami</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('klien') ?>">Klien Kami</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('testimoni') ?>">Testimoni</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('karir') ?>">Karir</a></li>
                    </ul>
                </li>
                
                <!-- Kontak -->
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('kontak') ?>">
                        <i class="fas fa-phone me-1 d-lg-none"></i>Kontak
                    </a>
                </li>
                
                <!-- Search Button (Simplified) -->
                <li class="nav-item">
                    <button class="nav-link btn btn-link" type="button" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="fas fa-search"></i>
                    </button>
                </li>
                
                <!-- Konsultasi Button (Simplified) -->
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-primary btn-sm px-3" href="<?= base_url('konsultasi') ?>">
                        <i class="fas fa-comment-dots me-1"></i>Konsultasi
                    </a>
                </li>
                
                <!-- User Menu -->
                <?php if(session()->get('isLoggedIn')): ?>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <span class="d-none d-md-inline"><?= session()->get('user_name') ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= base_url('dashboard') ?>"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('profil') ?>"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('pesanan') ?>"><i class="fas fa-shopping-cart me-2"></i>Pesanan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a href="<?= base_url('login') ?>" class="nav-link">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Search Modal (Tidak berubah) -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Cari Layanan atau Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="searchForm" action="<?= base_url('search') ?>" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" name="q" placeholder="Ketik kata kunci..." autofocus>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="search-suggestions mt-3">
                        <small class="text-muted">Pencarian populer:</small>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <a href="<?= base_url('search?q=website') ?>" class="badge bg-light text-dark">Website</a>
                            <a href="<?= base_url('search?q=cctv') ?>" class="badge bg-light text-dark">CCTV</a>
                            <a href="<?= base_url('search?q=it+support') ?>" class="badge bg-light text-dark">IT Support</a>
                            <a href="<?= base_url('search?q=komputer') ?>" class="badge bg-light text-dark">Komputer</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Floating WhatsApp Button (Simplified) -->
<a href="https://wa.me/6281393484770?text=Halo%20CENDRATAMA,%20saya%20ingin%20konsultasi" 
   class="whatsapp-float" 
   target="_blank"
   title="Chat WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Back to Top (Simplified) -->
<button id="backToTop" class="back-to-top" title="Kembali ke atas">
    <i class="fas fa-arrow-up"></i>
</button>