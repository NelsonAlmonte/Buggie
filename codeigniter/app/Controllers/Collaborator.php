<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CollaboratorModel;
use App\Models\ProjectModel;

class Collaborator extends BaseController
{
    public function collaborators($slug = '')
    {   
        $projectModel = model(ProjectModel::class);
        $collaboratorModel = model(CollaboratorModel::class);

        $data['project'] = $projectModel->getProject('', $slug);
        $data['slug'] = $slug;
        
        $data['collaborators'] = $collaboratorModel->getCollaborators($data['project']['id']);

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

        $selectedProjects = json_decode($_POST['projects'], true);

        $validationTarget = 2;
        $affectedRecords = 0;

        $savedCollaborator = $collaboratorModel->saveCollaborator($data);
        
        if ($savedCollaborator) $affectedRecords ++;
        if ($this->manageCollaboratorProjects($savedCollaborator, $selectedProjects)) $affectedRecords ++;

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
        $data['collaboratorProjects'] = $collaboratorModel->getCollaboratorProjects($id);

        return view('template/header')
        . view('collaborator/edit', $data)
        . view('template/footer');
    }

    public function update($id)
    {
        $collaboratorModel = model(CollaboratorModel::class);

        $validationTarget = 2;
        $affectedRecords = 0;

        $data = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'last' => $this->request->getPost('last'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'image' => 'A',
        ];

        $selectedProjects = json_decode($_POST['projects'], true);

        if ($collaboratorModel->updateCollaborator($data)) $affectedRecords ++;
        if ($this->manageCollaboratorProjects($id, $selectedProjects)) $affectedRecords ++;

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
            return redirect()->to('collaborator/edit/' . $id);
        }
    }

    public function manageCollaboratorProjects($collaborator, $selectedProjects)
    {
        $collaboratorModel = model(CollaboratorModel::class);
        $collaboratorProjects = $collaboratorModel->getCollaboratorProjects($collaborator);

        $validationTarget = 0;
        $affectedRecords = 0;

        $projectsToAdd = array_udiff(
            $selectedProjects, $collaboratorProjects,
            fn ($needle, $haystack) => $needle['id'] <=> $haystack['id']
        );
        $projectsToAdd = array_map(
            fn ($project) => [
                'collaborator' => $collaborator,
                'project' => $project['id'],
            ], $projectsToAdd
        );
        if (!empty($projectsToAdd))
            $affectedRecords += $collaboratorModel->saveCollaboratorProjects($projectsToAdd);
        
        $projectsToRemove = array_udiff(
            $collaboratorProjects, $selectedProjects,
            fn ($needle, $haystack) => $needle['id'] <=> $haystack['id']
        );
        $projectsToRemove = array_map(
            fn ($project) => [
                'collaborator' => $collaborator,
                'project' => $project['id'],
            ], $projectsToRemove
        );
        if (!empty($projectsToRemove))
            $affectedRecords += $collaboratorModel->deleteCollaboratorProjects($projectsToRemove);

        $validationTarget = count($projectsToAdd) + count($projectsToRemove);

        if ($affectedRecords == $validationTarget) {
            return true;
        } else {
            return false;
        }
    }
}