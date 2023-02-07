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
        $data = [];

        $data['project'] = $projectModel->getProject('', $slug);
        $data['slug'] = $slug;

        if (isset($data['project'])) {
            $data['collaborators'] = $collaboratorModel->getCollaboratorsByProject($data['project']['id']);
        } else {
            $data['collaborators'] = $collaboratorModel->getCollaborators();
        }
        
        return view('template/header')
        . view('collaborator/collaborators', $data)
        . view('template/footer');
    }

    public function add()
    {
        $projectModel = model(ProjectModel::class);
        $data = [];
        
        $data['projects'] = $projectModel->getProjects();
        $data['collaboratorProjects'] = [];

        return view('template/header')
        . view('collaborator/add', $data)
        . view('template/footer');
    }

    public function save()
    {
        $collaboratorModel = model(CollaboratorModel::class);
        $data = [];
        $selectedProjects = [];
        $savedCollaborator = false;
        $file = $this->request->getFile('image');
        $operationsToValidate = 3;
        $successfulOperations = 0;

        $data = [
            'name' => $this->request->getPost('name'),
            'last' => $this->request->getPost('last'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'image' => $file->getName(),
        ];

        $selectedProjects = json_decode($_POST['projects'], true);

        $savedCollaborator = $collaboratorModel->saveCollaborator($data);
        
        if ($savedCollaborator) $successfulOperations ++;
        if ($this->manageCollaboratorProjects($savedCollaborator, $selectedProjects)) $successfulOperations ++;
        if ($this->manageFile($file, '')) $successfulOperations ++;

        if ($successfulOperations == $operationsToValidate) {
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
        $data = [];

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
        $data = [];
        $selectedProjects = [];
        $collaborator = [];
        $file = $this->request->getFile('image');
        $operationsToValidate = 2;
        $successfulOperations = 0;

        $data = [
            'id' => $id,
            'name' => $this->request->getPost('name'),
            'last' => $this->request->getPost('last'),
            'email' => $this->request->getPost('email'),
            'username' => $this->request->getPost('username'),
        ];

        if (!empty($_POST['password'])) 
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $selectedProjects = json_decode($_POST['projects'], true);

        if (!empty($file->getName())) {
            $operationsToValidate = 3;
            $collaborator = $collaboratorModel->getCollaborator($id);
            if ($this->manageFile($file, $collaborator)) {
                $data['image'] = $file->getName();
                $successfulOperations ++;
            }
        }
        if ($collaboratorModel->updateCollaborator($data)) $successfulOperations ++;
        if ($this->manageCollaboratorProjects($id, $selectedProjects)) $successfulOperations ++;

        if ($successfulOperations == $operationsToValidate) {
            session()->setFlashdata([
                'message' => MESSAGE_SUCCESS, 
                'color' => MESSAGE_SUCCESS_COLOR, 
                'icon' => MESSAGE_SUCCESS_ICON
            ]);
            return redirect()->to('collaborator');
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
        $projectsToAdd = [];
        $projectsToRemove = [];
        $operationsToValidate = 0;
        $successfulOperations = 0;

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
            $successfulOperations += $collaboratorModel->saveCollaboratorProjects($projectsToAdd);
        
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
            $successfulOperations += $collaboratorModel->deleteCollaboratorProjects($projectsToRemove);

        $operationsToValidate = count($projectsToAdd) + count($projectsToRemove);

        if ($successfulOperations == $operationsToValidate) {
            return true;
        } else {
            return false;
        }
    }

    public function manageFile($file, $collaborator)
    {
        $operationsToValidate = 1;
        $successfulOperations = 0;

        if (!empty($collaborator)) {
            $operationsToValidate = 2;
            if(unlink(ROOTPATH . PATH_UPLOAD_PROFILE_IMAGE . $collaborator['image'])) 
                $successfulOperations ++;
        }

        if ($file->isValid() && !$file->hasMoved()) {
            if ($file->move(ROOTPATH . PATH_UPLOAD_PROFILE_IMAGE, $file->getName()))
                $successfulOperations ++;
        }

        if ($successfulOperations == $operationsToValidate) {
            return true;
        } else {
            return false;
        }
    }
}