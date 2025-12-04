<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'user_id',
        'activity',
        'description',
        'ip_address',
        'user_agent',
        'created_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    /**
     * Get user activities
     */
    public function getUserActivities($userId, $limit = 20)
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->find();
    }
    
    /**
     * Get recent activities
     */
    public function getRecentActivities($limit = 50)
    {
        return $this->select('activity_logs.*, users.full_name, users.email')
            ->join('users', 'users.id = activity_logs.user_id')
            ->orderBy('activity_logs.created_at', 'DESC')
            ->limit($limit)
            ->find();
    }
}