<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'project_status', 
                    'issue_status', 
                    'classification', 
                    'severity'
                ],
                'default'    => 'issue_status',
            ],
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => '6',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('categories');
    }

    public function down()
    {
        $this->forge->dropTable('categories');
    }
}
