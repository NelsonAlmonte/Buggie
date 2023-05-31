<?php

namespace App\Cells\Collaborator\AssignProjectModal;

use CodeIgniter\View\Cells\Cell;

class AssignProjectModal extends Cell
{
    protected $collaboratorProjects = [];

    public function mount($collaboratorProjects)
    {
        $this->collaboratorProjects = $collaboratorProjects;
    }

    public function render(): string
    {
        $data = [];
        $data['collaboratorProjects'] = $this->collaboratorProjects;
        return $this->view('assign_project_modal_cell', $data);
    }
}
