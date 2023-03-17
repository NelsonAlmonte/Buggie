<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class Category extends BaseController
{
    public function searchCategories()
	{
        $categoryModel = model(CategoryModel::class);
        $json = [];
        $response = [];
        $categories = [];

        $json = $this->request->getJSON(true);

        $response['token'] = csrf_hash();

        $response['data'] = [];
        if (!empty($json['query'])) {
            $categories = $categoryModel->searchCategories($json['query']);
            $response['data'] = array_udiff(
                $categories, $json['unwanted'], 
                fn ($needle, $haystack) => $needle['id'] <=> $haystack['id']
            );
        } else {
            $response['data'] = $categoryModel->searchCategories('');
        }

        return $this->response->setJSON($response);
	}
}
