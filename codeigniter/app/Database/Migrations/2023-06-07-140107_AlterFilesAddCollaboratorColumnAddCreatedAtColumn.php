<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterFilesAddCollaboratorColumnAddCreatedAtColumn extends Migration
{
    public function up()
    {
        $fields = [
            'collaborator' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        ];

        $this->forge->addColumn('files', $fields);
        $this->forge->addForeignKey('collaborator', 'collaborators', 'id');
        $this->forge->processIndexes('files');
    }

    public function down()
    {
        $this->forge->dropForeignKey('files', 'files_collaborator_foreign');
        $this->forge->dropColumn('files', ['collaborator', 'created_at']);
    }
}
