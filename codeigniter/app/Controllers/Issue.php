<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\FileModel;
use App\Models\IssueModel;
use App\Models\ProjectModel;
use DOMDocument;
use DOMXPath;

class Issue extends BaseController
{
    public function issues($slug)
    {
        $projectModel = model(ProjectModel::class);
        $issueModel = model(IssueModel::class);
        $data = [];
        $filters = [];
        $projectId = '';

        $data['project'] = $projectModel->getProject('', $slug);
        $data['slug'] = $slug;

        $projectId = $data['project']['id'];

        $filters = $this->_getFilters();
        
        $data['issues'] = $issueModel->getIssues(['project' => $projectId], $filters);
        $data['projectIssues'] = $issueModel->getIssues(['project' => $projectId]);
        $data['openIssues'] = array_filter(
            $data['projectIssues'],
            fn ($issue) => $issue['status_name'] != CATEGORY_ISSUE_STATUS_CLOSED_NAME
          );
          $data['closedIssues'] = array_filter(
            $data['projectIssues'],
            fn ($issue) => $issue['status_name'] == CATEGORY_ISSUE_STATUS_CLOSED_NAME
          );

        return view('template/header')
        . view('issue/issues', $data)
        . view('template/footer');
    }

    private function _getFilters()
    {
        $page = 1;
        $filters = [];

        $filters = $this->request->getGet();

        $filters['fields'] = array_filter($filters);
        unset($filters['fields']['page']);

        if (isset($filters['page']) && $filters['page'] != '') $page = $filters['page'];
        $filters['offset'] = ($page - 1) * PAGINATION_RECORDS_PER_PAGE;

        $filters['limit'] = PAGINATION_RECORDS_PER_PAGE;

        return $filters;
    }

    public function add($slug)
    {
        $projectModel = model(ProjectModel::class);
        $categoryModel = model(CategoryModel::class);
        $data = [];
        $categories = [];
        $categoryTypes = ['classification', 'severity', 'issue_status'];

        $data['project'] = $projectModel->getProject('', $slug);
        $data['auth'] = [
            'id' => session()->get('id'),
            'fullname' => session()->get('name') . ' ' . session()->get('last'),
        ];

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
        $savedIssue = 0;
        $files = $this->request->getFiles();
        $operationsToValidate = 1;
        $successfulOperations = 0;

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

        $savedIssue = $issueModel->saveIssue($data);
        if ($savedIssue > 0) $successfulOperations ++;
        if (!empty($files['files'][0]->getName())) {
            $operationsToValidate = 2;
            if ($this->manageFiles($files, $savedIssue)) $successfulOperations ++;
        }

        if ($successfulOperations == $operationsToValidate) {
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

    public function manageFiles($files, $issue)
    {
        $fileModel = model(FileModel::class);
        $randomFileName = '';
        $filesUploaded = 0;
        $filesSaved = 0;
        $operationResult = false;
        $data = [];
        
        foreach ($files['files'] as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $randomFileName = $file->getRandomName();
                $file->move(ROOTPATH . PATH_UPLOAD_ISSUES_FILES, $randomFileName);
                $filesUploaded ++;
            }
            
            $data = [
                'name' => $randomFileName,
                'issue' => $issue,
                'collaborator' => session()->get('id'),
            ];

            if ($fileModel->saveFile($data)) $filesSaved ++;
        }

        if ($filesUploaded == $filesSaved) $operationResult = true;

        return $operationResult;
    }

    public function edit($slug, $id)
    {
        $issueModel = model(IssueModel::class);
        $projectModel = model(ProjectModel::class);
        $categoryModel = model(CategoryModel::class);
        $fileModel = model(FileModel::class);
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

        $data['files'] = $fileModel->getIssueFiles($id);

        return view('template/header')
        . view('issue/edit', $data)
        . view('template/footer');
    }

    public function update($slug, $id)
    {
        $issueModel = model(IssueModel::class);
        $data = [];
        $files = $this->request->getFiles();
        $operationsToValidate = 1;
        $successfulOperations = 0;

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

        if ($issueModel->updateIssue($data)) $successfulOperations ++;
        if (!empty($files['files'][0]->getName())) {
            $operationsToValidate = 2;
            if ($this->manageFiles($files, $id)) $successfulOperations ++;
        }

        if ($successfulOperations == $operationsToValidate) {
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
        $fileModel = model(FileModel::class);
        $data = [];
        $fileExploded = [];
        $segments = [];
        $fileType = '';

        $data['issue'] = $issueModel->getIssue($id);
        $data['files'] = $fileModel->getIssueFiles($id);
        foreach ($data['files'] as $key => $file) {
            $fileExploded = explode('.', $file['name']);
            $fileType = end($fileExploded);
            $data['files'][$key]['type'] = $fileType;
        }
        $data['slug'] = $slug;
        $segments = explode('-', $slug);
        $data['projectName'] = implode(' ', $segments);
        
        return view('template/header')
        . view('issue/issue', $data)
        . view('template/footer');
    }

    public function uploadIssueImage()
    {
        $response = [];
        $directory = PATH_TO_VIEW_ISSUES_IMAGES;
        $protocol = 'http://';

        $file = $this->request->getFile('file');
        $file->move(ROOTPATH . PATH_UPLOAD_ISSUES_IMAGES, $file->getRandomName());
  
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") $protocol = "https://";
        
        $response['token'] = csrf_hash();
        $response['link'] = $protocol . $_SERVER["HTTP_HOST"] . $directory . $file->getName();
        
        return $this->response->setJSON($response);
    }

    public function deleteIssueImage($image = '', $isAjax = true)
    {
        $json = [];
        $response = [];
        $directory = ROOTPATH . PATH_UPLOAD_ISSUES_IMAGES;

        if ($isAjax) {
            $json = $this->request->getJSON(true);
            $image = $json['image'];
            $response['token'] = csrf_hash();
            $response['status'] = EXIT_DATABASE;
        }

        $image = explode('/', $image);
        $image = end($image);
        if (unlink($directory . $image)) $response['status'] = EXIT_SUCCESS;
        if ($isAjax) $response = $this->response->setJSON($response);

        return $response;
    }

    public function deleteIssueFile($file = [], $isAjax = true)
    {
        $fileModel = model(FileModel::class);
        $json = [];
        $response = [];
        $directory = ROOTPATH . PATH_UPLOAD_ISSUES_FILES;
        $operationsToValidate = 2;
        $successfulOperations = 0;

        if ($isAjax) {
            $json = $this->request->getJSON(true);
            $file = $json['file'];
            $response['token'] = csrf_hash();
            $response['status'] = EXIT_DATABASE;
        }

        if (unlink($directory . $file['name'])) $successfulOperations ++;
        if ($fileModel->deleteFile($file['id'])) $successfulOperations ++;
        if ($successfulOperations == $operationsToValidate) $response['status'] = EXIT_SUCCESS;
        if ($isAjax) $response = $this->response->setJSON($response);

        return $response;
    }

    public function deleteIssue()
    {
        $issueModel = model(IssueModel::class);
        $fileModel = model(FileModel::class);
        $json = [];
        $imagesFromDescription = [];
        $files = [];
        $deletionResponse = [];
        $response = [];
        $operationsToValidate = 1;
        $successfulOperations = 0;

        $json = $this->request->getJSON(true);

        $imagesFromDescription = $this->getImagesFromDescription($json['issue']['description']);
        if (!empty($imagesFromDescription)) {
            $operationsToValidate += count($imagesFromDescription);
            foreach ($imagesFromDescription as $image) {
                $deletionResponse = $this->deleteIssueImage($image, false);
                if ($deletionResponse['status'] == EXIT_SUCCESS) $successfulOperations ++;
            }
        }

        $files = $fileModel->getIssueFiles($json['issue']['id']);
        if (!empty($files)) {
            $operationsToValidate += count($files);
            foreach ($files as $file) {
                $deletionResponse = $this->deleteIssueFile($file, false);
                if ($deletionResponse['status'] == EXIT_SUCCESS) $successfulOperations ++;
            }
        }

        if ($issueModel->deleteIssue($json['issue']['id'])) $successfulOperations ++;

        if ($successfulOperations == $operationsToValidate) $response['status'] = EXIT_SUCCESS;

        $response['token'] = csrf_hash();
        return $this->response->setJSON($response);
    }

    public function getImagesFromDescription($description)
    {
        $images = [];
        $imagesFromDescription = [];

        if (empty($description)) return;

        $document = new DOMDocument();
        $document->loadHTML($description);
        $xpath = new DOMXPath($document);
        $images = $xpath->query('//img/@src');
        if ($images->length > 0) {
            foreach ($images as $image) {
                $imagesFromDescription[] = urldecode($image->value);
            }
        }
        return $imagesFromDescription;
    }

    public function assignIssue()
    {
        $issueModel = model(IssueModel::class);
        $json = [];
        $response = [];

        $json = $this->request->getJSON(true);

        $response['status'] = EXIT_DATABASE;
        $response['token'] = csrf_hash();
        $response['data'] = [];

        $data = [
            'id' => $json['issue'], 
            'assignee' => $json['collaborator']
        ];

        if ($issueModel->updateIssue($data)) $response['status'] = EXIT_SUCCESS;

        return $this->response->setJSON($response);
    }
}
