<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertStartingPermissionRole extends Seeder
{
    public function run()
    {
        $permissionsRoles = [
            [
                'role' => 1,
                'permission' => 1,
            ],
            [
                'role' => 1,
                'permission' => 2,
            ],
            [
                'role' => 1,
                'permission' => 3,
            ],
        ];

        foreach ($permissionsRoles as $permissionRole) {
            $this->db->table('permission_role')->insert($permissionRole);
        }
    }
}
