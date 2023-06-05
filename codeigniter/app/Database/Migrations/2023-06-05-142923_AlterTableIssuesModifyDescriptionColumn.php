<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableIssuesModifyDescriptionColumn extends Migration
{
    public function up()
    {
        $field = [
            'description' => [
                'type' => 'TEXT',
            ],
        ];

        $this->forge->modifyColumn('issues', $field);
    }

    public function down()
    {
        $field = [
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => '1000',
            ],
        ];

        $this->forge->modifyColumn('issues', $field);
    }
}
