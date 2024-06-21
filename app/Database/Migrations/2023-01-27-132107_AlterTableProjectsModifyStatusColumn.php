<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableProjectsModifyStatusColumn extends Migration
{
    public function up()
    {
        $field = [
            'status' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
            ],
        ];

        $this->forge->modifyColumn('projects', $field);
        $this->forge->addForeignKey('status', 'categories', 'id');
        $this->forge->processIndexes('projects');
    }

    public function down()
    {
        $this->forge->dropForeignKey('projects', 'projects_status_foreign');

        $field = [
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
        ];

        $this->forge->modifyColumn('projects', $field);
    }
}
