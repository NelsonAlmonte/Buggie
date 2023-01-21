<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'              => 'INT',
                'constraint'        => 11,
                'unsigned'          => true,
                'auto_increment'    => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
            'owner' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50'
            ],
            'start_date' => [
                'type'       => 'date',
            ],
            'end_date' => [
                'type'       => 'date',
                'null'       => true
            ],
            'completed_date' => [
                'type'       => 'date',
                'null'       => true
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('projects');
    }

    public function down()
    {
        $this->forge->dropTable('projects');
    }
}
