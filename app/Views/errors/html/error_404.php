<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - CENDRATAMA</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #f3e5f5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .error-container {
            max-width: 600px;
            text-align: center;
        }
        
        .error-icon {
            font-size: 8rem;
            color: #9b59b6;
            margin-bottom: 2rem;
        }
        
        .error-title {
            font-size: 3rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .error-subtitle {
            font-size: 1.5rem;
            color: #666;
            margin-bottom: 2rem;
        }
        
        .error-message {
            font-size: 1.1rem;
            color: #777;
            margin-bottom: 3rem;
        }
        
        .error-actions .btn {
            margin: 0 10px;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
        }
        
        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 3rem;
        }
        
        .brand-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #2ecc71 0%, #9b59b6 100%);
            color: white;
            font-size: 2rem;
            font-weight: bold;
            border-radius: 12px;
            margin-right: 15px;
        }
        
        .brand-text {
            text-align: left;
        }
        
        .brand-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            line-height: 1.2;
        }
        
        .brand-subtitle {
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="brand-logo">
            <img src="<?= base_url('logo.png') ?>" alt="CENDRATAMA Logo" 
     class="brand-icon me-2 img-fluid border-0 shadow-none logo-80">
            <div class="brand-text">
                <div class="brand-name">CENDRATAMA</div>
                <div class="brand-subtitle">Cendrawasih Digikarya Pertama</div>
            </div>
        </div>
        
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <h1 class="error-title">404</h1>
        <h2 class="error-subtitle">Halaman Tidak Ditemukan</h2>
        
        <p class="error-message">
            Maaf, halaman yang Anda cari tidak ditemukan. Mungkin halaman telah dipindahkan, dihapus, 
            atau Anda salah mengetik URL.
        </p>
        
        <div class="error-actions">
            <a href="<?= base_url() ?>" class="btn btn-primary">
                <i class="fas fa-home me-2"></i>Kembali ke Beranda
            </a>
            <a href="<?= base_url('contact') ?>" class="btn btn-outline-primary">
                <i class="fas fa-headset me-2"></i>Hubungi Kami
            </a>
        </div>
        
        <div class="mt-5">
            <p class="text-muted">
                Atau gunakan menu navigasi untuk menjelajahi website kami
            </p>
            <div class="d-flex justify-content-center flex-wrap gap-2 mt-3">
                <a href="<?= base_url('layanan') ?>" class="btn btn-sm btn-outline-secondary">Layanan</a>
                <a href="<?= base_url('produk') ?>" class="btn btn-sm btn-outline-secondary">Produk</a>
                <a href="<?= base_url('portfolio') ?>" class="btn btn-sm btn-outline-secondary">Portfolio</a>
                <a href="<?= base_url('blog') ?>" class="btn btn-sm btn-outline-secondary">Blog</a>
                <a href="<?= base_url('tentang') ?>" class="btn btn-sm btn-outline-secondary">Tentang Kami</a>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>