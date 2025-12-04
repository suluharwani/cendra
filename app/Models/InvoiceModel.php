<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'invoice_number',
        'order_id',
        'amount',
        'status',
        'due_date',
        'paid_at',
        'payment_method',
        'notes',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'invoice_number' => 'required|is_unique[invoices.invoice_number]',
        'order_id' => 'required|integer',
        'amount' => 'required|numeric',
        'due_date' => 'required|valid_date'
    ];
    
    protected $validationMessages = [
        'invoice_number' => [
            'required' => 'Nomor invoice harus diisi',
            'is_unique' => 'Nomor invoice sudah digunakan'
        ],
        'order_id' => [
            'required' => 'Order ID harus diisi',
            'integer' => 'Order ID harus berupa angka'
        ],
        'amount' => [
            'required' => 'Jumlah harus diisi',
            'numeric' => 'Jumlah harus berupa angka'
        ],
        'due_date' => [
            'required' => 'Tanggal jatuh tempo harus diisi',
            'valid_date' => 'Tanggal jatuh tempo tidak valid'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get invoice by order
     */
    public function getInvoiceByOrder($orderId)
    {
        return $this->where('order_id', $orderId)
            ->first();
    }
    
    /**
     * Mark invoice as paid
     */
    public function markAsPaid($invoiceId, $paymentMethod = null)
    {
        return $this->update($invoiceId, [
            'status' => 'paid',
            'paid_at' => date('Y-m-d H:i:s'),
            'payment_method' => $paymentMethod,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get overdue invoices
     */
    public function getOverdueInvoices()
    {
        return $this->where('status', 'unpaid')
            ->where('due_date <', date('Y-m-d'))
            ->orderBy('due_date', 'ASC')
            ->findAll();
    }
}