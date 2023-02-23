<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProjectModel;

class Issue extends BaseController
{
    public function issues($slug = '')
    {
        $projectModel = model(ProjectModel::class);
        $data = [];

        $data['project'] = $projectModel->getProject('', $slug);
        $data['slug'] = $slug;

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
        $categoryTypes = ['classification', 'severity', 'project_status'];

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

    public function save()
    {
        helper('url');
        $issueModel = model(IssueModel::class);
        $data = [];

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'reporter' => $this->request->getPost('reporter'),
            'assignee' => $this->request->getPost('assignee'),
            'classification' => $this->request->getPost('classification'),
            'severity' => $this->request->getPost('severity'),
            'status' => $this->request->getPost('status'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'project' => $this->request->getPost('project'),
        ];

        if ($issueModel->saveIssue($data)) {
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
}
