<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIssuesTable extends Migration
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
                'constraint' => '250',
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '1000',
            ],
            'reporter' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'assignee' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'classification' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'severity' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'status' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'start_date' => [
                'type' => 'date',
            ],
            'end_date' => [
                'type' => 'date',
                'null' => true
            ],
            'completed_date' => [
                'type' => 'date',
                'null' => true
            ],
            'project' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('reporter', 'collaborators', 'id');
        $this->forge->addForeignKey('assignee', 'collaborators', 'id');
        $this->forge->addForeignKey('classification', 'categories', 'id');
        $this->forge->addForeignKey('severity', 'categories', 'id');
        $this->forge->addForeignKey('status', 'categories', 'id');
        $this->forge->addForeignKey('project', 'projects', 'id');
        $this->forge->createTable('issues');
    }

    public function down()
    {
        $this->forge->dropForeignKey('issues', 'issues_reporter_foreign');
        $this->forge->dropForeignKey('issues', 'issues_assignee_foreign');
        $this->forge->dropForeignKey('issues', 'issues_classification_foreign');
        $this->forge->dropForeignKey('issues', 'issues_severity_foreign');
        $this->forge->dropForeignKey('issues', 'issues_status_foreign');
        $this->forge->dropForeignKey('issues', 'issues_project_foreign');
        $this->forge->dropTable('issues');
    }
}
