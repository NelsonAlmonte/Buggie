<?php

namespace App\Models;

use CodeIgniter\Model;

class IssueModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function saveIssue($data)
    {
        $this->db
            ->table('issues')
            ->insert($data);
        return $this->db->insertID();
    }
}
