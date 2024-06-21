<?php

namespace App\Cells\Shared\Toast;

use CodeIgniter\View\Cells\Cell;

class Toast extends Cell
{
    public function render(): string
    {
        return $this->view('toast_cell');
    }
}
