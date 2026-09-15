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
     * Esta función especial hace JOIN con las otras tablas para traer
     * el nombre de la actividad, la sucursal y el docente, no solo sus IDs.
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
}