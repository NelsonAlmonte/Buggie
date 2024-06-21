<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getPermissions()
    {
        return $this->db
            ->table('permissions')
            ->get()
            ->getResultArray();
    }

    public function getPermission($value, $field = 'id')
    {
        return $this->db
            ->table('permissions')
            ->getWhere([$field => $value, 'deleted_at =' => null])
            ->getRowArray();
    }

    public function getRolePermissions($role)
    {
        return $this->db
            ->table('permissions')
            ->join('permission_role', 'permission_role.permission = permissions.id')
            ->where('permission_role.role', $role)
            ->get()
            ->getResultArray();
    }

    public function savePermission($data)
    {
        return $this->db
            ->table('permissions')
            ->insert($data);
    }    

    public function updatePermission($data)
    {
        return $this->db
            ->table('permissions')
            ->where('id', $data['id'])
            ->update($data);
    }

    public function saveRolePermissions($data)
    {
        return $this->db
            ->table('permission_role')
            ->insertBatch($data);
    }

    public function deleteRolePermissions($data)
    {
        return $this->db
            ->table('permission_role')
            ->setData($data)
            ->onConstraint('role, permission')
            ->deleteBatch();
    }
}
