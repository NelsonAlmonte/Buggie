<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFilesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '250',
            ],
            'issue' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('issue', 'issues', 'id');
        $this->forge->createTable('files');
    }

    public function down()
    {
        $this->forge->dropForeignKey('files', 'files_issue_foreign');
        $this->forge->dropTable('files');
    }
}
