<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'order_number',
        'user_id',
        'service_id',
        'product_id',
        'quantity',
        'total_amount',
        'notes',
        'status',
        'payment_status',
        'payment_method',
        'payment_proof',
        'cancelled_at',
        'cancellation_reason',
        'completed_at',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'order_number' => 'required|is_unique[orders.order_number]',
        'user_id' => 'required|integer',
        'total_amount' => 'required|numeric'
    ];
    
    protected $validationMessages = [
        'order_number' => [
            'required' => 'Nomor pesanan harus diisi',
            'is_unique' => 'Nomor pesanan sudah digunakan'
        ],
        'user_id' => [
            'required' => 'User ID harus diisi',
            'integer' => 'User ID harus berupa angka'
        ],
        'total_amount' => [
            'required' => 'Total amount harus diisi',
            'numeric' => 'Total amount harus berupa angka'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get order timeline
     */
    public function getTimeline($orderId)
    {
        $order = $this->find($orderId);
        if (!$order) return [];
        
        $timeline = [];
        
        // Order created
        $timeline[] = [
            'event' => 'Pesanan dibuat',
            'date' => $order['created_at'],
            'icon' => 'fas fa-shopping-cart',
            'color' => 'primary'
        ];
        
        // Payment status
        if ($order['payment_status'] == 'paid') {
            $timeline[] = [
                'event' => 'Pembayaran dikonfirmasi',
                'date' => $order['updated_at'],
                'icon' => 'fas fa-check-circle',
                'color' => 'success'
            ];
        }
        
        // Status updates
        if ($order['status'] == 'processing') {
            $timeline[] = [
                'event' => 'Pesanan diproses',
                'date' => $order['updated_at'],
                'icon' => 'fas fa-cogs',
                'color' => 'info'
            ];
        } elseif ($order['status'] == 'completed') {
            $timeline[] = [
                'event' => 'Pesanan selesai',
                'date' => $order['completed_at'],
                'icon' => 'fas fa-check',
                'color' => 'success'
            ];
        } elseif ($order['status'] == 'cancelled') {
            $timeline[] = [
                'event' => 'Pesanan dibatalkan',
                'date' => $order['cancelled_at'],
                'icon' => 'fas fa-times-circle',
                'color' => 'danger'
            ];
        }
        
        return $timeline;
    }
    
    /**
     * Get orders by user
     */
    public function getOrdersByUser($userId, $limit = null)
    {
        $builder = $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->findAll();
    }
    
    /**
     * Get total sales
     */
    public function getTotalSales($startDate = null, $endDate = null)
    {
        $builder = $this->selectSum('total_amount')
            ->where('status', 'completed');
        
        if ($startDate) {
            $builder->where('DATE(created_at) >=', $startDate);
        }
        
        if ($endDate) {
            $builder->where('DATE(created_at) <=', $endDate);
        }
        
        $result = $builder->get()->getRow();
        return $result->total_amount ?? 0;
    }
}