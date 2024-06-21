<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CollaboratorModel;
use App\Models\IssueModel;
use App\Models\ProjectModel;
use App\Models\RoleModel;

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

        foreach ($data['collaborators'] as $key => $collaborator) {
            $data['collaborators'][$key]['projects'] = $collaboratorModel->getCollaboratorProjects($collaborator['id']);
        }
        
        return view('template/header')
        . view('collaborator/collaborators', $data)
        . view('template/footer');
    }

    public function view($id)
    {
        helper('text');
        $collaboratorModel = model(CollaboratorModel::class);
        $issueModel = model(IssueModel::class);
        $data = [];
        $collaboratorProjects = [];

        $data['collaborator'] = $collaboratorModel->getCollaborator($id);
        $collaboratorProjects = $collaboratorModel->getCollaboratorProjects($id);
        $data['collaboratorIssues'] = $issueModel->getCollaboratorIssues($id);
        $data['closedCollaboratorIssues'] = array_filter(
            $data['collaboratorIssues'],
            fn ($issue) => $issue['status_name'] == CATEGORY_ISSUE_STATUS_CLOSED_NAME 
        );

        $data['projectsWithoutAccess'] = [];
        $data['collaboratorProjects'] = [];
        
        if (in_array('project', session()->get('auth')['permissions'])) {
            $data['collaboratorProjects'] = $collaboratorProjects;
        } else{
            $loggedCollaboratorProjects = session()->get('projects');
            $loggedCollaboratorProjects = array_map(fn ($project) => $project['id'], $loggedCollaboratorProjects);

            foreach ($collaboratorProjects as $project) {
                if (in_array($project['id'], $loggedCollaboratorProjects)) {
                    $data['collaboratorProjects'][] = $project;
                } else {
                    $data['projectsWithoutAccess'][] = $project;
                }
            }
        }

        return view('template/header')
        . view('collaborator/view', $data)
        . view('template/footer');
    }

    public function add()
    {
        $projectModel = model(ProjectModel::class);
        $roleModel = model(RoleModel::class);
        $data = [];
        $data['collaboratorProjects'] = [];
        
        $data['projects'] = $projectModel->getProjects();
        $data['roles'] = $roleModel->getRoles();

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
            'role' => $this->request->getPost('role'),
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
                'hexColor' => MESSAGE_SUCCESS_HEX_COLOR, 
                'icon' => MESSAGE_SUCCESS_ICON
            ]);
            return redirect()->to('manage/collaborator');
        } else {
            session()->setFlashdata([
                'message' => MESSAGE_ERROR, 
                'color' => MESSAGE_ERROR_COLOR, 
                'hexColor' => MESSAGE_ERROR_HEX_COLOR, 
                'icon' => MESSAGE_ERROR_ICON
            ]);
            return redirect()->to('manage/collaborator/add');
        }
    }

    public function edit($id)
    {
        $collaboratorModel = model(CollaboratorModel::class);
        $projectModel = model(ProjectModel::class);
        $roleModel = model(RoleModel::class);
        $data = [];

        $data['collaborator'] = $collaboratorModel->getCollaborator($id);
        $data['projects'] = $projectModel->getProjects();
        $data['collaboratorProjects'] = $collaboratorModel->getCollaboratorProjects($id);
        $data['roles'] = $roleModel->getRoles();

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
            'role' => $this->request->getPost('role'),
        ];

        $data = array_filter($data);

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
                'hexColor' => MESSAGE_SUCCESS_HEX_COLOR, 
                'icon' => MESSAGE_SUCCESS_ICON
            ]);
            return redirect()->to('collaborator/view/' . $id);
        } else {
            session()->setFlashdata([
                'message' => MESSAGE_ERROR, 
                'color' => MESSAGE_ERROR_COLOR, 
                'hexColor' => MESSAGE_ERROR_HEX_COLOR, 
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
            if ($collaborator['image'] != DEFAULT_PROFILE_IMAGE) {
                $operationsToValidate = 2;
                if(unlink(ROOTPATH . PATH_UPLOAD_PROFILE_IMAGE . $collaborator['image'])) 
                    $successfulOperations ++;
            }
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

    public function searchCollaborators()
	{
        $collaboratorModel = model(CollaboratorModel::class);
        $json = [];
        $response = [];
        $collaborators = [];
        $project = '';

        $json = $this->request->getJSON(true);

        $response['token'] = csrf_hash();
        $response['data'] = [];

        if (!empty($json['query'])) {
            if (isset($json['project'])) $project = $json['project'];
            $collaborators = $collaboratorModel->searchCollaborators($json['query'], $project);
            $response['data'] = array_udiff(
                $collaborators, $json['unwanted'], 
                fn ($needle, $haystack) => $needle['id'] <=> $haystack['id']
            );
        } else {
            $response['data'] = $collaboratorModel->searchCollaborators('');
        }

        return $this->response->setJSON($response);
	}

    public function assignProjects()
    {
        $collaboratorModel = model(CollaboratorModel::class);
        $json = [];
        $response = [];
        $cartesian = [];
        $projectsToAdd = [];
        $operationsToValidate = 0;
        $successfulOperations = 0;

        $json = $this->request->getJSON(true);

        $cartesian = $this->_cartesian([$json['collaborators'], $json['projects']]);
        $projectsToAdd = $this->verifyCollaboratorProjects($cartesian);
        $operationsToValidate = count($projectsToAdd);
        if (!empty($projectsToAdd))
            $successfulOperations += $collaboratorModel->saveCollaboratorProjects($projectsToAdd);

        $response['status'] = EXIT_DATABASE;
        $response['token'] = csrf_hash();
        $response['data'] = $projectsToAdd;

        if ($successfulOperations == $operationsToValidate) $response['status'] = EXIT_SUCCESS;

        return $this->response->setJSON($response);
    }

    public function verifyCollaboratorProjects($data)
    {
		$collaboratorModel = model(CollaboratorModel::class);
        $collaborator = '';
        $project = '';
        $collaboratorProjects = [];
        $projectsToAdd = [];

        foreach ($data as $value) {
            $collaborator = $value[0]['id'];
            $project = $value[1]['id'];
            $collaboratorProjects = $collaboratorModel->getCollaboratorProjects($collaborator);
            $collaboratorProjects = array_map(fn ($project) => $project['id'], $collaboratorProjects);
            
            if (!in_array($project, $collaboratorProjects)) {
                $projectsToAdd[] = [
                    'collaborator' => $collaborator,
                    'project' => $project,
                ];
            }
        }

        return $projectsToAdd;
    }

    public function removeCollaboratorFromProject()
    {
        $collaboratorModel = model(CollaboratorModel::class);
        $json = [];
        $response = [];

        $json = $this->request->getJSON(true);

        $response['token'] = csrf_hash();

        if ($collaboratorModel->removeCollaboratorFromProject($json['collaborator'], $json['project']))
            $response['status'] = EXIT_SUCCESS;
        else
            $response['status'] = EXIT_ERROR;

        return $this->response->setJSON($response);
    }

    public function deleteCollaborator()
    {
        $collaboratorModel = model(CollaboratorModel::class);
        $json = [];
        $response = [];
        $data = ['is_active' => '0'];
        $collaboratorProjects = [];
        $operationsToValidate = 1;
        $successfulOperations = 0;

        $json = $this->request->getJSON(true);

        $response['token'] = csrf_hash();
        $response['status'] = EXIT_ERROR;

        $data['id'] = $json['collaborator'];

        if ($collaboratorModel->updateCollaborator($data)) $successfulOperations ++;

        $collaboratorProjects = $collaboratorModel->getCollaboratorProjects($json['collaborator']);
        $operationsToValidate += count($collaboratorProjects);

        foreach ($collaboratorProjects as $project) {
            if ($collaboratorModel->removeCollaboratorFromProject($json['collaborator'], $project['id']))
                $successfulOperations ++;
        }

        if ($successfulOperations == $operationsToValidate) $response['status'] = EXIT_SUCCESS;

        return $this->response->setJSON($response);
    }

    private function _cartesian($array)
    {
        $result = [[]];

        foreach ($array as $key => $values) {
            $append = [];

            foreach($result as $product) {
                foreach($values as $item) {
                    $product[$key] = $item;
                    $append[] = $product;
                }
            }
            $result = $append;
        }
        return $result;
    }
}