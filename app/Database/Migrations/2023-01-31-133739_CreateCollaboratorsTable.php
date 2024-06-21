<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCollaboratorsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
            'last' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('collaborators');
    }

    public function down()
    {
        $this->forge->dropTable('collaborators');
    }
}
