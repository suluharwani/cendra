<?php

namespace App\Models;

use CodeIgniter\Model;

class WishlistModel extends Model
{
    protected $table = 'wishlist';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'user_id',
        'product_id',
        'created_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'user_id' => 'required|integer',
        'product_id' => 'required|integer'
    ];
    
    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID harus diisi',
            'integer' => 'User ID harus berupa angka'
        ],
        'product_id' => [
            'required' => 'Product ID harus diisi',
            'integer' => 'Product ID harus berupa angka'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Check if product is in user's wishlist
     */
    public function isInWishlist($userId, $productId)
    {
        return $this->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }
    
    /**
     * Get user's wishlist count
     */
    public function getWishlistCount($userId)
    {
        return $this->where('user_id', $userId)
            ->countAllResults();
    }
}