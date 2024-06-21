<?php

namespace App\Cells\Project\IssuesCount;

use App\Models\IssueModel;
use CodeIgniter\View\Cells\Cell;

class IssuesCount extends Cell
{
    protected $issuesCount = [];
    protected $project = [];
    protected $progress = 0;

    public function mount($project)
    {
        $issueModel = model(IssueModel::class);
        $totalIssues = [];
        $openIssues = [];
        $PROGRESS_BAR_MAX = 100;
        $totalIssues = $issueModel->getIssues($project['id']);
        $openIssues = array_filter(
            $totalIssues,
            fn ($issue) => $issue['status_name'] == CATEGORY_ISSUE_STATUS_OPEN_NAME
        );
        $this->issuesCount = [
            'totalIssues' => count($totalIssues),
            'openIssues' => count($openIssues),
        ];
        $this->project = $project;
        if (count($openIssues) == 0 && count($totalIssues) != 0) {
            $this->progress = $PROGRESS_BAR_MAX;
        } elseif(count($totalIssues) != count($openIssues)) {
            $this->progress = (count($openIssues) / count($totalIssues)) * $PROGRESS_BAR_MAX;
        } else {
            $this->progress = 0;
        }
    }

    public function render(): string
    {
        $data = [];
        $data = [
            'issuesCount' => $this->issuesCount,
            'slug' => $this->project['slug'],
            'progress' => number_format($this->progress),
        ];
        return $this->view('issues_count_cell', $data);
    }
}
