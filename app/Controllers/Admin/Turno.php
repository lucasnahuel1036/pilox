<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\TurnoModel;
use App\Models\SucursalModel;
use App\Models\ActividadModel;
use App\Models\UsuarioModel;
use App\Models\AbonoModel;
use App\Models\ReservaModel;

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
        $data['sucursales'] = $sucursalModel->where('estado', 'activo')->findAll();
        $data['actividades'] = $actividadModel->findAll();
        // Solo traemos a los usuarios que son docentes activos
        $data['docentes'] = $usuarioModel->where('rol', 'docente')->where('estado', 'activo')->findAll();
        
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

        if (strtotime($fecha . ' ' . $horario) <= time()) {
            session()->setFlashdata('error', 'No se puede crear un turno en una fecha u horario que ya pasó.');
            return redirect()->back()->withInput();
        }

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

        if (strtotime($turno['fecha'] . ' ' . $turno['horario']) <= time()) {
            session()->setFlashdata('error', 'No se puede editar un turno que ya pasó.');
            return redirect()->to('/admin/turnos?ver=anteriores');
        }

        $data['turno'] = $turno;
        $data['sucursales'] = $sucursalModel->groupStart()
                                                ->where('estado', 'activo')
                                                ->orWhere('id_sucursal', $turno['id_sucursal'])
                                            ->groupEnd()
                                            ->findAll();
        $data['actividades'] = $actividadModel->findAll();
        $data['docentes'] = $usuarioModel->where('rol', 'docente')
                                         ->groupStart()
                                             ->where('estado', 'activo')
                                             ->orWhere('id_usuario', $turno['id_docente'])
                                         ->groupEnd()
                                         ->findAll();

        return view('templates/header')
             . view('admin/editar_turno', $data)
             . view('templates/footer');
    }

        public function actualizar($id)
    {
        $model = new TurnoModel();

        $turnoActual = $model->find($id);

        if (!$turnoActual || strtotime($turnoActual['fecha'] . ' ' . $turnoActual['horario']) <= time()) {
            session()->setFlashdata('error', 'No se puede editar un turno que ya pasó.');
            return redirect()->to('/admin/turnos');
        }

        $capacidad = (int) $this->request->getPost('capacidad');

        // Reservas confirmadas que ya tiene el turno
        $ocupados = db_connect()->table('reservas')
                                ->where('id_turno', $id)
                                ->where('estado', 'confirmada')
                                ->countAllResults();

        if (strtotime($this->request->getPost('fecha') . ' ' . $this->request->getPost('horario')) <= time()) {
            session()->setFlashdata('error', 'No se puede programar el turno en una fecha u horario que ya pasó.');
            return redirect()->back()->withInput();
        }

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

        $data['sucursales'] = $sucursalModel->where('estado', 'activo')->findAll();
        $data['actividades'] = $actividadModel->findAll();
        $data['docentes'] = $usuarioModel->where('rol', 'docente')->where('estado', 'activo')->findAll();

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

        if ($inicioStr < date('Y-m-d')) {
            session()->setFlashdata('error', 'La fecha de inicio no puede ser anterior a hoy.');
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
                if (strtotime($fecha->format('Y-m-d') . ' ' . $hora) <= time()) {
                    continue;
                }
                
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
    
    // Vista para asignar turnos periódicamente a un alumno
    public function asignar()
    {
        $data['sucursales']  = (new SucursalModel())->where('estado', 'activo')->findAll();
        $data['actividades'] = (new ActividadModel())->findAll();

        return view('templates/header')
             . view('admin/turnos_asignar', $data)
             . view('templates/footer');
    }

    // Procesa la asignación periódica de turnos a un alumno
    public function guardar_asignacion()
    {
        $idUsuario         = (int) $this->request->getPost('id_usuario');
        $idActividad       = (int) $this->request->getPost('id_actividad');
        $idSucursal        = (int) $this->request->getPost('id_sucursal');
        $horario           = $this->request->getPost('horario');
        $diasSeleccionados = $this->request->getPost('dias'); // ej: [2, 4] para Martes y Jueves

        if (empty($diasSeleccionados) || !is_array($diasSeleccionados)) {
            session()->setFlashdata('error', 'Debés seleccionar al menos un día de la semana.');
            return redirect()->back()->withInput();
        }

        $alumno = (new UsuarioModel())->where('id_usuario', $idUsuario)->where('rol', 'alumno')->first();
        if (!$alumno || $alumno['estado'] !== 'activo') {
            session()->setFlashdata('error', 'El alumno seleccionado no existe o no está activo.');
            return redirect()->back()->withInput();
        }

        // Exigimos abono vigente y pagado (igual que cuando reserva el alumno)
        $abono = (new AbonoModel())->getAbonoVigente($idUsuario);
        if (!$abono) {
            session()->setFlashdata('error', 'El alumno no tiene un abono vigente con clases disponibles.');
            return redirect()->back()->withInput();
        }
        if ($abono['estado_pago'] !== 'pagado') {
            session()->setFlashdata('error', 'El abono del alumno figura con pago pendiente. Regularizalo antes de asignar turnos.');
            return redirect()->back()->withInput();
        }

        // Buscamos turnos dentro del rango de vigencia del abono
        $fechaDesde = max(date('Y-m-d'), $abono['fecha_inicio']);
        $fechaHasta = $abono['fecha_fin'];

        $turnosCandidatos = (new TurnoModel())->getTurnosParaAsignacion(
            $idActividad,
            $idSucursal,
            $horario,
            $fechaDesde,
            $fechaHasta
        );

        // Filtramos solo los que caen en los días de la semana elegidos (1 = Lunes ... 6 = Sábado)
        $turnosFiltrados = array_filter($turnosCandidatos, function ($t) use ($diasSeleccionados) {
            $diaSemana = date('N', strtotime($t['fecha']));
            return in_array($diaSemana, $diasSeleccionados);
        });

        if (empty($turnosFiltrados)) {
            session()->setFlashdata('error', 'No se encontraron turnos creados para esos días y horario dentro de la vigencia del abono del alumno (' . date('d/m/Y', strtotime($abono['fecha_inicio'])) . ' al ' . date('d/m/Y', strtotime($abono['fecha_fin'])) . ').');
            return redirect()->back()->withInput();
        }

        $reservaModel    = new ReservaModel();
        $clasesRestantes = (int) $abono['clases_restantes'];
        $asignados       = 0;
        $salteados       = [];

        foreach ($turnosFiltrados as $turno) {
            $fechaFormateada = date('d/m/Y', strtotime($turno['fecha']));

            if ($clasesRestantes <= 0) {
                $salteados[] = "$fechaFormateada (abono sin más clases disponibles)";
                continue;
            }

            if ($reservaModel->existeReservaConfirmada($idUsuario, $turno['id_turno'])) {
                $salteados[] = "$fechaFormateada (ya estaba inscripto)";
                continue;
            }

            if ($reservaModel->existeReservaEnHorario($idUsuario, $turno['fecha'], $turno['horario'])) {
                $salteados[] = "$fechaFormateada (tiene otra clase en ese horario)";
                continue;
            }

            if ((int) $turno['cupos_disponibles'] <= 0) {
                $salteados[] = "$fechaFormateada (cupo lleno)";
                continue;
            }

            $resultado = $reservaModel->registrarReserva($idUsuario, $turno['id_turno'], $abono['id_abono']);
            if ($resultado['ok']) {
                $asignados++;
                $clasesRestantes--;
            } else {
                $salteados[] = "$fechaFormateada ({$resultado['error']})";
            }
        }

        if ($asignados === 0) {
            $detalle = !empty($salteados) ? ' Motivos: ' . implode(', ', $salteados) . '.' : '';
            session()->setFlashdata('error', 'No se pudo asignar ningún turno.' . $detalle);
            return redirect()->back()->withInput();
        }

        $mensaje = "Se asignaron $asignados turno(s) a {$alumno['nombre']} {$alumno['apellido']}.";
        if (!empty($salteados)) {
            $mensaje .= ' Fechas salteadas: ' . implode('; ', $salteados) . '.';
        }

        session()->setFlashdata('success', $mensaje);
        return redirect()->to('/admin/turnos');
    }

    // Ver alumnos inscriptos en un turno puntual (y permitir inscribir o cancelar)
    public function inscriptos($idTurno)
    {
        $turno = (new TurnoModel())->getTurnoConDetalles($idTurno);
        if (!$turno) {
            session()->setFlashdata('error', 'El turno no existe.');
            return redirect()->to('/admin/turnos');
        }

        $data['turno']      = $turno;
        $data['inscriptos'] = (new ReservaModel())->getInscriptosDeTurno($idTurno);
        $data['esFuturo']   = strtotime($turno['fecha'] . ' ' . $turno['horario']) > time();

        return view('templates/header')
             . view('admin/turnos_inscriptos', $data)
             . view('templates/footer');
    }

    // Inscribir a un alumno en un turno puntual desde la vista de inscriptos
    public function inscribir($idTurno)
    {
        $idUsuario    = (int) $this->request->getPost('id_usuario');
        $turno        = (new TurnoModel())->find($idTurno);
        $alumno       = (new UsuarioModel())->where('id_usuario', $idUsuario)->where('rol', 'alumno')->first();
        $abonoModel   = new AbonoModel();
        $reservaModel = new ReservaModel();

        if (!$turno || strtotime($turno['fecha'] . ' ' . $turno['horario']) <= time()) {
            session()->setFlashdata('error', 'El turno ya comenzó o no existe.');
            return redirect()->to('/admin/turnos/inscriptos/' . $idTurno);
        }

        if (!$alumno || $alumno['estado'] !== 'activo') {
            session()->setFlashdata('error', 'El alumno seleccionado no está activo.');
            return redirect()->to('/admin/turnos/inscriptos/' . $idTurno);
        }

        $abono = $abonoModel->getAbonoVigente($idUsuario);
        if (!$abono) {
            session()->setFlashdata('error', 'El alumno no tiene un abono vigente con clases disponibles.');
            return redirect()->to('/admin/turnos/inscriptos/' . $idTurno);
        }

        if ($abono['estado_pago'] !== 'pagado') {
            session()->setFlashdata('error', 'El abono del alumno tiene el pago pendiente.');
            return redirect()->to('/admin/turnos/inscriptos/' . $idTurno);
        }

        if ($turno['fecha'] < $abono['fecha_inicio'] || $turno['fecha'] > $abono['fecha_fin']) {
            session()->setFlashdata('error', 'La fecha del turno está fuera de la vigencia del abono del alumno.');
            return redirect()->to('/admin/turnos/inscriptos/' . $idTurno);
        }

        if ($reservaModel->existeReservaConfirmada($idUsuario, $idTurno)) {
            session()->setFlashdata('error', 'El alumno ya está inscripto en este turno.');
            return redirect()->to('/admin/turnos/inscriptos/' . $idTurno);
        }

        if ($reservaModel->existeReservaEnHorario($idUsuario, $turno['fecha'], $turno['horario'])) {
            session()->setFlashdata('error', 'El alumno ya tiene otra reserva en ese mismo día y horario.');
            return redirect()->to('/admin/turnos/inscriptos/' . $idTurno);
        }

        $resultado = $reservaModel->registrarReserva($idUsuario, $idTurno, $abono['id_abono']);
        if (!$resultado['ok']) {
            session()->setFlashdata('error', 'No se pudo inscribir al alumno: ' . $resultado['error'] . '.');
            return redirect()->to('/admin/turnos/inscriptos/' . $idTurno);
        }

        session()->setFlashdata('success', 'Alumno inscripto correctamente en el turno.');
        return redirect()->to('/admin/turnos/inscriptos/' . $idTurno);
    }

    // Cancelar la reserva de un alumno desde el panel Admin
    public function cancelar_reserva($idReserva)
    {
        $reservaModel = new ReservaModel();
        $reserva      = $reservaModel->find($idReserva);

        if (!$reserva || $reserva['estado'] !== 'confirmada') {
            session()->setFlashdata('error', 'La reserva no existe o ya fue cancelada.');
            return redirect()->back();
        }

        $turno = (new TurnoModel())->find($reserva['id_turno']);
        if (!$turno || strtotime($turno['fecha'] . ' ' . $turno['horario']) <= time()) {
            session()->setFlashdata('error', 'No se puede cancelar una reserva de un turno que ya comenzó.');
            return redirect()->to('/admin/turnos/inscriptos/' . $reserva['id_turno']);
        }

        if (!$reservaModel->cancelarReservaConfirmada($reserva)) {
            session()->setFlashdata('error', 'No se pudo cancelar la reserva. Intentá nuevamente.');
            return redirect()->to('/admin/turnos/inscriptos/' . $reserva['id_turno']);
        }

        session()->setFlashdata('success', 'Reserva cancelada. Se liberó el cupo y se devolvió la clase al abono del alumno.');
        return redirect()->to('/admin/turnos/inscriptos/' . $reserva['id_turno']);
    }
}