    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <div class="brand-container">
                    <span class="brand-icon">C</span>
                    <div class="brand-text">
                        <span class="brand-name">CENDRATAMA</span>
                        <span class="brand-subtitle">Cendrawasih Digikarya Pertama</span>
                    </div>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url() ?>">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                            Layanan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= base_url('layanan/website') ?>">Website Custom</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('layanan/it-support') ?>">IT Support</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('layanan/mesin-kasir') ?>">Pasang Mesin Kasir</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button" data-bs-toggle="dropdown">
                            Produk
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= base_url('produk/cctv') ?>">Pengadaan CCTV</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('produk/komputer') ?>">Pengadaan Komputer</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('produk/jaringan') ?>">Pengadaan Jaringan</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('tentang') ?>">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('kontak') ?>">Kontak</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-cta" href="<?= base_url('kontak') ?>">Konsultasi Gratis</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>