<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Data layanan utama (realistis sesuai profil klien)
        $services = [
            [
                'id' => 1,
                'title' => 'CCTV Security System',
                'description' => 'Perencanaan, instalasi, dan perawatan sistem CCTV untuk rumah, toko, kantor, dan area operasional bisnis.',
                'icon' => 'fas fa-video',
                'slug' => 'cctv',
                'features' => ['Kamera HD/4K', 'Akses Remote', 'Notifikasi & Monitoring']
            ],
            [
                'id' => 2,
                'title' => 'Mesin & Aplikasi Kasir',
                'description' => 'Pengadaan perangkat kasir dan implementasi aplikasi kasir untuk operasional penjualan yang lebih rapi dan efisien.',
                'icon' => 'fas fa-cash-register',
                'slug' => 'kasir',
                'features' => ['POS & Printer', 'Laporan Penjualan', 'Pendampingan Penggunaan']
            ],
            [
                'id' => 3,
                'title' => 'Pembuatan Website',
                'description' => 'Pembuatan website company profile dan kebutuhan bisnis dengan tampilan profesional, responsif, dan mudah dikelola.',
                'icon' => 'fas fa-globe',
                'slug' => 'website',
                'features' => ['Desain Responsif', 'SEO Dasar', 'Integrasi Kontak/WhatsApp']
            ],
            [
                'id' => 4,
                'title' => 'Aplikasi Custom & Sistem Bisnis',
                'description' => 'Pengembangan aplikasi sesuai kebutuhan operasional, termasuk sistem internal, HRIS/payroll, dan manajemen aset.',
                'icon' => 'fas fa-code',
                'slug' => 'custom',
                'features' => ['Sesuai Kebutuhan', 'Integrasi Data', 'Dukungan Teknis']
            ],
            [
                'id' => 5,
                'title' => 'Pengadaan Elektronik & IT',
                'description' => 'Pengadaan perangkat elektronik dan pendukung IT untuk kebutuhan kantor dan operasional usaha.',
                'icon' => 'fas fa-laptop',
                'slug' => 'elektronik',
                'features' => ['Produk Bergaransi', 'Pilihan Sesuai Kebutuhan', 'Konsultasi Spesifikasi']
            ],
        ];
        
        // Produk/aplikasi unggulan
        $products = [
            [
                'id' => 1,
                'title' => 'Paket CCTV UMKM & Kantor',
                'description' => 'Paket instalasi CCTV dengan pemilihan kamera, perekam, dan konfigurasi akses monitoring sesuai lokasi.',
                'image' => base_url('assets/images/placeholder-service.svg'),
                'slug' => 'cctv',
                'price' => 'Mulai Rp 2.500.000',
                'old_price' => null,
                'badge' => 'Layanan'
            ],
            [
                'id' => 2,
                'title' => 'Paket Mesin Kasir (POS)',
                'description' => 'Perangkat kasir dan pendampingan setup untuk usaha retail, toko, dan F&B.',
                'image' => base_url('assets/images/placeholder-service.svg'),
                'slug' => 'kasir',
                'price' => 'Mulai Rp 5.000.000',
                'old_price' => null,
                'badge' => 'Populer'
            ],
            [
                'id' => 3,
                'title' => 'Aplikasi Bisnis (Inventory, Payroll, Kasir)',
                'description' => 'Solusi aplikasi untuk pengelolaan inventori & aset, HRIS/payroll, hingga kasir sesuai kebutuhan bisnis.',
                'image' => base_url('assets/images/placeholder-service.svg'),
                'slug' => 'aplikasi',
                'price' => 'Konsultasi Kebutuhan',
                'old_price' => null,
                'badge' => 'Aplikasi'
            ]
        ];
        
        // Portofolio (contoh tipe pekerjaan yang relevan)
        $portfolios = [
            [
                'title' => 'Instalasi CCTV Area Usaha',
                'category' => 'Keamanan',
                'image' => base_url('assets/images/placeholder-portfolio.svg'),
                'url' => base_url('portfolio'),
                'tech' => ['CCTV', 'DVR/NVR', 'Remote Monitoring']
            ],
            [
                'title' => 'Implementasi Sistem Kasir',
                'category' => 'POS',
                'image' => base_url('assets/images/placeholder-portfolio.svg'),
                'url' => base_url('portfolio'),
                'tech' => ['POS', 'Printer Thermal', 'Barcode Scanner']
            ],
            [
                'title' => 'Website & Sistem Informasi Bisnis',
                'category' => 'Website & Aplikasi',
                'image' => base_url('assets/images/placeholder-portfolio.svg'),
                'url' => base_url('portfolio'),
                'tech' => ['Web', 'Database', 'Integrasi']
            ]
        ];
        
        // Artikel/informasi (edukatif, tanpa klaim palsu)
        $blogs = [
            [
                'title' => 'Hal yang Perlu Disiapkan Sebelum Instalasi CCTV',
                'excerpt' => 'Tentukan titik pantau, kebutuhan perekaman, dan akses monitoring agar sistem CCTV sesuai kebutuhan lokasi.',
                'image' => base_url('assets/images/placeholder-blog.svg'),
                'slug' => 'persiapan-instalasi-cctv',
                'category' => 'CCTV',
                'date' => date('Y-m-d'),
                'views' => 0,
                'read_time' => 4
            ],
            [
                'title' => 'Manfaat Aplikasi Kasir untuk UMKM',
                'excerpt' => 'Pencatatan transaksi dan laporan yang rapi membantu pemilik usaha memantau penjualan harian dengan lebih mudah.',
                'image' => base_url('assets/images/placeholder-blog.svg'),
                'slug' => 'manfaat-aplikasi-kasir-umkm',
                'category' => 'Kasir',
                'date' => date('Y-m-d'),
                'views' => 0,
                'read_time' => 4
            ],
            [
                'title' => 'Website Bisnis: Informasi Penting yang Harus Ada',
                'excerpt' => 'Profil usaha, layanan, kontak, dan bukti pekerjaan yang jelas membantu calon pelanggan memahami dan mempercayai bisnis Anda.',
                'image' => base_url('assets/images/placeholder-blog.svg'),
                'slug' => 'informasi-penting-website-bisnis',
                'category' => 'Website',
                'date' => date('Y-m-d'),
                'views' => 0,
                'read_time' => 5
            ]
        ];
        
        $data = [
            'title' => 'Cendratama Teknologi - Solusi Teknologi Terpercaya untuk Bisnis Anda',
            'meta_description' => 'Cendratama Teknologi menyediakan layanan CCTV, mesin & aplikasi kasir, pengadaan elektronik, pembuatan website, dan aplikasi custom untuk mendukung bisnis Anda.',
            'services' => $services,
            'products' => $products,
            'portfolios' => $portfolios,
            'blogs' => $blogs
        ];
        
        return view('header', $data)
             . view('navigation')
             . view('content')
             . view('footer');
    }
    

    
    public function layanan($service = null)
    {
        $data = [
            'title' => 'Layanan CENDRATAMA'
        ];
        
        // You can add logic here to load different content based on $service
        
        return view('header', $data)
             . view('navigation')
             . view('content_layanan') // Create separate view for services
             . view('footer');
    }
    
    public function produk($product = null)
    {
        $data = [
            'title' => 'Produk CENDRATAMA'
        ];
        
        // You can add logic here to load different content based on $product
        
        return view('header', $data)
             . view('navigation')
             . view('content_produk') // Create separate view for products
             . view('footer');
    }
    
    public function tentang()
    {
        $data = [
            'title' => 'Tentang CENDRATAMA'
        ];
        
        return view('header', $data)
             . view('navigation')
             . view('content_tentang') // Create separate view for about
             . view('footer');
    }
    
    public function kontak()
    {
        $data = [
            'title' => 'Kontak CENDRATAMA'
        ];
        
        return view('header', $data)
             . view('navigation')
             . view('content_kontak') // Create separate view for contact
             . view('footer');
    }
}