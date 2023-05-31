<?php

namespace App\Cells\Collaborator\CollaboratorSummary;

use App\Models\CollaboratorModel;
use App\Models\IssueModel;
use CodeIgniter\View\Cells\Cell;

class CollaboratorSummary extends Cell
{
    protected $projects = [];
    protected $issues = [];
    protected $closedIssues = [];

    public function mount($collaborator)
    {
        $collaboratorModel = model(CollaboratorModel::class);
        $issueModel = model(IssueModel::class);

        $this->projects = $collaboratorModel->getCollaboratorProjects($collaborator);
        $this->issues = $issueModel->getCollaboratorIssues($collaborator);
        $this->closedIssues = array_filter(
            $this->issues,
            fn ($issue) => $issue['status_name'] == CATEGORY_ISSUE_STATUS_CLOSED_NAME
        );
    }

    public function render(): string
    {
        $data = [
            'projects' => $this->projects,
            'issues' => $this->issues,
            'closedIssues' => $this->closedIssues,
        ];
        return $this->view('collaborator_summary_cell', $data);
    }
}
