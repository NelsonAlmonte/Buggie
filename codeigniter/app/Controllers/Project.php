<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProjectModel;

class Project extends BaseController
{

  public function projects()
  {
    return view('template/header')
      . view('project/projects')
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
    $projectModel = model(ProjectModel::class);

    $data = [
      'name' => $this->request->getPost('name'),
      'owner' => $this->request->getPost('owner'),
      'deadline' => $this->request->getPost('deadline'),
      'status' => $this->request->getPost('status'),
    ];

    if ($projectModel->saveProject($data)) {
      session()->setFlashdata('message', MESSAGE_SUCCESS);
      return redirect()->to('project');
    } else {
      session()->setFlashdata('message', MESSAGE_ERROR);
      return redirect()->to('project/add');
    }
  }
}