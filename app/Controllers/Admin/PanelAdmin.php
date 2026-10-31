<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\UsuarioModel;
use App\Models\ActividadModel;
use App\Models\SucursalModel;
use App\Models\TurnoModel;

class PanelAdmin extends BaseController
{
    // Panel principal del administrador: totales generales
    public function dashboard(): string
    {
        $usuarioModel = new UsuarioModel();
        $actividadModel = new ActividadModel();
        $sucursalModel = new SucursalModel();
        $turnoModel = new TurnoModel();

        $data = [
            'total_alumnos'     => $usuarioModel->where('rol', 'alumno')->where('estado', 'activo')->countAllResults(),
            'total_docentes'    => $usuarioModel->where('rol', 'docente')->where('estado', 'activo')->countAllResults(),
            'total_actividades' => $actividadModel->countAllResults(),
            'total_sucursales'  => $sucursalModel->where('estado', 'activo')->countAllResults(),
            'turnos_activos'    => $turnoModel->where('fecha >=', date('Y-m-d'))->countAllResults()
        ];

        return view('templates/header')
             . view('admin/dashboard', $data)
             . view('templates/footer');
    }
}
