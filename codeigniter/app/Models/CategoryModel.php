<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getCategories()
    {
        return $this->db
            ->table('categories')
            ->get()
            ->getResultArray();
    }

    public function searchCategories($query)
    {
        return $this->db
            ->table('categories')
            ->like('name', $query)
            ->limit(5)
            ->get()
            ->getResultArray();
    }
}
