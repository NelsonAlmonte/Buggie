<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\IssueModel;
use App\Models\ProjectModel;

class Calendar extends BaseController
{
    public function calendar($projectSlug = '')
    {
        $projectModel = model(ProjectModel::class);
        $issueModel = model(IssueModel::class);
        $projects = [];
        $issues = [];

        $projects[] = $projectModel->getProject('', $projectSlug);
        if (empty($projectSlug)) {
            $projects = session()->get('projects');
            if (in_array('project', session()->get('auth')['permissions']))
              $projects = $projectModel->getProjects();
        }

        $issues = $issueModel->getIssues($projects[0]['id']);
        
        $data['projects'] = $projects;
        $data['issues'] = $issues;

        return view('template/header')
        . view('calendar/calendar', $data)
        . view('template/footer');
    }
    
    public function getIssues()
    {
        $issueModel = model(issueModel::class);
        $json = [];
        $response = [];
        $issues = [];

        $json = $this->request->getJSON(true);

        $response['token'] = csrf_hash();
        $response['events'] = $issues;
        
        $issues = $issueModel->getIssues($json['projectId']);

        foreach ($issues as $key => $issue) {
            $response['events'][$key]['start'] = $issue['start_date'];
            $response['events'][$key]['title'] = $issue['title'];
            $response['events'][$key]['url'] = '/issue/' . $json['projectSlug'] . '/issue/' . $issue['id'];
            $response['events'][$key]['backgroundColor'] = '#' . $issue['status_color'];
            $response['events'][$key]['borderColor'] = '#' . $issue['status_color'];
        }

        return $this->response->setJSON($response);
    }
}
