<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'CENDRATAMA - Solusi Digital Terpercaya'
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