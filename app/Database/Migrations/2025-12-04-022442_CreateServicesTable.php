<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateServicesTable extends Migration
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
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
                'unique' => true,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'short_description' => [
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => true,
            ],
            'price_type' => [
                'type' => 'ENUM',
                'constraint' => ['one_time', 'monthly', 'yearly', 'custom'],
                'default' => 'one_time',
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
            ],
            'discount_price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'subcategory' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => 'default-service.jpg',
            ],
            'gallery' => [
                'type' => 'TEXT',
                'null' => true, // JSON array of images
            ],
            'features' => [
                'type' => 'TEXT',
                'null' => true, // JSON array of features
            ],
            'requirements' => [
                'type' => 'TEXT',
                'null' => true, // JSON array of requirements
            ],
            'delivery_time' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Estimated days for delivery',
                'null' => true,
            ],
            'revisions' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'is_featured' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'is_popular' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'draft'],
                'default' => 'active',
            ],
            'meta_title' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ],
            'meta_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'meta_keywords' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'views' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'order_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
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
        $this->forge->addKey('slug');
        $this->forge->addKey('category');
        $this->forge->addKey('status');
        $this->forge->addKey('is_featured');
        $this->forge->addKey('is_popular');
        $this->forge->createTable('services');
        
        // Insert sample services
        $sampleServices = [
            [
                'title' => 'Website Custom Development',
                'slug' => 'website-custom-development',
                'description' => 'Pembuatan website custom dengan teknologi terkini sesuai kebutuhan bisnis Anda. Dilengkapi dengan CMS, responsive design, dan SEO optimization.',
                'short_description' => 'Website custom dengan teknologi terkini untuk bisnis Anda',
                'price_type' => 'one_time',
                'price' => 5000000,
                'category' => 'website',
                'subcategory' => 'custom',
                'features' => json_encode([
                    'Responsive Design',
                    'CMS Integration',
                    'SEO Optimization',
                    'Mobile Friendly',
                    '1 Tahun Hosting',
                    '6 Bulan Maintenance'
                ]),
                'requirements' => json_encode([
                    'Logo perusahaan',
                    'Konten teks',
                    'Foto/gambar',
                    'Warna preferensi',
                    'Referensi website'
                ]),
                'delivery_time' => 30,
                'revisions' => 3,
                'is_featured' => 1,
                'is_popular' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'IT Support Bulanan',
                'slug' => 'it-support-bulanan',
                'description' => 'Layanan IT Support bulanan untuk maintenance sistem, troubleshooting, dan dukungan teknis 24/7.',
                'short_description' => 'IT Support bulanan dengan dukungan 24/7',
                'price_type' => 'monthly',
                'price' => 1500000,
                'category' => 'it-support',
                'subcategory' => 'subscription',
                'features' => json_encode([
                    'Remote Support 24/7',
                    'On-site Support (jika diperlukan)',
                    'Monthly System Check',
                    'Security Updates',
                    'Backup Monitoring',
                    'Performance Optimization'
                ]),
                'delivery_time' => null,
                'revisions' => null,
                'is_featured' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Instalasi CCTV Paket Lengkap',
                'slug' => 'instalasi-cctv-paket-lengkap',
                'description' => 'Paket lengkap instalasi CCTV termasuk 4 kamera, DVR, hard disk, kabel, dan konfigurasi remote monitoring.',
                'short_description' => 'Paket lengkap instalasi CCTV 4 kamera',
                'price_type' => 'one_time',
                'price' => 3500000,
                'category' => 'cctv',
                'subcategory' => 'installation',
                'features' => json_encode([
                    '4 Kamera CCTV HD',
                    'DVR 4 Channel',
                    'Hard Disk 1TB',
                    'Kabel & Aksesoris',
                    'Instalasi Profesional',
                    'Remote Monitoring Setup',
                    'Training Penggunaan'
                ]),
                'delivery_time' => 7,
                'revisions' => 1,
                'is_popular' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        foreach ($sampleServices as $service) {
            $this->db->table('services')->insert($service);
        }
    }

    public function down()
    {
        $this->forge->dropTable('services');
    }
}