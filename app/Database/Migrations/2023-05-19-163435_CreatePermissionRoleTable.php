<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePermissionRoleTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'role' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'permission' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('role', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('permission', 'permissions', 'id');
        $this->forge->createTable('permission_role');
    }

    public function down()
    {
        $this->forge->dropForeignKey('permission_role', 'permission_role_role_foreign');
        $this->forge->dropForeignKey('permission_role', 'permission_role_permission_foreign');
        $this->forge->dropTable('permission_role');
    }
}
