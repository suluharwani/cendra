<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'title',
        'slug',
        'description',
        'price',
        'category',
        'image',
        'features',
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
        'slug' => 'required|alpha_dash|is_unique[services.slug]',
        'price' => 'required|numeric|greater_than[0]',
        'category' => 'required'
    ];
    
    protected $validationMessages = [
        'title' => [
            'required' => 'Judul layanan harus diisi',
            'min_length' => 'Judul layanan minimal 3 karakter',
            'max_length' => 'Judul layanan maksimal 200 karakter'
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
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get active services
     */
    public function getActiveServices()
    {
        return $this->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
    
    /**
     * Get services by category
     */
    public function getServicesByCategory($category)
    {
        return $this->where('category', $category)
            ->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
    
    /**
     * Get service by slug
     */
    public function getServiceBySlug($slug)
    {
        return $this->where('slug', $slug)
            ->where('status', 'active')
            ->first();
    }
    
    /**
     * Get featured services
     */
    public function getFeaturedServices($limit = 6)
    {
        return $this->where('status', 'active')
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }
}