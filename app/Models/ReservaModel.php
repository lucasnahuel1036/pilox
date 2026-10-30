<?php

namespace App\Models;

use CodeIgniter\Model;

class ReservaModel extends Model
{
    protected $table            = 'reservas';
    protected $primaryKey       = 'id_reserva';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'fecha_reserva', 'estado', 'asistencia',
        'id_usuario', 'id_turno', 'id_abono'
    ];

    // ¿El alumno ya tiene una reserva confirmada en este turno?
    public function existeReservaConfirmada($idUsuario, $idTurno)
    {
        return $this->where('id_usuario', $idUsuario)
                    ->where('id_turno', $idTurno)
                    ->where('estado', 'confirmada')
                    ->first() !== null;
    }

    // Consulta si el alumno ya tiene una reserva confirmada en esa fecha y horario
    public function existeReservaEnHorario($idUsuario, $fecha, $horario)
    {
        return $this->select('reservas.id_reserva')
                    ->join('turnos', 'turnos.id_turno = reservas.id_turno')
                    ->where('reservas.id_usuario', $idUsuario)
                    ->where('reservas.estado', 'confirmada')
                    ->where('turnos.fecha', $fecha)
                    ->where('turnos.horario', $horario)
                    ->first() !== null;
    }
    
        // Todas las reservas de un alumno con los datos del turno (más recientes primero)
    public function getReservasDeAlumno($idUsuario)
    {
        return $this->select('reservas.*, turnos.fecha, turnos.horario, actividades.nombre as actividad, sucursales.nombre as sucursal, usuarios.nombre as docente_nombre, usuarios.apellido as docente_apellido')
                    ->join('turnos', 'turnos.id_turno = reservas.id_turno')
                    ->join('actividades', 'actividades.id_actividad = turnos.id_actividad')
                    ->join('sucursales', 'sucursales.id_sucursal = turnos.id_sucursal')
                    ->join('usuarios', 'usuarios.id_usuario = turnos.id_docente')
                    ->where('reservas.id_usuario', $idUsuario)
                    ->orderBy('turnos.fecha', 'DESC')
                    ->orderBy('turnos.horario', 'DESC')
                    ->findAll();
    }
        // Lo usa el docente: alumnos con reserva confirmada en un turno 
    public function getInscriptosDeTurno($idTurno)
    {
        return $this->select('reservas.id_reserva, reservas.asistencia, usuarios.nombre, usuarios.apellido, usuarios.dni')
                    ->join('usuarios', 'usuarios.id_usuario = reservas.id_usuario')
                    ->where('reservas.id_turno', $idTurno)
                    ->where('reservas.estado', 'confirmada')
                    ->orderBy('usuarios.apellido', 'ASC')
                    ->orderBy('usuarios.nombre', 'ASC')
                    ->findAll();
    }
        // Descuenta cupo y clase del abono, e inserta la reserva en una transacción
    public function registrarReserva($idUsuario, $idTurno, $idAbono): array
    {
        $db = db_connect();
        $db->transBegin();

        $db->table('turnos')
           ->where('id_turno', $idTurno)
           ->where('cupos_disponibles >', 0)
           ->set('cupos_disponibles', 'cupos_disponibles - 1', false)
           ->update();
        $cupoOk = ($db->affectedRows() === 1);

        $db->table('abonos')
           ->where('id_abono', $idAbono)
           ->where('clases_restantes >', 0)
           ->set('clases_restantes', 'clases_restantes - 1', false)
           ->update();
        $claseOk = ($db->affectedRows() === 1);

        if (!$cupoOk || !$claseOk) {
            $db->transRollback();
            return [
                'ok'    => false,
                'error' => !$cupoOk ? 'sin cupos disponibles' : 'sin clases restantes en el abono',
            ];
        }

        $db->table('reservas')->insert([
            'id_usuario' => $idUsuario,
            'id_turno'   => $idTurno,
            'id_abono'   => $idAbono,
        ]);

        if ($db->transStatus() === false) {
            $db->transRollback();
            return ['ok' => false, 'error' => 'error al guardar la reserva'];
        }

        $db->transCommit();
        return ['ok' => true];
    }

    // Cancela una reserva confirmada, libera el cupo y devuelve la clase al abono
    public function cancelarReservaConfirmada($reserva): bool
    {
        $db = db_connect();
        $db->transBegin();

        $db->table('reservas')
           ->where('id_reserva', $reserva['id_reserva'])
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
            return false;
        }

        $db->transCommit();
        return true;
    }
}