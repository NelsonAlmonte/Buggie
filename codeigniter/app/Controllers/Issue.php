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
        print_r($_FILES);
    }
}
