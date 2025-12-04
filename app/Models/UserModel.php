<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'full_name',
        'email',
        'phone',
        'password',
        'avatar',
        'role',
        'status',
        'verification_token',
        'verified_at',
        'reset_token',
        'reset_expires',
        'remember_token',
        'last_login',
        'address',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'full_name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'phone' => 'required|min_length[10]|max_length[15]',
        'password' => 'required|min_length[6]'
    ];
    
    protected $validationMessages = [
        'full_name' => [
            'required' => 'Nama lengkap harus diisi',
            'min_length' => 'Nama lengkap minimal 3 karakter',
            'max_length' => 'Nama lengkap maksimal 100 karakter'
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Email tidak valid',
            'is_unique' => 'Email sudah terdaftar'
        ],
        'phone' => [
            'required' => 'Nomor telepon harus diisi',
            'min_length' => 'Nomor telepon minimal 10 karakter',
            'max_length' => 'Nomor telepon maksimal 15 karakter'
        ],
        'password' => [
            'required' => 'Password harus diisi',
            'min_length' => 'Password minimal 6 karakter'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
    
    /**
     * Get active users
     */
    public function getActiveUsers()
    {
        return $this->where('status', 'active')->findAll();
    }
    
    /**
     * Get users by role
     */
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)
            ->where('status', 'active')
            ->findAll();
    }
    
    /**
     * Update last login
     */
    public function updateLastLogin($userId)
    {
        return $this->update($userId, [
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Verify user
     */
    public function verifyUser($token)
    {
        return $this->where('verification_token', $token)
            ->where('status', 'pending')
            ->set([
                'status' => 'active',
                'verified_at' => date('Y-m-d H:i:s'),
                'verification_token' => null
            ])
            ->update();
    }
    
    /**
     * Set reset token
     */
    public function setResetToken($email, $token, $expires)
    {
        return $this->where('email', $email)
            ->set([
                'reset_token' => $token,
                'reset_expires' => $expires
            ])
            ->update();
    }
    
    /**
     * Get user by reset token
     */
    public function getUserByResetToken($token)
    {
        return $this->where('reset_token', $token)
            ->where('reset_expires >', date('Y-m-d H:i:s'))
            ->first();
    }
    
    /**
     * Reset password
     */
    public function resetPassword($token, $password)
    {
        return $this->where('reset_token', $token)
            ->set([
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'reset_token' => null,
                'reset_expires' => null,
                'updated_at' => date('Y-m-d H:i:s')
            ])
            ->update();
    }
}