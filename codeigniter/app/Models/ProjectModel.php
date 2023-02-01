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
            ->select('projects.*, categories.name AS status, categories.color')
            ->join('categories', 'categories.id = projects.status')
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

    public function getProject($id = '', $slug = '')
    {
        return $this->db
            ->table('projects')
            ->select('projects.*, categories.name AS status, categories.color')
            ->join('categories', 'categories.id = projects.status')
            ->where('projects.id', $id)
            ->orWhere('projects.slug', $slug)
            ->get()
            ->getRowArray();
    }

    public function updateProject($data)
    {
        return $this->db
            ->table('projects')
            ->where(['id' => $data['id']])
            ->update($data);
    }

    public function searchProjects($query)
    {
        return $this->db
            ->table('projects')
            ->like('name', $query)
            ->get()
            ->getResultArray();
    }
}
