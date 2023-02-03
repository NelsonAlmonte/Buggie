<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\ProjectModel;

class Project extends BaseController
{

  public function projects()
  {
    helper('text');
    $projectModel = model(ProjectModel::class);

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

    $data = [
      'name' => $this->request->getPost('name'),
      'slug' => url_title($_POST['name'], '-', true),
      'description' => $this->request->getPost('description'),
      'owner' => $this->request->getPost('owner'),
      'status' => $this->request->getPost('status'),
      'start_date' => $this->request->getPost('start_date'),
      'end_date' => $this->request->getPost('end_date'),
    ];

    if ($projectModel->saveProject($data)) {
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
      return redirect()->to('project/add');
    }
  }

  public function edit($slug, $id)
  {
    $projectModel = model(ProjectModel::class);
    $categoryModel = model(CategoryModel::class);

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
        'icon' => MESSAGE_SUCCESS_ICON
      ]);
      return redirect()->to('project');
    } else {
      session()->setFlashdata([
        'message' => MESSAGE_ERROR, 
        'color' => MESSAGE_ERROR_COLOR, 
        'icon' => MESSAGE_ERROR_ICON
      ]);
      return redirect()->to('project/edit/' . $id);
    }
  }

  public function dashboard($slug)
  {
    helper('text');
    
    $projectModel = model(ProjectModel::class);

    $data['project'] = $projectModel->getProject('', $slug);

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
		if ($this->request->isAJAX()) {
			$projectModel = model(ProjectModel::class);

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

			echo json_encode($response);
		}
	}
}