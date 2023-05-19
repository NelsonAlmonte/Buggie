<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertAdminUser extends Seeder
{
    public function run()
    {
        $user = [
            'username' => 'admin',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'name' => 'Admin',
            'last' => 'Admin',
            'email' => 'admin@foo.com',
            'image' => 'default.jpg',
            'role' => 1,
        ];

        $this->db->table('collaborators')->insert($user);
    }
}
