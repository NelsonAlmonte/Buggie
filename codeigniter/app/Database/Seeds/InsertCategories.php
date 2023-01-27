<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertCategories extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'open',
                'type' => 'status',
                'color' => '0d6efd',
            ],
            [
                'name' => 'in progress',
                'type' => 'status',
                'color' => 'ffc107',
            ],
            [
                'name' => 'to be tested',
                'type' => 'status',
                'color' => '0dcaf0',
            ],
            [
                'name' => 'closed',
                'type' => 'status',
                'color' => '198754',
            ],
            [
                'name' => 'security',
                'type' => 'classification',
                'color' => '20c997',
            ],
            [
                'name' => 'crash',
                'type' => 'classification',
                'color' => 'fd7e14',
            ],
            [
                'name' => 'data loss',
                'type' => 'classification',
                'color' => 'dc3545',
            ],
            [
                'name' => 'performance',
                'type' => 'classification',
                'color' => 'ffc107',
            ],
            [
                'name' => 'ui/usability',
                'type' => 'classification',
                'color' => '0dcaf0',
            ],
            [
                'name' => 'other bug',
                'type' => 'classification',
                'color' => 'ffffff',
            ],
            [
                'name' => 'critical',
                'type' => 'severity',
                'color' => 'dc3545',
            ],
            [
                'name' => 'major',
                'type' => 'severity',
                'color' => 'fd7e14',
            ],
            [
                'name' => 'minor',
                'type' => 'severity',
                'color' => '0dcaf0',
            ],
        ];

        foreach ($categories as $category) {
            $this->db->table('categories')->insert($category);
        }
    }
}
