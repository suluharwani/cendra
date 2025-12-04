<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityLogsTable extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'activity' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => '45',
            ],
            'user_agent' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('user_id');
        $this->forge->addKey('activity');
        $this->forge->addKey('created_at');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('activity_logs');
    }

    public function down()
    {
        $this->forge->dropTable('activity_logs');
    }
}