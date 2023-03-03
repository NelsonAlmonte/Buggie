<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\IssueModel;
use App\Models\ProjectModel;

class Issue extends BaseController
{
    public function issues($slug)
    {
        $projectModel = model(ProjectModel::class);
        $issueModel = model(IssueModel::class);
        $data = [];

        $data['project'] = $projectModel->getProject('', $slug);
        $data['slug'] = $slug;

        $data['issues'] = $issueModel->getIssues($data['project']['id']);

        return view('template/header')
        . view('issue/issues', $data)
        . view('template/footer');
    }

    public function add($slug)
    {
        $projectModel = model(ProjectModel::class);
        $categoryModel = model(CategoryModel::class);
        $data = [];
        $categories = [];
        $categoryTypes = ['classification', 'severity', 'issue_status'];

        $data['project'] = $projectModel->getProject('', $slug);

        $categories = $categoryModel->getCategories();

        foreach ($categoryTypes as $type) {
            $data['status'][$type] = array_values(
                array_filter(
                    $categories, 
                    fn ($category) => $category['type'] == $type
                )
            );
        }

        return view('template/header')
        . view('issue/add', $data)
        . view('template/footer');
    }

    public function save($slug)
    {
        $issueModel = model(IssueModel::class);
        $data = [];

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'reporter' => $this->request->getPost('reporter'),
            'assignee' => !empty($_POST['assignee']) ? $_POST['assignee'] : null,
            'classification' => $this->request->getPost('classification'),
            'severity' => $this->request->getPost('severity'),
            'status' => $this->request->getPost('status'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'project' => $this->request->getPost('project'),
        ];

        $data['issue'] = $issueModel->saveIssue($data);

        if ($data['issue']) {
            session()->setFlashdata([
                'message' => MESSAGE_SUCCESS, 
                'color' => MESSAGE_SUCCESS_COLOR, 
                'icon' => MESSAGE_SUCCESS_ICON
            ]);
            return redirect()->to('issue/' . $slug);
        } else {
            session()->setFlashdata([
                'message' => MESSAGE_ERROR, 
                'color' => MESSAGE_ERROR_COLOR, 
                'icon' => MESSAGE_ERROR_ICON
            ]);
            return redirect()->to('issue/' . $slug . '/add');
        }
    }

    public function edit($slug, $id)
    {
        $issueModel = model(IssueModel::class);
        $projectModel = model(ProjectModel::class);
        $categoryModel = model(CategoryModel::class);
        $data = [];
        $categories = [];
        $categoryTypes = ['classification', 'severity', 'issue_status'];

        $data['issue'] = $issueModel->getIssue($id);
        $data['project'] = $projectModel->getProject('', $slug);

        $categories = $categoryModel->getCategories();

        foreach ($categoryTypes as $type) {
            $data['status'][$type] = array_values(
                array_filter(
                    $categories, 
                    fn ($category) => $category['type'] == $type
                )
            );
        }

        return view('template/header')
        . view('issue/edit', $data)
        . view('template/footer');
    }

    public function update($slug, $id)
    {
        $issueModel = model(IssueModel::class);
        $data = [];

        $data = [
            'id' => $id,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'reporter' => $this->request->getPost('reporter'),
            'assignee' => !empty($_POST['assignee']) ? $_POST['assignee'] : null,
            'classification' => $this->request->getPost('classification'),
            'severity' => $this->request->getPost('severity'),
            'status' => $this->request->getPost('status'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'project' => $this->request->getPost('project'),
        ];

        if ($issueModel->updateIssue($data)) {
            session()->setFlashdata([
                'message' => MESSAGE_SUCCESS, 
                'color' => MESSAGE_SUCCESS_COLOR, 
                'icon' => MESSAGE_SUCCESS_ICON
            ]);
            return redirect()->to('issue/' . $slug);
        } else {
            session()->setFlashdata([
                'message' => MESSAGE_ERROR, 
                'color' => MESSAGE_ERROR_COLOR, 
                'icon' => MESSAGE_ERROR_ICON
            ]);
            return redirect()->to('issue/' . $slug . '/edit/' . $id);
        }
    }

    public function issue($slug, $id)
    {
        $issueModel = model(IssueModel::class);
        $data = [];

        $data['issue'] = $issueModel->getIssue($id);
        $data['slug'] = $slug;
        
        return view('template/header')
        . view('issue/issue', $data)
        . view('template/footer');
    }

    public function uploadIssueImage()
    {
        $response = [];
        $directory = "/uploads/issues-images/";
        $protocol = '';

        $file = $this->request->getFile('file');
        $file->move(ROOTPATH . PATH_UPLOAD_ISSUES_IMAGES, $file->getRandomName());
  
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") {
            $protocol = "https://";
        } else {
            $protocol = "http://";
        }
        
        $response['token'] = csrf_hash();
        $response['link'] = $protocol . $_SERVER["HTTP_HOST"] . $directory . $file->getName();
        
        return $this->response->setJSON($response);
    }

    public function deleteIssueImage()
    {
        $json = [];
        $response = [];
        $image = '';
        $directory = ROOTPATH . PATH_UPLOAD_ISSUES_IMAGES;

        $json = $this->request->getJSON(true);
        $json['image'] = explode('/', $json['image']);

        $response['token'] = csrf_hash();
        $response['status'] = EXIT_DATABASE;

        $image = end($json['image']);

        if (unlink($directory . $image)) $response['status'] = EXIT_SUCCESS;

        return $this->response->setJSON($response);
    }
}
