<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CollaboratorModel;

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
        $collaboratorModel = Model(CollaboratorModel::class);
        $collaborator = [];
        $projects = [];
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

        $sessionData = [
            'id' => $collaborator['id'],
            'username' => $collaborator['username'],
            'name' => $collaborator['name'],
            'last' => $collaborator['last'],
            'projects' => $projects,
            'isLoggedIn' => TRUE,
        ];

        session()->set($sessionData);
        return redirect()->to('project');            
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('auth/login');
    }
}
