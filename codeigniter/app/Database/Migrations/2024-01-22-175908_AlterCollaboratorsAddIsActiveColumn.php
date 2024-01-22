<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterCollaboratorsAddIsActiveColumn extends Migration
{
    public function up()
    {
        $field = [
            'is_active' => [
                'type'   => 'BOOLEAN',
                'default' => 1,
            ]
        ];

        $this->forge->addColumn('collaborators', $field);
    }

    public function down()
    {
        $this->forge->dropColumn('collaborators', 'is_active');
    }
}
