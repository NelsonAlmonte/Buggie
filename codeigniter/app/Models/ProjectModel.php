<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function saveProject($data)
    {
        $this->db
            ->table('projects')
            ->insert($data);
        return $this->db->insertID();
    }
}
