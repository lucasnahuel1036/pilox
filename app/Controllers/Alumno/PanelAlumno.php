<?php

namespace App\Controllers\Alumno;

use App\Controllers\BaseController;

use App\Models\UsuarioModel;
use App\Models\AbonoModel;

class PanelAlumno extends BaseController
{
    // Panel principal del alumno: sus datos y su último abono
    public function dashboard()
    {
        $idUsuario = session()->get('id_usuario');

        $data['alumno'] = (new UsuarioModel())->find($idUsuario);
        $data['abono']  = (new AbonoModel())->getUltimoAbono($idUsuario);

        return view('templates/header')
             . view('alumno/dashboard', $data)
             . view('templates/footer');
    }
}

