<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReportModel;

class Report extends BaseController
{
    public function report($project = '')
    {
        return view('template/header')
        . view('report/report')
        . view('template/footer');
    }

    public function getReport()
    {
        $reportModel = model(ReportModel::class);
        $json = [];
        $response = [];
        $report = [];

        $json = $this->request->getJSON(true);

        $response['token'] = csrf_hash();
        
        $report = $reportModel->getReportByCategoryType($json['type'], $json['project']);
        if ($json['type'] == 'assignee' || $json['type'] == 'reporter')
            $report = $reportModel->getReportByCollaborator($json['type'], $json['project']);

        $response['data']['labels'] = array_map(fn ($label) => $label['label'], $report);
        $response['data']['data'] = array_map(fn ($data) => $data['data'], $report);

        return $this->response->setJSON($response);
    }
}
