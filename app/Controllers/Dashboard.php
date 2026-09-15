<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\ActividadModel;
use App\Models\SucursalModel;
use App\Models\TurnoModel;

class Dashboard extends BaseController
{
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $session = session();
        
       

        $usuarioModel = new UsuarioModel();
        $actividadModel = new ActividadModel();
        $sucursalModel = new SucursalModel();
        $turnoModel = new TurnoModel();

        $data = [
            'total_alumnos'     => $usuarioModel->where('rol', 'alumno')->countAllResults(),
            'total_docentes'    => $usuarioModel->where('rol', 'docente')->countAllResults(),
            'total_actividades' => $actividadModel->countAllResults(),
            'total_sucursales'  => $sucursalModel->countAllResults(),
            // Traemos solo los turnos de hoy en adelante
            'turnos_activos'    => $turnoModel->where('fecha >=', date('Y-m-d'))->countAllResults()
        ];

        return view('templates/header')
             . view('dashboard', $data)
             . view('templates/footer');
    }
}