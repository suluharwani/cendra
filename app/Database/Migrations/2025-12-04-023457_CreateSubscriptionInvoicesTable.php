<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubscriptionInvoicesTable extends Migration
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
            'invoice_number' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => true,
            ],
            'subscription_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'billing_period' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'comment' => 'e.g., Jan 2024, Q1 2024',
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
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
                'constraint' => ['draft', 'sent', 'viewed', 'paid', 'overdue', 'cancelled'],
                'default' => 'draft',
            ],
            'due_date' => [
                'type' => 'DATE',
            ],
            'paid_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
            'payment_reference' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
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
            'reminder_sent' => [
                'type' => 'TINYINT',
                'constraint' => 1,
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
        $this->forge->addKey('invoice_number');
        $this->forge->addKey('subscription_id');
        $this->forge->addKey('client_id');
        $this->forge->addKey('status');
        $this->forge->addKey('due_date');
        $this->forge->addForeignKey('subscription_id', 'subscriptions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('client_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subscription_invoices');
    }

    public function down()
    {
        $this->forge->dropTable('subscription_invoices');
    }
}