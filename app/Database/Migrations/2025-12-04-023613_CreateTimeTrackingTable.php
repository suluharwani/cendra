<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTimeTrackingTable extends Migration
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
            'project_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'task_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'start_time' => [
                'type' => 'DATETIME',
            ],
            'end_time' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'duration_minutes' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'comment' => 'Calculated duration in minutes',
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'is_billable' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'hourly_rate' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'total_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'paused', 'completed', 'approved', 'rejected'],
                'default' => 'active',
            ],
            'approved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'approved_at' => [
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
        $this->forge->addKey('project_id');
        $this->forge->addKey('task_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('start_time');
        $this->forge->addKey('status');
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('task_id', 'project_tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('time_tracking');
    }

    public function down()
    {
        $this->forge->dropTable('time_tracking');
    }
}