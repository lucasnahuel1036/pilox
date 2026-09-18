<?php

namespace App\Controllers;

use App\Models\TurnoModel;
use App\Models\SucursalModel;
use App\Models\ActividadModel;
use App\Models\UsuarioModel;

class Turno extends BaseController
{
    public function index()
    {
        

        // Instanciamos todos los modelos que necesitamos
        $turnoModel = new TurnoModel();
        $sucursalModel = new SucursalModel();
        $actividadModel = new ActividadModel();
        $usuarioModel = new UsuarioModel();

        // Mandamos los datos para llenar los <select> del formulario
        $data['sucursales'] = $sucursalModel->findAll();
        $data['actividades'] = $actividadModel->findAll();
        // Solo traemos a los usuarios que son docentes
        $data['docentes'] = $usuarioModel->where('rol', 'docente')->findAll();
        
        // Mandamos la lista de turnos (con los nombres de las tablas unidas)
        $data['turnos'] = $turnoModel->getTurnosConDetalles();

        return view('templates/header')
             . view('admin/turnos', $data)
             . view('templates/footer');
    }

    public function guardar()
    {
        $model = new TurnoModel();
        
        // Capturamos la capacidad elegida
        $capacidad = $this->request->getPost('capacidad');
        
        $fecha = $this->request->getPost('fecha');
        $horario = $this->request->getPost('horario');
        $id_docente = $this->request->getPost('id_docente');

        $existeTurno = $model->where('fecha', $fecha)
                             ->where('horario', $horario)
                             ->where('id_docente', $id_docente)
                             ->first();

        if ($existeTurno) {
            session()->setFlashdata('error', 'El docente ya tiene una clase asignada en esa fecha y horario.');
            return redirect()->back()->withInput();
        }

        $model->insert([
            'fecha'             => $this->request->getPost('fecha'),
            'horario'           => $this->request->getPost('horario'),
            'capacidad'         => $capacidad,
            'cupos_disponibles' => $capacidad, // La magia: los cupos inician igual que la capacidad
            'id_sucursal'       => $this->request->getPost('id_sucursal'),
            'id_actividad'      => $this->request->getPost('id_actividad'),
            'id_docente'        => $this->request->getPost('id_docente')
        ]);
        
        session()->setFlashdata('success', 'Turno configurado exitosamente.');
        return redirect()->to('/admin/turnos');
    }

    public function editar($id)
    {
        $turnoModel = new TurnoModel();
        $sucursalModel = new SucursalModel();
        $actividadModel = new ActividadModel();
        $usuarioModel = new UsuarioModel();

        $data['turno'] = $turnoModel->find($id);
        $data['sucursales'] = $sucursalModel->findAll();
        $data['actividades'] = $actividadModel->findAll();
        $data['docentes'] = $usuarioModel->where('rol', 'docente')->findAll();

        return view('templates/header')
             . view('admin/editar_turno', $data)
             . view('templates/footer');
    }

    public function actualizar($id)
    {
        $model = new TurnoModel();
        
        $capacidad = $this->request->getPost('capacidad');

        $model->update($id, [
            'fecha'             => $this->request->getPost('fecha'),
            'horario'           => $this->request->getPost('horario'),
            'capacidad'         => $capacidad,
            'cupos_disponibles' => $capacidad, // Por ahora (sin alumnos inscriptos) reiniciamos los cupos
            'id_sucursal'       => $this->request->getPost('id_sucursal'),
            'id_actividad'      => $this->request->getPost('id_actividad'),
            'id_docente'        => $this->request->getPost('id_docente')
        ]);
        
        session()->setFlashdata('success', 'Turno actualizado correctamente.');
        return redirect()->to('/admin/turnos');
    }

    public function eliminar($id)
    {
        $model = new TurnoModel();
        $model->delete($id);
        
        session()->setFlashdata('success', 'Turno eliminado de la grilla.');
        return redirect()->to('/admin/turnos');
    }

    // Busca para aasignar turnos
    public function masivo()
    {
        $sucursalModel = new \App\Models\SucursalModel();
        $actividadModel = new \App\Models\ActividadModel();
        $usuarioModel = new \App\Models\UsuarioModel();

        $data['sucursales'] = $sucursalModel->findAll();
        $data['actividades'] = $actividadModel->findAll();
        $data['docentes'] = $usuarioModel->where('rol', 'docente')->findAll();

        return view('templates/header')
             . view('admin/generar_turnos', $data)
             . view('templates/footer');
    }

    // Generar turnos reiterativos a largo plazo
    public function generar_masivo()
    {
        $model = new TurnoModel();
        
        // Capturamos el rango de fechas
        $fecha_inicio = new \DateTime($this->request->getPost('fecha_inicio'));
        $fecha_fin = new \DateTime($this->request->getPost('fecha_fin'));
        $fecha_fin->modify('+1 day'); // Sumamos un día para que el bucle incluya la fecha final
        
        // Capturamos los datos fijos de la clase
        $hora = $this->request->getPost('horario');
        $dias_seleccionados = $this->request->getPost('dias'); // Esto será un array (ej: [2, 4] para Mar y Jue)
        $capacidad = $this->request->getPost('capacidad');
        
        // Configuramos el iterador de PHP para avanzar de a 1 día
        $intervalo = new \DateInterval('P1D');
        $periodo = new \DatePeriod($fecha_inicio, $intervalo, $fecha_fin);
        
        $turnos_creados = 0;

        //Recorremos el calendario
        foreach ($periodo as $fecha) {
            // El método format('N') devuelve 1 (Lunes) a 7 (Domingo)
            if (in_array($fecha->format('N'), $dias_seleccionados)) {
                
                $existeTurno = $model->where('fecha', $fecha->format('Y-m-d'))
                                     ->where('horario', $hora)
                                     ->where('id_docente', $this->request->getPost('id_docente'))
                                     ->first();

                if (!$existeTurno) {
                    $model->insert([
                        'fecha'             => $fecha->format('Y-m-d'),
                        'horario'           => $hora,
                        'capacidad'         => $capacidad,
                        'cupos_disponibles' => $capacidad,
                        'id_sucursal'       => $this->request->getPost('id_sucursal'),
                        'id_actividad'      => $this->request->getPost('id_actividad'),
                        'id_docente'        => $this->request->getPost('id_docente')
                    ]);
                    $turnos_creados++;
                }
            }
        }
        
        session()->setFlashdata('success', "¡Grilla generada! Se crearon $turnos_creados clases automáticamente.");
        return redirect()->to('/admin/turnos');
    }
}