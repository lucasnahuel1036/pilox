<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('templates/header')
             . view('inicio')
             . view('templates/footer');
    }
}