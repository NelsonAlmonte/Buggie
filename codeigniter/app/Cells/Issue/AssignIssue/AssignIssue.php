<?php

namespace App\Cells\Issue\AssignIssue;

use CodeIgniter\View\Cells\Cell;

class AssignIssue extends Cell
{
    public function render(): string
    {
        return $this->view('assign_issue_cell');
    }
}
