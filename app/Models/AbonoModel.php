<?php

namespace App\Models;

use CodeIgniter\Model;

class AbonoModel extends Model
{
    protected $table            = 'abonos';
    protected $primaryKey       = 'id_abono';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'fecha_inicio', 'fecha_fin', 'clases_totales',
        'clases_restantes', 'estado_pago', 'id_usuario'
    ];

    // Abonos con los datos del alumno (para el listado del admin)
    public function getAbonosConDetalles()
    {
        return $this->select('abonos.*, usuarios.nombre, usuarios.apellido, usuarios.dni')
                    ->join('usuarios', 'usuarios.id_usuario = abonos.id_usuario')
                    ->orderBy('abonos.fecha_inicio', 'DESC')
                    ->findAll();
    }

    // Abono vigente de un alumno: no vencido y con clases disponibles
    public function getAbonoVigente($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)
                    ->where('fecha_fin >=', date('Y-m-d'))
                    ->where('clases_restantes >', 0)
                    ->first();
    }
    
        // Último abono del alumno (aunque esté vencido o agotado)
    public function getUltimoAbono($idUsuario)
    {
        return $this->where('id_usuario', $idUsuario)
                    ->orderBy('fecha_inicio', 'DESC')
                    ->orderBy('id_abono', 'DESC')
                    ->first();
    }
}