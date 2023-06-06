<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FileModel;
use App\Models\ProjectModel;

class File extends BaseController
{
    public function files($slug)
    {
        $fileModel = model(FileModel::class);
        $projectModel = model(ProjectModel::class);
        $data = [];
        $fileExploded = [];
        $fileType = '';

        $data['project'] = $projectModel->getProject('', $slug);

        $data['files'] = $fileModel->getProjectFiles($data['project']['id']);
        foreach ($data['files'] as $key => $file) {
            $fileExploded = explode('.', $file['name']);
            $fileType = end($fileExploded);
            $data['files'][$key]['type'] = $fileType;
        }

        return view('template/header')
        . view('file/files', $data)
        . view('template/footer');
    }
}
