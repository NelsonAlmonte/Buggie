<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProjectModel;

class Project extends BaseController
{

  public function projects()
  {
    helper('text');
    $projectModel = model(ProjectModel::class);

    $data['projects'] = $projectModel->getProjects();

    return view('template/header')
      . view('project/projects', $data)
      . view('template/footer');
  }

  public function add()
  {
    return view('template/header')
      . view('project/add')
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

    $data['project'] = $projectModel->getProject($id);

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

    return view('template/header')
      . view('project/dashboard', $data)
      . view('template/footer');
  }
}