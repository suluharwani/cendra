<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Data services
        $services = [
            [
                'id' => 1,
                'title' => 'Website Custom',
                'description' => 'Pembuatan website sesuai kebutuhan bisnis Anda dengan teknologi terkini dan desain responsif.',
                'icon' => 'fas fa-code',
                'slug' => 'website',
                'features' => ['Responsive Design', 'SEO Friendly', 'CMS Integration']
            ],
            [
                'id' => 2,
                'title' => 'IT Support',
                'description' => 'Layanan dukungan teknis IT untuk menjaga sistem teknologi informasi Anda berjalan optimal.',
                'icon' => 'fas fa-headset',
                'slug' => 'it-support',
                'features' => ['24/7 Support', 'Remote Assistance', 'Regular Maintenance']
            ],
            [
                'id' => 3,
                'title' => 'Pasang Mesin Kasir',
                'description' => 'Instalasi dan setup mesin kasir dengan sistem yang terintegrasi untuk usaha retail dan hospitality.',
                'icon' => 'fas fa-cash-register',
                'slug' => 'mesin-kasir',
                'features' => ['POS System', 'Inventory Management', 'Sales Reporting']
            ]
        ];
        
        // Data products
        $products = [
            [
                'id' => 1,
                'title' => 'Pengadaan CCTV',
                'description' => 'Pemasangan sistem keamanan CCTV untuk kantor, toko, pabrik, dan properti lainnya.',
                'image' => 'https://images.unsplash.com/photo-1590959651373-a3db0f38a961?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'slug' => 'cctv',
                'price' => 'Rp 3.000.000',
                'old_price' => 'Rp 3.500.000',
                'badge' => 'Promo'
            ],
            [
                'id' => 2,
                'title' => 'Pengadaan Komputer',
                'description' => 'Supply komputer, laptop, dan perangkat pendukung untuk kebutuhan kantor dan bisnis.',
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'slug' => 'komputer',
                'price' => 'Rp 5.000.000',
                'old_price' => null,
                'badge' => 'Best Seller'
            ],
            [
                'id' => 3,
                'title' => 'Pengadaan Jaringan',
                'description' => 'Instalasi dan konfigurasi jaringan internet, intranet, dan sistem komunikasi data.',
                'image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'slug' => 'jaringan',
                'price' => 'Rp 2.500.000',
                'old_price' => 'Rp 3.000.000',
                'badge' => 'Sale'
            ]
        ];
        
        // Data portfolio
        $portfolios = [
            [
                'title' => 'Website E-commerce',
                'category' => 'E-commerce',
                'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'url' => 'https://example.com',
                'tech' => ['Laravel', 'Vue.js', 'MySQL']
            ],
            [
                'title' => 'Company Profile',
                'category' => 'Website',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'url' => 'https://example.com',
                'tech' => ['WordPress', 'PHP', 'JavaScript']
            ],
            [
                'title' => 'Mobile App',
                'category' => 'Mobile Application',
                'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'url' => 'https://example.com',
                'tech' => ['React Native', 'Node.js', 'MongoDB']
            ]
        ];
        
        // Data blog
        $blogs = [
            [
                'title' => 'Tips Memilih Layanan IT Support untuk Bisnis',
                'excerpt' => 'Panduan lengkap dalam memilih layanan IT support yang tepat untuk kebutuhan bisnis Anda.',
                'image' => 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'slug' => 'tips-memilih-it-support',
                'category' => 'Tips & Trik',
                'date' => '2023-06-15',
                'views' => 1250,
                'read_time' => 5
            ],
            [
                'title' => 'Keuntungan Menggunakan CCTV untuk Keamanan Bisnis',
                'excerpt' => 'Bagaimana sistem CCTV dapat meningkatkan keamanan dan efisiensi operasional bisnis Anda.',
                'image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'slug' => 'keuntungan-cctv-bisnis',
                'category' => 'Keamanan',
                'date' => '2023-06-10',
                'views' => 980,
                'read_time' => 4
            ],
            [
                'title' => 'Website Responsif: Pentingnya untuk Bisnis Modern',
                'excerpt' => 'Mengapa website responsif menjadi kebutuhan wajib bagi bisnis di era digital saat ini.',
                'image' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'slug' => 'website-responsif-penting',
                'category' => 'Web Development',
                'date' => '2023-06-05',
                'views' => 1500,
                'read_time' => 6
            ]
        ];
        
        $data = [
            'title' => 'CENDRATAMA - Solusi Digital Terpercaya',
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