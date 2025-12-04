<?php

namespace App\Models;

use CodeIgniter\Model;

class ConsultationModel extends Model
{
    protected $table = 'consultations';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'user_id',
        'subject',
        'message',
        'status',
        'admin_notes',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'user_id' => 'required|integer',
        'subject' => 'required|min_length[3]|max_length[200]',
        'message' => 'required|min_length[10]'
    ];
    
    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID harus diisi',
            'integer' => 'User ID harus berupa angka'
        ],
        'subject' => [
            'required' => 'Subjek harus diisi',
            'min_length' => 'Subjek minimal 3 karakter',
            'max_length' => 'Subjek maksimal 200 karakter'
        ],
        'message' => [
            'required' => 'Pesan harus diisi',
            'min_length' => 'Pesan minimal 10 karakter'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get consultations by user
     */
    public function getConsultationsByUser($userId)
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
    
    /**
     * Get open consultations
     */
    public function getOpenConsultations()
    {
        return $this->where('status', 'open')
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}