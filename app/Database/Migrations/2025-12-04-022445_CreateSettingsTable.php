<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'key' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => true,
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['text', 'textarea', 'number', 'email', 'url', 'boolean', 'select', 'multiselect', 'json'],
                'default' => 'text',
            ],
            'label' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'options' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON options for select/multiselect',
            ],
            'group' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'general',
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'is_public' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '0=Admin only, 1=Public',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('key');
        $this->forge->addKey('group');
        $this->forge->createTable('settings');
        
        // Insert default settings
        $defaultSettings = [
            // Company Information
            [
                'key' => 'company_name',
                'value' => 'PT Cendrawasih Digikarya Pertama (CENDRATAMA)',
                'type' => 'text',
                'label' => 'Nama Perusahaan',
                'description' => 'Nama lengkap perusahaan',
                'group' => 'company',
                'sort_order' => 1,
                'is_public' => 1
            ],
            [
                'key' => 'company_email',
                'value' => 'info@cendratama.co.id',
                'type' => 'email',
                'label' => 'Email Perusahaan',
                'description' => 'Email utama perusahaan',
                'group' => 'company',
                'sort_order' => 2,
                'is_public' => 1
            ],
            [
                'key' => 'company_phone',
                'value' => '+62 21 1234 5678',
                'type' => 'text',
                'label' => 'Telepon Perusahaan',
                'description' => 'Nomor telepon perusahaan',
                'group' => 'company',
                'sort_order' => 3,
                'is_public' => 1
            ],
            [
                'key' => 'company_address',
                'value' => 'Jl. Teknologi No. 123, Jakarta, Indonesia',
                'type' => 'textarea',
                'label' => 'Alamat Perusahaan',
                'description' => 'Alamat lengkap perusahaan',
                'group' => 'company',
                'sort_order' => 4,
                'is_public' => 1
            ],
            
            // Website Settings
            [
                'key' => 'website_title',
                'value' => 'CENDRATAMA - Cendrawasih Digikarya Pertama',
                'type' => 'text',
                'label' => 'Judul Website',
                'description' => 'Judul yang muncul di browser tab',
                'group' => 'website',
                'sort_order' => 1,
                'is_public' => 1
            ],
            [
                'key' => 'website_description',
                'value' => 'Solusi Digital Terpercaya untuk Bisnis Anda',
                'type' => 'textarea',
                'label' => 'Deskripsi Website',
                'description' => 'Deskripsi meta untuk SEO',
                'group' => 'website',
                'sort_order' => 2,
                'is_public' => 1
            ],
            [
                'key' => 'website_keywords',
                'value' => 'jasa website, it support, cctv, komputer, jaringan, cendratama',
                'type' => 'text',
                'label' => 'Keywords Website',
                'description' => 'Keyword meta untuk SEO',
                'group' => 'website',
                'sort_order' => 3,
                'is_public' => 1
            ],
            
            // Payment Settings
            [
                'key' => 'payment_methods',
                'value' => json_encode(['bank_transfer', 'credit_card', 'qris', 'virtual_account']),
                'type' => 'multiselect',
                'label' => 'Metode Pembayaran',
                'description' => 'Metode pembayaran yang diterima',
                'options' => json_encode([
                    'bank_transfer' => 'Transfer Bank',
                    'credit_card' => 'Kartu Kredit',
                    'qris' => 'QRIS',
                    'virtual_account' => 'Virtual Account',
                    'cash' => 'Tunai',
                    'cod' => 'Cash on Delivery'
                ]),
                'group' => 'payment',
                'sort_order' => 1,
                'is_public' => 1
            ],
            [
                'key' => 'tax_rate',
                'value' => '11',
                'type' => 'number',
                'label' => 'Persentase Pajak',
                'description' => 'Persentase pajak PPN (%)',
                'group' => 'payment',
                'sort_order' => 2,
                'is_public' => 0
            ],
            
            // Email Settings
            [
                'key' => 'smtp_host',
                'value' => 'smtp.gmail.com',
                'type' => 'text',
                'label' => 'SMTP Host',
                'description' => 'Host server email',
                'group' => 'email',
                'sort_order' => 1,
                'is_public' => 0
            ],
            [
                'key' => 'smtp_port',
                'value' => '587',
                'type' => 'number',
                'label' => 'SMTP Port',
                'description' => 'Port server email',
                'group' => 'email',
                'sort_order' => 2,
                'is_public' => 0
            ],
            
            // Project Settings
            [
                'key' => 'default_project_manager',
                'value' => '1',
                'type' => 'select',
                'label' => 'Project Manager Default',
                'description' => 'Project manager default untuk proyek baru',
                'options' => json_encode(['1' => 'Administrator']),
                'group' => 'project',
                'sort_order' => 1,
                'is_public' => 0
            ],
            [
                'key' => 'hourly_rate',
                'value' => '150000',
                'type' => 'number',
                'label' => 'Tarif Per Jam',
                'description' => 'Tarif default per jam untuk time tracking',
                'group' => 'project',
                'sort_order' => 2,
                'is_public' => 0
            ]
        ];
        
        foreach ($defaultSettings as $setting) {
            $this->db->table('settings')->insert($setting);
        }
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}