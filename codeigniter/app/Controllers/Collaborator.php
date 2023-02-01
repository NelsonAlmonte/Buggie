<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CollaboratorModel;
use App\Models\ProjectModel;

class Collaborator extends BaseController
{
    public function collaborators($projectSlug)
    {   
        $projectModel = model(ProjectModel::class);

        $data['project'] = $projectModel->getProject('', $projectSlug);

        return view('template/header')
        . view('collaborator/collaborators', $data)
        . view('template/footer');
    }

    public function add()
    {
        $projectModel = model(ProjectModel::class);

        $data['projects'] = $projectModel->getProjects();

        return view('template/header')
        . view('collaborator/add', $data)
        . view('template/footer');
    }

    public function save()
    {
        $collaboratorModel = model(CollaboratorModel::class);

        $data = [
            'name' => $this->request->getPost('name'),
            'last' => $this->request->getPost('last'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'image' => 'A',
        ];

        $projects = json_decode($_POST['projects'], true);

        $validationTarget = count($projects) + 1;
        $affectedRecords = 0;

        $savedCollaborator = $collaboratorModel->saveCollaborator($data);
        if ($savedCollaborator) $affectedRecords ++;

        foreach ($projects as $project) {
            $collaboratorProject = [
                'collaborator' => $savedCollaborator,
                'project' => $project['id'],
            ];
            if ($collaboratorModel->saveCollaboratorProject($collaboratorProject)) 
                $affectedRecords ++;
        }
        
        if ($affectedRecords == $validationTarget) {
            session()->setFlashdata([
                'message' => MESSAGE_SUCCESS, 
                'color' => MESSAGE_SUCCESS_COLOR, 
                'icon' => MESSAGE_SUCCESS_ICON
            ]);
            return redirect()->to('project');
        } else {
            session()->setFlashdata([
                'message' => MESSAGE_ERROR, 
                'color' => MESSAGE_ERROR_COLOR, 
                'icon' => MESSAGE_ERROR_ICON
            ]);
            return redirect()->to('collaborator/add');
        }
    }

    public function edit($id)
    {
        $collaboratorModel = model(CollaboratorModel::class);
        $projectModel = model(ProjectModel::class);

        $data['collaborator'] = $collaboratorModel->getCollaborator($id);
        $data['projects'] = $projectModel->getProjects();

        return view('template/header')
        . view('collaborator/edit', $data)
        . view('template/footer');
    }

    public function update($id)
    {
        $collaboratorModel = model(CollaboratorModel::class);

        $data = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'last' => $this->request->getPost('last'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'image' => 'A',
        ];

        if ($collaboratorModel->updateCollaborator($data)) {
            session()->setFlashdata([
                'message' => MESSAGE_SUCCESS, 
                'color' => MESSAGE_SUCCESS_COLOR, 
                'icon' => MESSAGE_SUCCESS_ICON
            ]);
            return redirect()->to('project');
        } else {
            session()->setFlashdata([
                'message' => MESSAGE_ERROR, 
                'color' => MESSAGE_ERROR_COLOR, 
                'icon' => MESSAGE_ERROR_ICON
            ]);
            return redirect()->to('collaborator/edit/' . $id);
        }
    }
}