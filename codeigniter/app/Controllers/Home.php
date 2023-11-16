<?php

namespace App\Controllers;

use App\Models\CollaboratorModel;
use App\Models\IssueModel;
use DateTime;

class Home extends BaseController
{
    public function home()
    {
        $collaboratorModel = model(CollaboratorModel::class);
        $issueModel = model(IssueModel::class);
        $data = [];
        $collaboratorId = session()->get('id');

        
        $data['collaboratorIssues'] = $issueModel->getCollaboratorIssues($collaboratorId);
        $data['closedCollaboratorIssues'] = array_filter(
            $data['collaboratorIssues'],
            fn ($issue) => $issue['status_name'] == CATEGORY_ISSUE_STATUS_CLOSED_NAME 
        );
        $data['overdueIssues'] = array_map(
            'self::_getIntervalFromNow',
            $data['collaboratorIssues'],
        );
        
        foreach ($data['collaboratorIssues'] as $key => $issue) {
            $data['collaboratorIssues'][$key]['overdueDays'] = $data['overdueIssues'][$key];
        }

        $collaboratorProjects = $collaboratorModel->getCollaboratorProjects($collaboratorId);
        $data['projectsWithoutAccess'] = [];
        $data['collaboratorProjects'] = [];
        
        if (in_array('project', session()->get('auth')['permissions'])) {
            $data['collaboratorProjects'] = $collaboratorProjects;
        } else{
            $loggedCollaboratorProjects = session()->get('projects');
            $loggedCollaboratorProjects = array_map(fn ($project) => $project['id'], $loggedCollaboratorProjects);

            foreach ($collaboratorProjects as $project) {
                if (in_array($project['id'], $loggedCollaboratorProjects)) {
                    $data['collaboratorProjects'][] = $project;
                } else {
                    $data['projectsWithoutAccess'][] = $project;
                }
            }
        }

        return view('template/header')
        . view('home/home', $data)
        . view('template/footer');
    }

    private function _getIntervalFromNow($issue)
    {
        $nowDate = new DateTime();
        $dueDate = new DateTime($issue['end_date']);
        $interval = $dueDate->diff($nowDate);
        return $interval->format('%a');
    }
}
