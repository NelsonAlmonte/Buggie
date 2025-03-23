<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CollaboratorModel;
use App\Models\PermissionModel;
use App\Models\RoleModel;

class Role extends BaseController
{
    public function roles()
    {
        $roleModel = model(RoleModel::class);

        $data['roles'] = $roleModel->getRoles();

        return view('template/header')
            . view('role/roles', $data)
            . view('template/footer');
    }

    public function add()
    {
        $permissionModel = model(PermissionModel::class);

        $data['permissions'] = $permissionModel->getPermissions();

        return view('template/header')
            . view('role/add', $data)
            . view('template/footer');
    }

    public function save()
    {
        $roleModel = model(RoleModel::class);
        $role = 0;
        $permissions = [];

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        $role = $roleModel->saveRole($data);
        $permissions = array_map(fn ($permission) => ['id' => $permission], $_POST['permissions']);

        if ($this->_manageRolePermissions($role, $permissions)) {
            session()->setFlashdata([
                'message' => MESSAGE_SUCCESS, 
                'color' => MESSAGE_SUCCESS_COLOR, 
                'hexColor' => MESSAGE_SUCCESS_HEX_COLOR, 
                'icon' => MESSAGE_SUCCESS_ICON
            ]);
            return redirect()->to('manage/role');
        } else {
            session()->setFlashdata([
                'message' => MESSAGE_ERROR, 
                'color' => MESSAGE_ERROR_COLOR, 
                'hexColor' => MESSAGE_ERROR_HEX_COLOR, 
                'icon' => MESSAGE_ERROR_ICON
            ]);
            return redirect()->to('manage/role/add');
        }
    }

    public function edit($id)
    {
        $roleModel = model(RoleModel::class);
        $permissionModel = model(PermissionModel::class);

        $data['role'] = $roleModel->getRole($id);
        $data['permissions'] = $permissionModel->getPermissions();
        $data['rolePermissions'] = $permissionModel->getRolePermissions($id);
        $data['rolePermissions'] = array_column($data['rolePermissions'], 'permission');

        return view('template/header')
            . view('role/edit', $data)
            . view('template/footer');
    }

    public function update($id)
    {
        $roleModel = model(RoleModel::class);
        $permissions = [];

        $data = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        $permissions = array_map(fn ($permission) => ['id' => $permission], $_POST['permissions']);

        if ($roleModel->updateRole($data) && $this->_manageRolePermissions($id, $permissions)) {
            session()->setFlashdata([
                'message' => MESSAGE_SUCCESS, 
                'color' => MESSAGE_SUCCESS_COLOR, 
                'hexColor' => MESSAGE_SUCCESS_HEX_COLOR, 
                'icon' => MESSAGE_SUCCESS_ICON
            ]);
            return redirect()->to('manage/role');
        } else {
            session()->setFlashdata([
                'message' => MESSAGE_ERROR, 
                'color' => MESSAGE_ERROR_COLOR, 
                'hexColor' => MESSAGE_ERROR_HEX_COLOR, 
                'icon' => MESSAGE_ERROR_ICON
            ]);
            return redirect()->to('manage/role/edit/' . $id);
        }
    }

    private function _manageRolePermissions($role, $selectedPermissions)
    {
        $permissionModel = model(PermissionModel::class);
        $rolePermissions = $permissionModel->getRolePermissions($role);
        $rolesToAdd = [];
        $rolesToRemove = [];
        $operationsToValidate = 0;
        $succesfulOperations = 0;
    
        $rolePermissions = array_map(
            fn ($perm) => ['permission' => $perm['permission'] ?? null], 
            $rolePermissions ?? []
        );
    
        $selectedPermissions = array_map(
            fn ($perm) => ['id' => $perm['id'] ?? null], 
            $selectedPermissions ?? []
        );
    
        $selectedPermissions = array_filter($selectedPermissions, fn($perm) => isset($perm['id']));
        $rolePermissions = array_filter($rolePermissions, fn($perm) => isset($perm['permission']));
    
        $rolesToAdd = empty($rolePermissions) 
            ? $selectedPermissions 
            : array_udiff(
                $selectedPermissions, $rolePermissions,
                fn ($needle, $haystack) => ($needle['id'] ?? null) <=> ($haystack['permission'] ?? null)
        );
    
        $rolesToAdd = array_map(
            fn ($permission) => [
                'role' => $role,
                'permission' => $permission['id'] ?? null
            ], $rolesToAdd
        );
    
        if (!empty($rolesToAdd)) 
            $succesfulOperations += $permissionModel->saveRolePermissions($rolesToAdd);
    
        $rolesToRemove = empty($rolePermissions) 
            ? []  
            : array_udiff(
                $rolePermissions, $selectedPermissions,
                fn ($needle, $haystack) => ($needle['permission'] ?? null) <=> ($haystack['id'] ?? null)
        );
    
        $rolesToRemove = array_map(
            fn ($permission) => [
                'role' => $role,
                'permission' => $permission['permission'] ?? null
            ], $rolesToRemove
        );
    
        if (!empty($rolesToRemove)) 
            $succesfulOperations += $permissionModel->deleteRolePermissions($rolesToRemove);
    
        $operationsToValidate = count($rolesToAdd) + count($rolesToRemove);
    
        return ($succesfulOperations == $operationsToValidate);
    }

    public function deleteRole()
    {
        $roleModel = model(RoleModel::class);
        $permissionModel = model(PermissionModel::class);
        $collaboratorModel = model(CollaboratorModel::class);
        $json = [];
        $response = [];
        $role = [];
        $rolesToRemove = [];
        $operationsToValidate = 2;
        $succesfulOperations = 0;
        
        $json = $this->request->getJSON(true);
        
        $response['token'] = csrf_hash();
        $response['status'] = EXIT_ERROR;

        $role = $json['role'];
        $rolePermissions = $permissionModel->getRolePermissions($role['id']);

        if ($collaboratorModel->updateToDefaultRole(['role' => $role['id']])) $succesfulOperations ++;

        $rolesToRemove = array_map(
            fn ($permission) => [
                'role' => $role['id'],
                'permission' => $permission['permission'] ?? null
            ], $rolePermissions
        );
          
        if (!empty($rolesToRemove)) 
            $succesfulOperations += $permissionModel->deleteRolePermissions($rolesToRemove);

        if ($roleModel->deleteRole($role['id'])) 
            $succesfulOperations ++;

        $operationsToValidate += count($rolesToRemove);

        if ($succesfulOperations == $operationsToValidate) $response['status'] = EXIT_SUCCESS;

        return $this->response->setJSON($response);
    }
}
