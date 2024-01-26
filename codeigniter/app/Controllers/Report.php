<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProjectModel;
use App\Models\ReportModel;

class Report extends BaseController
{
    public function report($projectSlug = '')
    {
        $projectModel = model(ProjectModel::class);

        $projects[] = $projectModel->getProject('', $projectSlug);
        if (empty($projectSlug)) {
            $projects = session()->get('projects');
            if (in_array('project', session()->get('auth')['permissions']))
              $projects = $projectModel->getProjects();
        }
        
        $data['projects'] = $projects;

        return view('template/header')
        . view('report/report', $data)
        . view('template/footer');
    }

    public function getReport()
    {
        $reportModel = model(ReportModel::class);
        $json = [];
        $response = [];
        $report = [];

        $json = $this->request->getGet();

        $response['token'] = csrf_hash();
        
        $report = $reportModel->getReportByCategoryType($json['type'], $json['project']);
        if ($json['type'] == 'assignee' || $json['type'] == 'reporter')
            $report = $reportModel->getReportByCollaborator($json['type'], $json['project']);

        $response['data']['labels'] = array_map(fn ($label) => $label['label'], $report);
        $response['data']['data'] = array_map(fn ($data) => $data['data'], $report);

        return $this->response->setJSON($response);
    }
}
