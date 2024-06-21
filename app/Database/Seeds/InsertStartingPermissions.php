<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertStartingPermissions extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'name' => 'project',
                'description' => 'Access to projects.',
            ],
            [
                'name' => 'collaborator',
                'description' => 'Access to collaborator.',
            ],
            [
                'name' => 'role',
                'description' => 'Access to roles.',
            ],
            [
                'name' => 'issue',
                'description' => 'Access to issues.',
            ],
        ];

        foreach ($permissions as $permission) {
            $this->db->table('permissions')->insert($permission);
        }
    }
}
