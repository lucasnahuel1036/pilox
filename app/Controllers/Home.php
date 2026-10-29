<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('templates/header')
             . view('public/inicio')
             . view('templates/footer');
    }
}