<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getRoles()
    {
        return $this->db
            ->table('roles')
            ->get()
            ->getResultArray();
    }

    public function getRole($id)
    {
        return $this->db
            ->table('roles')
            ->getWhere(['id' => $id])
            ->getRowArray();
    }

    public function saveRole($data)
    {
        $this->db
            ->table('roles')
            ->insert($data);
        return $this->db->insertID();
    }

    public function updateRole($data)
    {
        return $this->db
            ->table('roles')
            ->where('id', $data['id'])
            ->update($data);
    }

    public function deleteRole($id)
    {
        return $this->db
            ->table('roles')
            ->where('id', $id)
            ->delete();
    }
}
