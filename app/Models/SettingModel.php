<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'key',
        'value',
        'type',
        'label',
        'description',
        'options',
        'created_at',
        'updated_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'key' => 'required|alpha_dash|is_unique[settings.key]',
        'value' => 'required'
    ];
    
    protected $validationMessages = [
        'key' => [
            'required' => 'Key harus diisi',
            'alpha_dash' => 'Key hanya boleh berisi huruf, angka, dash, dan underscore',
            'is_unique' => 'Key sudah digunakan'
        ],
        'value' => [
            'required' => 'Value harus diisi'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Get setting value by key
     */
    public function getValue($key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        return $setting ? $setting['value'] : $default;
    }
    
    /**
     * Set setting value
     */
    public function setValue($key, $value)
    {
        $setting = $this->where('key', $key)->first();
        
        if ($setting) {
            return $this->update($setting['id'], ['value' => $value]);
        } else {
            return $this->insert([
                'key' => $key,
                'value' => $value,
                'type' => 'text',
                'label' => ucfirst(str_replace('_', ' ', $key)),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
    
    /**
     * Get all settings as key-value array
     */
    public function getAllSettings()
    {
        $settings = $this->findAll();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }
        
        return $result;
    }
}