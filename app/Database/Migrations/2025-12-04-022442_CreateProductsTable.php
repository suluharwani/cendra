<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
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
                'unique' => true, // Ini akan membuat UNIQUE INDEX
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'short_description' => [
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => true,
            ],
            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => true, // Ini akan membuat UNIQUE INDEX
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
            'cost_price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'brand' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'model' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'image' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => 'default-product.jpg',
            ],
            'gallery' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'specifications' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'stock' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'stock_alert' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 5,
            ],
            'weight' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Weight in kg',
            ],
            'dimensions' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => 'Format: LxWxH in cm',
            ],
            'warranty_months' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 12,
            ],
            'is_featured' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'is_bestseller' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'out_of_stock', 'discontinued'],
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
            'sold_count' => [
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
        // $this->forge->addKey('slug'); // DIHAPUS: sudah ada unique constraint
        // $this->forge->addKey('sku');  // DIHAPUS: sudah ada unique constraint
        $this->forge->addKey('category');
        $this->forge->addKey('status');
        $this->forge->addKey('is_featured');
        $this->forge->addKey('is_bestseller');
        $this->forge->createTable('products');
        
        // Insert sample products
        $sampleProducts = [
            [
                'title' => 'CCTV Camera HD 2MP',
                'slug' => 'cctv-camera-hd-2mp',
                'description' => 'Kamera CCTV HD 2MP dengan night vision 30m, waterproof IP67, wide angle 90 derajat.',
                'short_description' => 'CCTV HD 2MP dengan night vision',
                'sku' => 'CCTV-2MP-001',
                'price' => 450000,
                'discount_price' => 399000,
                'cost_price' => 250000,
                'category' => 'cctv',
                'brand' => 'Hikvision',
                'model' => 'DS-2CD2342WD-I',
                'specifications' => json_encode([
                    'Resolution' => '2MP (1920x1080)',
                    'Lens' => '2.8mm fixed lens',
                    'Night Vision' => '30m IR distance',
                    'Weatherproof' => 'IP67',
                    'Power Supply' => 'DC 12V',
                    'Interface' => 'BNC, Power DC'
                ]),
                'stock' => 50,
                'stock_alert' => 10,
                'weight' => 0.8,
                'dimensions' => '12x8x8',
                'warranty_months' => 24,
                'is_featured' => 1,
                'is_bestseller' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Laptop Business Elite',
                'slug' => 'laptop-business-elite',
                'description' => 'Laptop bisnis dengan processor Intel Core i7, 16GB RAM, 512GB SSD, Windows 11 Pro.',
                'short_description' => 'Laptop bisnis high performance',
                'sku' => 'LAP-ELITE-001',
                'price' => 12500000,
                'cost_price' => 9500000,
                'category' => 'computer',
                'brand' => 'Dell',
                'model' => 'Latitude 5420',
                'specifications' => json_encode([
                    'Processor' => 'Intel Core i7-1165G7',
                    'RAM' => '16GB DDR4',
                    'Storage' => '512GB NVMe SSD',
                    'Display' => '14" FHD IPS',
                    'OS' => 'Windows 11 Pro',
                    'Battery' => '4-cell 68Wh'
                ]),
                'stock' => 15,
                'stock_alert' => 3,
                'weight' => 1.5,
                'dimensions' => '32x22x2',
                'warranty_months' => 36,
                'is_featured' => 1,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Managed Switch 24 Port',
                'slug' => 'managed-switch-24-port',
                'description' => 'Managed switch 24 port gigabit dengan PoE+, layer 2 management, dan VLAN support.',
                'short_description' => 'Switch managed 24 port gigabit PoE+',
                'sku' => 'NET-SW24-001',
                'price' => 3200000,
                'category' => 'network',
                'brand' => 'TP-Link',
                'model' => 'TL-SG3428',
                'specifications' => json_encode([
                    'Ports' => '24x 10/100/1000 Mbps',
                    'PoE Ports' => '24 ports PoE+',
                    'PoE Budget' => '370W',
                    'Management' => 'Layer 2+',
                    'VLAN' => '802.1Q VLAN',
                    'QoS' => 'Yes'
                ]),
                'stock' => 8,
                'stock_alert' => 2,
                'weight' => 3.2,
                'dimensions' => '44x30x5',
                'warranty_months' => 36,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        foreach ($sampleProducts as $product) {
            $this->db->table('products')->insert($product);
        }
    }

    public function down()
    {
        $this->forge->dropTable('products');
    }
}