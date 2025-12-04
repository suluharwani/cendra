<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuotationsTable extends Migration
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
            'quotation_number' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => true,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'project_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
            ],
            'valid_until' => [
                'type' => 'DATE',
            ],
            'subtotal' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
            ],
            'tax_rate' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => '0.00',
            ],
            'tax_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'sent', 'viewed', 'accepted', 'rejected', 'expired'],
                'default' => 'draft',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'terms_conditions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'accepted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'rejected_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'sent_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'viewed_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addKey('quotation_number');
        $this->forge->addKey('client_id');
        $this->forge->addKey('project_id');
        $this->forge->addKey('status');
        $this->forge->addKey('valid_until');
        $this->forge->addForeignKey('client_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('quotations');
        
        // Create quotation_items table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'quotation_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'service_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'product_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '500',
            ],
            'quantity' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => '1.00',
            ],
            'unit_price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
            ],
            'total_price' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('quotation_id');
        $this->forge->addKey('service_id');
        $this->forge->addKey('product_id');
        $this->forge->addForeignKey('quotation_id', 'quotations', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('quotation_items');
    }

    public function down()
    {
        $this->forge->dropTable('quotation_items');
        $this->forge->dropTable('quotations');
    }
}