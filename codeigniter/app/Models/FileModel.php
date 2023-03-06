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
}
