<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SetupAuthRecords extends Seeder
{
    public function run()
    {
        $this->call('InsertStartingRoles');
        $this->call('InsertStartingPermissions');
        $this->call('InsertStartingPermissionRole');
        $this->call('InsertAdminUser');
    }
}
