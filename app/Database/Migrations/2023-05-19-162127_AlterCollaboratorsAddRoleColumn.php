<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterCollaboratorsAddRoleColumn extends Migration
{
    public function up()
    {
        $field = [
            'role' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
        ];

        $this->forge->addColumn('collaborators', $field);
        $this->forge->addForeignKey('role', 'roles', 'id');
        $this->forge->processIndexes('collaborators');
    }

    public function down()
    {
        $this->forge->dropForeignKey('collaborators', 'collaborators_role_foreign');
        $this->forge->dropColumn('collaborators', 'role');
    }
}
