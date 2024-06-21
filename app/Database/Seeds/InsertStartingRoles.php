<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertStartingRoles extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'administrator',
                'description' => 'Full system access',
            ],
            [
                'name' => 'default',
                'description' => 'Default system user',
            ],
        ];

        foreach ($roles as $role) {
            $this->db->table('roles')->insert($role);
        }
    }
}
