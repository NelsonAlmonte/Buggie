<?php

namespace App\Cells\Collaborator\AssignCollaboratorModal;

use CodeIgniter\View\Cells\Cell;

class AssignCollaboratorModal extends Cell
{
    public function render(): string
    {
        return $this->view('assign_collaborator_modal_cell');
    }
}
