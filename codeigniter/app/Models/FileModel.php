<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function saveFile($data)
    {
        return $this->db
            ->table('files')
            ->insert($data);
    }

    public function getIssueFiles($issue)
    {
        return $this->db
            ->table('files')
            ->where('issue', $issue)
            ->get()
            ->getResultArray();
    }
}
