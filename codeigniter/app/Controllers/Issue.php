<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\IssueModel;
use App\Models\ProjectModel;

class Issue extends BaseController
{
    public function issues($slug = '')
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
        helper('url');
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
            'end_date' => $this->request->getPost('end_date'),
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
}
