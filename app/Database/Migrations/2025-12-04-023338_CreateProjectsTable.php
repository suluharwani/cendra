<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectsTable extends Migration
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
            'project_code' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'unique' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
            ],
            'description' => [
                'type' => 'TEXT',
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
                'null' => true,
            ],
            'project_type' => [
                'type' => 'ENUM',
                'constraint' => ['one_time', 'subscription', 'maintenance', 'consultation'],
                'default' => 'one_time',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['quotation', 'negotiation', 'confirmed', 'in_progress', 'testing', 'completed', 'delivered', 'on_hold', 'cancelled'],
                'default' => 'quotation',
            ],
            'priority' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'urgent'],
                'default' => 'medium',
            ],
            'budget' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => '0.00',
            ],
            'estimated_hours' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'actual_hours' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'deadline' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'completed_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'project_manager_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'team_members' => [
                'type' => 'TEXT',
                'null' => true, // JSON array of user IDs
            ],
            'requirements' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'deliverables' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'milestones' => [
                'type' => 'TEXT',
                'null' => true, // JSON array of milestones
            ],
            'documents' => [
                'type' => 'TEXT',
                'null' => true, // JSON array of document paths
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'client_feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'rating' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => true,
                'comment' => '1-5 stars',
            ],
            'is_public' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Show in portfolio',
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
        $this->forge->addKey('project_code');
        $this->forge->addKey('client_id');
        $this->forge->addKey('service_id');
        $this->forge->addKey('status');
        $this->forge->addKey('priority');
        $this->forge->addKey('project_manager_id');
        $this->forge->addKey('is_public');
        $this->forge->addForeignKey('client_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('project_manager_id', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->createTable('projects');
    }

    public function down()
    {
        $this->forge->dropTable('projects');
    }
}