<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\CollaboratorModel;
use App\Models\FileModel;
use App\Models\IssueModel;
use App\Models\ProjectModel;

class Project extends BaseController
{

  public function projects()
  {
    helper('text');
    $projectModel = model(ProjectModel::class);
    $data = [];
    $color = '';
    $backgroundColor = '';
    $border = '';
    
    $data['projects'] = session()->get('projects');
    if (in_array('project', session()->get('auth')['permissions']))
      $data['projects'] = $projectModel->getProjects();

    foreach ($data['projects'] as $key => $project) {
      $color = 'color: #' . $project['color'] . ';';
      $backgroundColor = 'background-color: #' . $project['color'] . '1a;';
      $border = 'border: 1px solid #' . $project['color'];
      $data['projects'][$key]['statusStyle'] = $color . $backgroundColor . $border;
    }

    return view('template/header')
      . view('project/projects', $data)
      . view('template/footer');
  }

  public function add()
  {
    $categoryModel = model(CategoryModel::class);
    $data = [];
    $categories = [];

    $categories = $categoryModel->getCategories();
    $data['projectStatus'] = array_values(
      array_filter(
        $categories, 
        fn ($category) => $category['type'] == 'project_status'
      )
    );

    return view('template/header')
      . view('project/add', $data)
      . view('template/footer');
  }

  public function save()
  {
    helper('url');
    $projectModel = model(ProjectModel::class);
    $createdProjectId = 0;
    $data = [];

    $data = [
      'name' => $this->request->getPost('name'),
      'slug' => url_title($_POST['name'], '-', true),
      'description' => $this->request->getPost('description'),
      'owner' => $this->request->getPost('owner'),
      'status' => $this->request->getPost('status'),
      'start_date' => $this->request->getPost('start_date'),
      'end_date' => $this->request->getPost('end_date'),
    ];

    $createdProjectId = $projectModel->saveProject($data);

    if ($this->_assignCreatedProjectToCurrentCollaborator($createdProjectId)) {
      session()->setFlashdata([
        'message' => MESSAGE_SUCCESS, 
        'color' => MESSAGE_SUCCESS_COLOR, 
        'hexColor' => MESSAGE_SUCCESS_HEX_COLOR, 
        'icon' => MESSAGE_SUCCESS_ICON
      ]);
      return redirect()->to('project');
    } else {
      session()->setFlashdata([
        'message' => MESSAGE_ERROR, 
        'color' => MESSAGE_ERROR_COLOR, 
        'hexColor' => MESSAGE_ERROR_HEX_COLOR, 
        'icon' => MESSAGE_ERROR_ICON
      ]);
      return redirect()->to('manage/project/add');
    }
  }

  private function _assignCreatedProjectToCurrentCollaborator($createdProjectId)
  {
    $projectModel = model(ProjectModel::class);
    $collaboratorModel = model(CollaboratorModel::class);
    $currentCollaborator = 0;
    $createdProject = [];
    $collaboratorToAdd = [];
    $projects = [];

    $createdProject = $projectModel->getProject($createdProjectId);
    $currentCollaborator = session()->get('id');
    $collaboratorToAdd = [
      'collaborator' => $currentCollaborator,
      'project' => $createdProjectId,
    ];
    $projects = session()->get('projects');
    array_push($projects, $createdProject);
    session()->set('projects', $projects);

    return $collaboratorModel->saveCollaboratorProjects($collaboratorToAdd);
  }

  public function edit($slug, $id)
  {
    $projectModel = model(ProjectModel::class);
    $categoryModel = model(CategoryModel::class);
    $data = [];
    $categories = [];

    $data['project'] = $projectModel->getProject($id);

    $categories = $categoryModel->getCategories();
    $data['projectStatus'] = array_values(
      array_filter(
        $categories, 
        fn ($category) => $category['type'] == 'project_status'
      )
    );

    return view('template/header')
      . view('project/edit', $data)
      . view('template/footer');
  }

  public function update($slug, $id)
  {
    helper('url');
    $projectModel = model(ProjectModel::class);
    $data = [];

    $data = [
      'id' => $id,
      'name' => $this->request->getPost('name'),
      'slug' => url_title($_POST['name'], '-', true),
      'description' => $this->request->getPost('description'),
      'owner' => $this->request->getPost('owner'),
      'status' => $this->request->getPost('status'),
      'start_date' => $this->request->getPost('start_date'),
      'end_date' => $this->request->getPost('end_date'),
    ];

    if ($projectModel->updateProject($data)) {
      session()->setFlashdata([
        'message' => MESSAGE_SUCCESS, 
        'color' => MESSAGE_SUCCESS_COLOR, 
        'hexColor' => MESSAGE_SUCCESS_HEX_COLOR, 
        'icon' => MESSAGE_SUCCESS_ICON
      ]);
      return redirect()->to('project');
    } else {
      session()->setFlashdata([
        'message' => MESSAGE_ERROR, 
        'color' => MESSAGE_ERROR_COLOR, 
        'hexColor' => MESSAGE_ERROR_HEX_COLOR, 
        'icon' => MESSAGE_ERROR_ICON
      ]);
      return redirect()->to('manage/project/edit/' . $id);
    }
  }

  public function dashboard($slug)
  {
    helper('text');
    
    $projectModel = model(ProjectModel::class);
    $collaboratorModel = model(CollaboratorModel::class);
    $issueModel = model(IssueModel::class);
    $fileModel = model(FileModel::class);
    $data = [];
    $color = '';
    $backgroundColor = '';
    $border = '';

    $data['project'] = $projectModel->getProject('', $slug);
    $data['collaborators'] = $collaboratorModel->getCollaboratorsByProject($data['project']['id']);
    $data['issues'] = $issueModel->getIssues($data['project']['id']);
    $data['openIssues'] = array_filter(
      $data['issues'],
      fn ($issue) => $issue['status_name'] != CATEGORY_ISSUE_STATUS_CLOSED_NAME
    );
    $data['closedIssues'] = array_filter(
      $data['issues'],
      fn ($issue) => $issue['status_name'] == CATEGORY_ISSUE_STATUS_CLOSED_NAME
    );
    $data['files'] = $fileModel->getProjectFiles($data['project']['id']);

    $color = 'color: #' . $data['project']['color'] . ';';
    $backgroundColor = 'background-color: #' . $data['project']['color'] . '1a;';
    $border = 'border: 1px solid #' . $data['project']['color'];
    $data['project']['statusStyle'] = $color . $backgroundColor . $border;

    return view('template/header')
      . view('project/dashboard', $data)
      . view('template/footer');
  }

	public function searchProjects()
	{
    $projectModel = model(ProjectModel::class);
    $json = [];
    $response = [];
    $projects = [];

    $json = $this->request->getJSON(true);

    $response['token'] = csrf_hash();

    $response['data'] = [];
    if (!empty($json['query'])) {
      $projects = $projectModel->searchProjects($json['query']);
      $response['data'] = array_udiff(
        $projects, $json['unwanted'], 
        fn ($needle, $haystack) => $needle['id'] <=> $haystack['id']
      );
    }

    return $this->response->setJSON($response);
	}
}