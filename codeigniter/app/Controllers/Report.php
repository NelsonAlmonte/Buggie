<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Report extends BaseController
{
    public function report($project = '')
    {
        return view('template/header')
        . view('report/report')
        . view('template/footer');
    }
}
