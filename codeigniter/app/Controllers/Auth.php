<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CollaboratorModel;
use App\Models\PermissionModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) 
            return redirect()->to('project');

        return view('auth/login');
    }

    public function authenticate()
    {
        $collaboratorModel = model(CollaboratorModel::class);
        $permissionModel = model(PermissionModel::class);
        $collaborator = [];
        $projects = [];
        $permissions = [];
        $sessionData = [];
        $collaboratorPassword = '';
        $username = $_POST['username'];
        $password = $_POST['password'];
        $isPasswordAuthenticated = false;
        
        $collaborator = $collaboratorModel->getCollaborator($username, 'username');

        if (!$collaborator) {
            session()->setFlashdata('message', 'Este usuario no existe');
            return redirect()->to('auth/login');
        }

        $collaboratorPassword = $collaborator['password'];
        $isPasswordAuthenticated = password_verify($password, $collaboratorPassword);

        if (!$isPasswordAuthenticated) {
            session()->setFlashdata('message', 'Contraseña incorrecta');
            return redirect()->to('auth/login');
        }

        $projects = $collaboratorModel->getCollaboratorProjects($collaborator['id']);
        $permissions = $permissionModel->getRolePermissions($collaborator['role']);
        $permissions = array_map(fn($permission) => $permission['name'], $permissions);

        $sessionData = [
            'id' => $collaborator['id'],
            'username' => $collaborator['username'],
            'name' => $collaborator['name'],
            'last' => $collaborator['last'],
            'projects' => $projects,
            'auth' => [
                'role' => [
                    'id' => $collaborator['roleId'],
                    'name' => $collaborator['roleName'],
                ],
                'permissions' => $permissions,
            ],
            'isLoggedIn' => TRUE,
        ];

        session()->set($sessionData);
        return redirect()->to('home');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('auth/login');
    }
}
