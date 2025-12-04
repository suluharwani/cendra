<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectTasksTable extends Migration
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
            'task_code' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '200',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'assignee_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'priority' => [
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'urgent'],
                'default' => 'medium',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['todo', 'in_progress', 'review', 'completed', 'blocked'],
                'default' => 'todo',
            ],
            'estimated_hours' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'actual_hours' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => true,
            ],
            'start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'due_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'completed_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'dependencies' => [
                'type' => 'TEXT',
                'null' => true, // JSON array of task IDs
            ],
            'attachments' => [
                'type' => 'TEXT',
                'null' => true, // JSON array of file paths
            ],
            'checklist' => [
                'type' => 'TEXT',
                'null' => true, // JSON array of checklist items
            ],
            'tags' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true, // Comma separated tags
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
        $this->forge->addKey('task_code');
        $this->forge->addKey('assignee_id');
        $this->forge->addKey('status');
        $this->forge->addKey('priority');
        $this->forge->addKey('due_date');
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('assignee_id', 'users', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('project_tasks');
        
        // Create project_comments table
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
            'comment' => [
                'type' => 'TEXT',
            ],
            'attachments' => [
                'type' => 'TEXT',
                'null' => true, // JSON array of file paths
            ],
            'is_internal' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '0=Visible to client, 1=Internal only',
            ],
            'parent_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'For reply comments',
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
        $this->forge->addKey('parent_id');
        $this->forge->addForeignKey('project_id', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('task_id', 'project_tasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('project_comments');
    }

    public function down()
    {
        $this->forge->dropTable('project_comments');
        $this->forge->dropTable('project_tasks');
    }
}