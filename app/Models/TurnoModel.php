<?php

namespace App\Models;

use CodeIgniter\Model;

class TurnoModel extends Model
{
    protected $table            = 'turnos';
    protected $primaryKey       = 'id_turno';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'fecha', 
        'horario', 
        'capacidad', 
        'cupos_disponibles', 
        'id_sucursal', 
        'id_actividad', 
        'id_docente'
    ];

    /**
     * Esta función hace JOIN con las otras tablas para traer
     * el nombre de la actividad, la sucursal y el docente
     */
    public function getTurnosConDetalles()
    {
        return $this->select('turnos.*, actividades.nombre as actividad, sucursales.nombre as sucursal, usuarios.nombre as docente_nombre, usuarios.apellido as docente_apellido')
                    ->join('actividades', 'actividades.id_actividad = turnos.id_actividad')
                    ->join('sucursales', 'sucursales.id_sucursal = turnos.id_sucursal')
                    ->join('usuarios', 'usuarios.id_usuario = turnos.id_docente')
                    ->orderBy('turnos.fecha', 'ASC')
                    ->orderBy('turnos.horario', 'ASC')
                    ->findAll();
    }

     /**
     * Turnos futuros con cupo, filtrables por actividad, sucursal, día y franja horaria
     */
    public function getTurnosDisponibles($filtros = [])
    {
        $ahora = date('Y-m-d H:i:s');

        $this->select('turnos.*, actividades.nombre as actividad, sucursales.nombre as sucursal, usuarios.nombre as docente_nombre, usuarios.apellido as docente_apellido')
             ->join('actividades', 'actividades.id_actividad = turnos.id_actividad')
             ->join('sucursales', 'sucursales.id_sucursal = turnos.id_sucursal')
             ->join('usuarios', 'usuarios.id_usuario = turnos.id_docente')
             ->where('turnos.cupos_disponibles >', 0)
             ->where("TIMESTAMP(turnos.fecha, turnos.horario) > '" . $ahora . "'", null, false);

        if (!empty($filtros['id_actividad'])) {
            $this->where('turnos.id_actividad', $filtros['id_actividad']);
        }
        if (!empty($filtros['id_sucursal'])) {
            $this->where('turnos.id_sucursal', $filtros['id_sucursal']);
        }
        if (!empty($filtros['fecha'])) {
            $this->where('turnos.fecha', $filtros['fecha']);
        }
        if (!empty($filtros['franja'])) {
            if ($filtros['franja'] === 'manana') {
                $this->where('turnos.horario <', '12:00:00');
            } elseif ($filtros['franja'] === 'tarde') {
                $this->where('turnos.horario >=', '12:00:00')->where('turnos.horario <', '18:00:00');
            } elseif ($filtros['franja'] === 'noche') {
                $this->where('turnos.horario >=', '18:00:00');
            }
        }

        return $this->orderBy('turnos.fecha', 'ASC')
                    ->orderBy('turnos.horario', 'ASC')
                    ->findAll();
    }
}