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

    // ¿El alumno ya tiene una reserva confirmada en esa fecha y horario?
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
}