<?php

namespace App\Models;

use CodeIgniter\Model;

class CollaboratorModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function saveCollaborator($data)
    {
        $this->db
            ->table('collaborators')
            ->insert($data);
        return $this->db->insertID();
    }

    public function getCollaborator($id)
    {
        return $this->db
            ->table('collaborators')
            ->where('id', $id)
            ->get()
            ->getRowArray();
    }

    public function updateCollaborator($data)
    {
        return $this->db
            ->table('collaborators')
            ->where(['id' => $data['id']])
            ->update($data);
    }
}
