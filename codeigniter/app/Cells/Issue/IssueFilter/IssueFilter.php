<?php

namespace App\Cells\Issue\IssueFilter;

use CodeIgniter\View\Cells\Cell;

class IssueFilter extends Cell
{
    protected $options = [];

    public function mount($options)
    {
        $this->options = $options;    
    }

    public function render(): string
    {
        $data = [];
        $data = [
            'name' => $this->options['name'],
            'controller' => $this->options['controller'],
            'method' => $this->options['method'],
        ];
        return $this->view('issue_filter_cell', $data);
    }
}
