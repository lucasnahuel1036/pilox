<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

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

        $periodo = ($this->request->getGet('ver') === 'anteriores') ? 'anteriores' : 'proximos';

        // Mandamos los datos para llenar los <select> del formulario
        $data['sucursales'] = $sucursalModel->findAll();
        $data['actividades'] = $actividadModel->findAll();
        // Solo traemos a los usuarios que son docentes
        $data['docentes'] = $usuarioModel->where('rol', 'docente')->findAll();
        
        // Mandamos la lista de turnos paginada según el período elegido
        $data['turnos']  = $turnoModel->getTurnosConDetalles($periodo)->paginate(10);
        $data['pager']   = $turnoModel->pager;
        $data['periodo'] = $periodo;

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

        if ((int) $capacidad < 1) {
            session()->setFlashdata('error', 'La capacidad debe ser al menos 1.');
            return redirect()->back()->withInput();
        }

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
        $turno = $turnoModel->find($id);

        if (!$turno) {
            session()->setFlashdata('error', 'El turno no existe.');
            return redirect()->to('/admin/turnos');
        }

        $sucursalModel = new SucursalModel();
        $actividadModel = new ActividadModel();
        $usuarioModel = new UsuarioModel();

        $data['turno'] = $turno;
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

        $capacidad = (int) $this->request->getPost('capacidad');

        // Reservas confirmadas que ya tiene el turno
        $ocupados = db_connect()->table('reservas')
                                ->where('id_turno', $id)
                                ->where('estado', 'confirmada')
                                ->countAllResults();

        if ($capacidad < 1 || $capacidad < $ocupados) {
            session()->setFlashdata('error', 'La capacidad no puede ser menor a las reservas confirmadas (' . $ocupados . ').');
            return redirect()->back()->withInput();
        }

        $choque = $model->where('fecha', $this->request->getPost('fecha'))
                        ->where('horario', $this->request->getPost('horario'))
                        ->where('id_docente', $this->request->getPost('id_docente'))
                        ->where('id_turno !=', $id)
                        ->first();

        if ($choque) {
            session()->setFlashdata('error', 'El docente ya tiene una clase asignada en esa fecha y horario.');
            return redirect()->back()->withInput();
        }

        $model->update($id, [
            'fecha'             => $this->request->getPost('fecha'),
            'horario'           => $this->request->getPost('horario'),
            'capacidad'         => $capacidad,
            'cupos_disponibles' => $capacidad - $ocupados,
            'id_sucursal'       => $this->request->getPost('id_sucursal'),
            'id_actividad'      => $this->request->getPost('id_actividad'),
            'id_docente'        => $this->request->getPost('id_docente')
        ]);

        session()->setFlashdata('success', 'Turno actualizado correctamente.');
        return redirect()->to('/admin/turnos');
    }

    public function eliminar($id)
    {
        if (db_connect()->table('reservas')->where('id_turno', $id)->countAllResults() > 0) {
        session()->setFlashdata('error', 'No se puede eliminar el turno porque tiene reservas asociadas.');
        return redirect()->to('/admin/turnos');
        }

        $model = new TurnoModel();
        $model->delete($id);
        
        session()->setFlashdata('success', 'Turno eliminado de la grilla.');
        return redirect()->to('/admin/turnos');
    }

    // Busca para aasignar turnos
    public function masivo()
    {
        $sucursalModel = new SucursalModel();
        $actividadModel = new ActividadModel();
        $usuarioModel = new UsuarioModel();

        $data['sucursales'] = $sucursalModel->findAll();
        $data['actividades'] = $actividadModel->findAll();
        $data['docentes'] = $usuarioModel->where('rol', 'docente')->findAll();

        return view('templates/header')
             . view('admin/turnos_masivo', $data)
             . view('templates/footer');
    }

    // Generar turnos reiterativos a largo plazo
    public function generar_masivo()
    {
        $model = new TurnoModel();
        
        $inicioStr          = (string) $this->request->getPost('fecha_inicio');
        $finStr             = (string) $this->request->getPost('fecha_fin');
        $dias_seleccionados = $this->request->getPost('dias'); // Esto será un array (ej: [2, 4] para Mar y Jue)
        $capacidad          = (int) $this->request->getPost('capacidad');
        $hora               = $this->request->getPost('horario');

        // Validamos que haya elegido al menos un día de la semana
        if (empty($dias_seleccionados) || !is_array($dias_seleccionados)) {
            session()->setFlashdata('error', 'Debés seleccionar al menos un día de la semana.');
            return redirect()->back()->withInput();
        }

        // Validamos fechas y rango
        $fecha_inicio = \DateTime::createFromFormat('Y-m-d', $inicioStr);
        $fecha_fin    = \DateTime::createFromFormat('Y-m-d', $finStr);

        if (!$fecha_inicio || $fecha_inicio->format('Y-m-d') !== $inicioStr || !$fecha_fin || $fecha_fin->format('Y-m-d') !== $finStr) {
            session()->setFlashdata('error', 'Las fechas ingresadas no son válidas.');
            return redirect()->back()->withInput();
        }

        if ($fecha_fin < $fecha_inicio) {
            session()->setFlashdata('error', 'La fecha de fin no puede ser anterior a la fecha de inicio.');
            return redirect()->back()->withInput();
        }

        if ($capacidad < 1) {
            session()->setFlashdata('error', 'La capacidad debe ser al menos 1.');
            return redirect()->back()->withInput();
        }

        $fecha_fin->modify('+1 day'); // Sumamos un día para que el bucle incluya la fecha final
        
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