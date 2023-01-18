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

    public function getProjects()
    {
        return $this->db
            ->table('projects')
            ->get()
            ->getResultArray();
    }

    public function saveProject($data)
    {
        $this->db
            ->table('projects')
            ->insert($data);
        return $this->db->insertID();
    }

    public function getProject($id)
    {
        return $this->db
            ->table('projects')
            ->getWhere(['id' => $id])
            ->getRowArray();
    }

    public function updateProject($data)
    {
        return $this->db
            ->table('projects')
            ->where(['id' => $data['id']])
            ->update($data);
    }
}
