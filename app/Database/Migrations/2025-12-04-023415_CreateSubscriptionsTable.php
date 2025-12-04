<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubscriptionsTable extends Migration
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
            'subscription_code' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => true,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'service_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'plan_type' => [
                'type' => 'ENUM',
                'constraint' => ['monthly', 'quarterly', 'semiannual', 'annual', 'custom'],
                'default' => 'monthly',
            ],
            'billing_cycle' => [
                'type' => 'ENUM',
                'constraint' => ['monthly', 'quarterly', 'semiannual', 'annual'],
                'default' => 'monthly',
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
            ],
            'discount_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'next_billing_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'pending', 'suspended', 'cancelled', 'expired', 'completed'],
                'default' => 'pending',
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'payment_gateway' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'auto_renew' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cancellation_reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cancelled_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'suspended_at' => [
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
        $this->forge->addKey('subscription_code');
        $this->forge->addKey('client_id');
        $this->forge->addKey('service_id');
        $this->forge->addKey('status');
        $this->forge->addKey('next_billing_date');
        $this->forge->addForeignKey('client_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subscriptions');
    }

    public function down()
    {
        $this->forge->dropTable('subscriptions');
    }
}