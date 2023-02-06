<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function home()
    {
        return view('template/header')
        . view('home/home')
        . view('template/footer');
    }
}
