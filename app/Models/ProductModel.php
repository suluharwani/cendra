<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'title',
        'slug',
        'description',
        'price',
        'old_price',
        'category',
        'image',
        'stock',
        'status',
        'meta_title',
        'meta_description',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[200]',
        'slug' => 'required|alpha_dash|is_unique[products.slug]',
        'price' => 'required|numeric|greater_than[0]',
        'category' => 'required',
        'stock' => 'required|integer'
    ];
    
    protected $validationMessages = [
        'title' => [
            'required' => 'Judul produk harus diisi',
            'min_length' => 'Judul produk minimal 3 karakter',
            'max_length' => 'Judul produk maksimal 200 karakter'
        ],
        'slug' => [
            'required' => 'Slug harus diisi',
            'alpha_dash' => 'Slug hanya boleh berisi huruf, angka, dash, dan underscore',
            'is_unique' => 'Slug sudah digunakan'
        ],
        'price' => [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka',
            'greater_than' => 'Harga harus lebih dari 0'
        ],
        'category' => [
            'required' => 'Kategori harus dipilih'
        ],
        'stock' => [
            'required' => 'Stok harus diisi',
            'integer' => 'Stok harus berupa angka bulat'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get active products
     */
    public function getActiveProducts()
    {
        return $this->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
    
    /**
     * Get products by category
     */
    public function getProductsByCategory($category)
    {
        return $this->where('category', $category)
            ->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
    
    /**
     * Get product by slug
     */
    public function getProductBySlug($slug)
    {
        return $this->where('slug', $slug)
            ->where('status', 'active')
            ->first();
    }
    
    /**
     * Get featured products
     */
    public function getFeaturedProducts($limit = 6)
    {
        return $this->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
    
    /**
     * Get products on sale
     */
    public function getProductsOnSale($limit = 6)
    {
        return $this->where('status', 'active')
            ->where('old_price >', 0)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}