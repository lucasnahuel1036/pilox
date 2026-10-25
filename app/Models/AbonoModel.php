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
        'clases_restantes', 'precio', 'estado_pago', 'fecha_pago', 'id_usuario'
    ];

        // Base común: une al alumno y aplica la búsqueda por nombre, apellido o DNI
    private function baseConAlumno($busqueda = '')
    {
        $this->select('abonos.*, usuarios.nombre, usuarios.apellido, usuarios.dni')
             ->join('usuarios', 'usuarios.id_usuario = abonos.id_usuario');

        $busqueda = trim((string) $busqueda);

        if ($busqueda !== '') {
            foreach (preg_split('/\s+/', $busqueda) as $palabra) {
                $this->groupStart()
                         ->like('usuarios.nombre', $palabra)
                         ->orLike('usuarios.apellido', $palabra)
                         ->orLike('usuarios.dni', $palabra)
                     ->groupEnd();
            }
        }

        return $this;
    }

    // Abonos vigentes: no vencidos y con clases disponibles
    public function vigentesConDetalles($busqueda = '')
    {
        return $this->baseConAlumno($busqueda)
                    ->where('abonos.fecha_fin >=', date('Y-m-d'))
                    ->where('abonos.clases_restantes >', 0)
                    ->orderBy('abonos.fecha_fin', 'ASC');
    }

    // Historial: abonos vencidos o agotados
    public function historialConDetalles($busqueda = '')
    {
        return $this->baseConAlumno($busqueda)
                    ->groupStart()
                        ->where('abonos.fecha_fin <', date('Y-m-d'))
                        ->orWhere('abonos.clases_restantes <=', 0)
                    ->groupEnd()
                    ->orderBy('abonos.fecha_fin', 'DESC');
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