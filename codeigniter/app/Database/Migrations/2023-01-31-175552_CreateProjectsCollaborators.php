<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectsCollaborators extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'project' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'collaborator' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('project', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('collaborator', 'collaborators', 'id');
        $this->forge->createTable('projects_collaborators');
    }

    public function down()
    {
        $this->forge->dropForeignKey('projects_collaborators', 'projects_collaborators_project_foreign');
        $this->forge->dropForeignKey('projects_collaborators', 'projects_collaborators_collaborator_foreign');
        $this->forge->dropTable('projects_collaborators');
    }
}
