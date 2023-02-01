<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCollaboratorsProjectsTable extends Migration
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
            'collaborator' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'project' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('collaborator', 'collaborators', 'id');
        $this->forge->addForeignKey('project', 'projects', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('collaborators_projects');
    }

    public function down()
    {
        $this->forge->dropForeignKey('collaborators_projects', 'collaborators_projects_project_foreign');
        $this->forge->dropForeignKey('collaborators_projects', 'collaborators_projects_collaborator_foreign');
        $this->forge->dropTable('collaborators_projects');
    }
}
