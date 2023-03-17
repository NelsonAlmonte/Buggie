<?php

namespace App\Cells\Issue\IssueSort;

use CodeIgniter\View\Cells\Cell;

class IssueSort extends Cell
{
    public function render(): string
    {
        return $this->view('issue_sort_cell');
    }
}
