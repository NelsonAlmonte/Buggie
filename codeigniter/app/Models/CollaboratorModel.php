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

    public function saveCollaboratorProjects($data)
    {
        return $this->db
            ->table('collaborators_projects')
            ->insertBatch($data);
    }

    public function deleteCollaboratorProjects($data)
    {
        return $this->db
            ->table('collaborators_projects')
            ->setData($data)
            ->onConstraint('collaborator, project')
            ->deleteBatch();
    }

    public function getCollaborator($value, $field = 'c.id')
    {
        return $this->db
            ->table('collaborators c')
            ->select('c.*, r.id AS roleId, r.name AS roleName')
            ->join('roles r', 'r.id = c.role')
            ->where($field, $value)
            ->get()
            ->getRowArray();
    }

    public function getCollaboratorProjects($collaborator)
    {
        return $this->db
            ->table('projects p')
            ->select('p.*, ca.name AS status, ca.color')
            ->join('collaborators_projects cp', 'cp.project = p.id')
            ->join('collaborators c', 'c.id = cp.collaborator')
            ->join('categories ca', 'ca.id = p.status')
            ->where('c.id', $collaborator)
            ->get()
            ->getResultArray();
    }

    public function updateCollaborator($data)
    {
        return $this->db
            ->table('collaborators')
            ->where(['id' => $data['id']])
            ->update($data);
    }

    public function getCollaborators()
    {
        return $this->db
            ->table('collaborators')
            ->get()
            ->getResultArray();
    }

    public function getCollaboratorsByProject($project)
    {
        return $this->db
            ->table('collaborators c')
            ->select('c.id, c.name, c.last, c.username, c.email, c.image')
            ->join('collaborators_projects cp', 'cp.collaborator = c.id')
            ->join('projects p', 'p.id = cp.project')
            ->where('p.id', $project)
            ->get()
            ->getResultArray();
    }

    public function searchCollaborators($query, $project = '')
    {
        $builder = $this->db
            ->table('collaborators c')
            ->select('c.*');
        if (!empty($project)) {
            $builder->join('collaborators_projects cp', 'cp.collaborator = c.id', 'right')
                ->join('projects p', 'p.id = cp.project')
                ->where('p.id', $project);
        }
            $builder->like('c.name', $query)
            ->orLike('c.last', $query)
            ->orLike('c.username', $query)
            ->limit(5)
            ->groupBy('c.id');
        return $builder->get()
            ->getResultArray();
    }
}
