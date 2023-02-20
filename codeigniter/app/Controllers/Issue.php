<?php

namespace App\Controllers;

class Issue extends BaseController
{
    public function issues($projectSlug = '')
    {
        return view('template/header')
        . view('issue/issues')
        . view('template/footer');
    }
}
