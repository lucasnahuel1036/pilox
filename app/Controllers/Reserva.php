<?php

namespace App\Controllers;

use App\Models\TurnoModel;
use App\Models\SucursalModel;
use App\Models\ActividadModel;
use App\Models\AbonoModel;
use App\Models\ReservaModel;
use App\Models\UsuarioModel;

class Reserva extends BaseController
{
        // Horas de anticipación mínimas para poder cancelar una reserva
    const HORAS_CANCELACION = 1;
    
    // CU3: Consultar turnos disponibles
    public function turnos()
    {
        $idUsuario = session()->get('id_usuario');

        $filtros = [
            'id_actividad' => $this->request->getGet('id_actividad'),
            'id_sucursal'  => $this->request->getGet('id_sucursal'),
            'fecha'        => $this->request->getGet('fecha'),
            'franja'       => $this->request->getGet('franja'),
        ];

        $reservaModel = new ReservaModel();

        $data['filtros']     = $filtros;
        $data['turnos']      = (new TurnoModel())->getTurnosDisponibles($filtros);
        $data['actividades'] = (new ActividadModel())->findAll();
        $data['sucursales']  = (new SucursalModel())->findAll();
        $data['abono']       = (new AbonoModel())->getAbonoVigente($idUsuario);
        $misReservas = $reservaModel->select('reservas.id_turno, turnos.fecha, turnos.horario')
                                    ->join('turnos', 'turnos.id_turno = reservas.id_turno')
                                    ->where('reservas.id_usuario', $idUsuario)
                                    ->where('reservas.estado', 'confirmada')
                                    ->findAll();

        $data['reservados']       = array_column($misReservas, 'id_turno');
        $data['horariosOcupados'] = array_map(fn($r) => $r['fecha'] . ' ' . $r['horario'], $misReservas);

        return view('templates/header')
             . view('alumno/turnos', $data)
             . view('templates/footer');
    }

    // CU4: Realizar reserva
    public function reservar($idTurno)
    {
        $idUsuario    = session()->get('id_usuario');
        $abonoModel   = new AbonoModel();
        $reservaModel = new ReservaModel();

        $alumno = (new UsuarioModel())->find($idUsuario);
        $turno  = (new TurnoModel())->find($idTurno);

        // 1. El alumno debe estar habilitado
        if (!$alumno || $alumno['estado'] !== 'activo') {
            session()->setFlashdata('error', 'Tu cuenta no está habilitada para reservar. Consultá en la administración.');
            return redirect()->back();
        }

        // 2. El turno debe existir y no haber empezado
        if (!$turno || strtotime($turno['fecha'] . ' ' . $turno['horario']) <= time()) {
            session()->setFlashdata('error', 'El turno ya no está disponible.');
            return redirect()->back();
        }

        // 3. Debe tener un abono vigente con clases
        $abono = $abonoModel->getAbonoVigente($idUsuario);
        if (!$abono) {
            session()->setFlashdata('error', 'No tenés un abono vigente con clases disponibles.');
            return redirect()->back();
        }

        // 4. El abono debe estar pagado
        if ($abono['estado_pago'] !== 'pagado') {
            session()->setFlashdata('error', 'Tu abono tiene el pago pendiente. Regularizalo para poder reservar.');
            return redirect()->back();
        }

        // 5. El turno debe caer dentro de la vigencia del abono
        if ($turno['fecha'] < $abono['fecha_inicio'] || $turno['fecha'] > $abono['fecha_fin']) {
            session()->setFlashdata('error', 'El turno está fuera de la vigencia de tu abono.');
            return redirect()->back();
        }

        // 6. No puede reservar dos veces el mismo turno
        if ($reservaModel->existeReservaConfirmada($idUsuario, $idTurno)) {
            session()->setFlashdata('error', 'Ya tenés una reserva en este turno.');
            return redirect()->back();
        }

        // 6b. No puede tener otra clase confirmada en el mismo día y horario
        if ($reservaModel->existeReservaEnHorario($idUsuario, $turno['fecha'], $turno['horario'])) {
            session()->setFlashdata('error', 'Ya tenés otra clase reservada en ese mismo día y horario.');
            return redirect()->back();
        }

        // 7. Descontamos cupo y clase, y creamos la reserva en una sola transacción.
        //    Los UPDATE con condición evitan que dos alumnos tomen el último cupo.
        $db = db_connect();
        $db->transBegin();

        $db->table('turnos')
           ->where('id_turno', $idTurno)
           ->where('cupos_disponibles >', 0)
           ->set('cupos_disponibles', 'cupos_disponibles - 1', false)
           ->update();
        $cupoOk = ($db->affectedRows() === 1);

        $db->table('abonos')
           ->where('id_abono', $abono['id_abono'])
           ->where('clases_restantes >', 0)
           ->set('clases_restantes', 'clases_restantes - 1', false)
           ->update();
        $claseOk = ($db->affectedRows() === 1);

        if (!$cupoOk || !$claseOk) {
            $db->transRollback();
            $mensaje = !$cupoOk ? 'El turno se quedó sin cupos.' : 'No te quedan clases disponibles en tu abono.';
            session()->setFlashdata('error', $mensaje);
            return redirect()->back();
        }

        $db->table('reservas')->insert([
            'id_usuario' => $idUsuario,
            'id_turno'   => $idTurno,
            'id_abono'   => $abono['id_abono'],
        ]);

        if ($db->transStatus() === false) {
            $db->transRollback();
            session()->setFlashdata('error', 'No se pudo realizar la reserva. Intentá nuevamente.');
            return redirect()->back();
        }

        $db->transCommit();

        session()->setFlashdata('success', 'Reserva confirmada. Se descontó una clase de tu abono.');
        return redirect()->back();
    }

        // CU5: Consultar reservas del alumno
    public function misReservas()
    {
        $idUsuario = session()->get('id_usuario');
        $todas     = (new ReservaModel())->getReservasDeAlumno($idUsuario);
        $ahora     = time();

        $proximas  = [];
        $historial = [];

        foreach ($todas as $reserva) {
            $inicio = strtotime($reserva['fecha'] . ' ' . $reserva['horario']);

            if ($reserva['estado'] === 'confirmada' && $inicio > $ahora) {
                // ¿Todavía está a tiempo de cancelar?
                $reserva['puede_cancelar'] = ($inicio - $ahora) >= (self::HORAS_CANCELACION * 3600);
                $proximas[] = $reserva;
            } else {
                $historial[] = $reserva;
            }
        }

        // Las más cercanas primero
        $data['proximas']  = array_reverse($proximas);
        $data['historial'] = $historial;

        return view('templates/header')
             . view('alumno/reservas', $data)
             . view('templates/footer');
    }

    // CU6: Cancelar reserva (devuelve la clase al abono y libera el cupo)
    public function cancelar($idReserva)
    {
        $idUsuario    = session()->get('id_usuario');
        $reservaModel = new ReservaModel();

        // La reserva debe existir y ser del alumno logueado
        $reserva = $reservaModel->where('id_reserva', $idReserva)
                                ->where('id_usuario', $idUsuario)
                                ->first();

        if (!$reserva) {
            session()->setFlashdata('error', 'La reserva no existe.');
            return redirect()->to('/alumno/reservas');
        }

        if ($reserva['estado'] !== 'confirmada') {
            session()->setFlashdata('error', 'La reserva ya fue cancelada.');
            return redirect()->to('/alumno/reservas');
        }

        // Solo se puede cancelar con la anticipación mínima
        $turno  = (new TurnoModel())->find($reserva['id_turno']);
        $inicio = strtotime($turno['fecha'] . ' ' . $turno['horario']);

        if (($inicio - time()) < (self::HORAS_CANCELACION * 3600)) {
            session()->setFlashdata('error', 'Solo se puede cancelar hasta ' . self::HORAS_CANCELACION . ' horas antes de la clase.');
            return redirect()->to('/alumno/reservas');
        }

        // Todo o nada: marcamos cancelada, liberamos el cupo y devolvemos la clase
        $db = db_connect();
        $db->transBegin();

        $db->table('reservas')
           ->where('id_reserva', $idReserva)
           ->where('estado', 'confirmada')
           ->set('estado', 'cancelada')
           ->update();
        $reservaOk = ($db->affectedRows() === 1);

        $db->table('turnos')
           ->where('id_turno', $reserva['id_turno'])
           ->set('cupos_disponibles', 'cupos_disponibles + 1', false)
           ->update();
        $cupoOk = ($db->affectedRows() === 1);

        $db->table('abonos')
           ->where('id_abono', $reserva['id_abono'])
           ->set('clases_restantes', 'clases_restantes + 1', false)
           ->update();
        $claseOk = ($db->affectedRows() === 1);

        if (!$reservaOk || !$cupoOk || !$claseOk || $db->transStatus() === false) {
            $db->transRollback();
            session()->setFlashdata('error', 'No se pudo cancelar la reserva. Intentá nuevamente.');
            return redirect()->to('/alumno/reservas');
        }

        $db->transCommit();

        session()->setFlashdata('success', 'Reserva cancelada. Se te devolvió la clase a tu abono.');
        return redirect()->to('/alumno/reservas');
    }
}