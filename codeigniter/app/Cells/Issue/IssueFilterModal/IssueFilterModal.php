<?php

namespace App\Cells\Issue\IssueFilterModal;

use CodeIgniter\View\Cells\Cell;

class IssueFilterModal extends Cell
{
    protected $filters = [];
    protected $slug = '';

    public function mount($slug)
    {
        $filters = [
            'reporter' => [
                'controller' => 'collaborator',
                'method' => 'searchCollaborators',
                'searchKey' => 'username',
            ],
            'assignee' => [
                'controller' => 'collaborator',
                'method' => 'searchCollaborators',
                'searchKey' => 'username',
            ],
            'status' => [
                'controller' => 'category',
                'method' => 'searchCategories',
                'searchKey' => 'name',
            ],
        ];
        $this->filters = $filters;
        $this->slug = $slug;
    }

    public function render(): string
    {
        $data['filters'] = $this->filters;
        $data['slug'] = $this->slug;
        return $this->view('issue_filter_modal_cell', $data);
    }
}
