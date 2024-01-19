<?php

namespace App\Cells\Issue\AssignIssue;

use CodeIgniter\View\Cells\Cell;

class AssignIssue extends Cell
{
    protected $project = [];

    public function mount($project)
    {
        $this->project = $project;
    }

    public function render(): string
    {
        $data = [];
        $data['project'] = $this->project;
        return $this->view('assign_issue_cell', $data);
    }
}
