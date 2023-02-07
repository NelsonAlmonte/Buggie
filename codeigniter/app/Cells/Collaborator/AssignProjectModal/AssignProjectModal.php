<?php

namespace App\Cells\Collaborator\AssignProjectModal;

use CodeIgniter\View\Cells\Cell;

class AssignProjectModal extends Cell
{
    public $collaboratorProjects;
    public $message;


    public function render(): string
    {
        return $this->view('assign_project_modal_cell');
    }
}
