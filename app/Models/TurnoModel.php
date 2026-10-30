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
     * el nombre de la actividad, la sucursal y el docente.
     * $periodo: 'proximos' (aún no iniciados) o 'anteriores' (ya iniciados o pasados).
     * Devuelve el modelo para poder encadenar paginate() o findAll().
     */
    public function getTurnosConDetalles($periodo = 'proximos')
    {
        $ahora = date('Y-m-d H:i:s');

        $this->select('turnos.*, actividades.nombre as actividad, sucursales.nombre as sucursal, usuarios.nombre as docente_nombre, usuarios.apellido as docente_apellido')
             ->join('actividades', 'actividades.id_actividad = turnos.id_actividad')
             ->join('sucursales', 'sucursales.id_sucursal = turnos.id_sucursal')
             ->join('usuarios', 'usuarios.id_usuario = turnos.id_docente');

        if ($periodo === 'anteriores') {
            return $this->where("TIMESTAMP(turnos.fecha, turnos.horario) <= '" . $ahora . "'", null, false)
                        ->orderBy('turnos.fecha', 'DESC')
                        ->orderBy('turnos.horario', 'DESC');
        }

        return $this->where("TIMESTAMP(turnos.fecha, turnos.horario) > '" . $ahora . "'", null, false)
                    ->orderBy('turnos.fecha', 'ASC')
                    ->orderBy('turnos.horario', 'ASC');
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
        
    /**
    * Clases de un docente. $periodo: 'proximas' (de hoy en adelante) o 'anteriores'.
    * Devuelve el modelo para poder encadenar paginate() o findAll().
    */
    public function getClasesDeDocente($idDocente, $periodo = 'proximas')
    {
            $this->select('turnos.*, actividades.nombre as actividad, sucursales.nombre as sucursal, (turnos.capacidad - turnos.cupos_disponibles) as inscriptos, (SELECT COUNT(*) FROM reservas WHERE reservas.id_turno = turnos.id_turno AND reservas.estado = \'confirmada\' AND reservas.asistencia IS NULL) as sin_registrar', false)
             ->join('actividades', 'actividades.id_actividad = turnos.id_actividad')
             ->join('sucursales', 'sucursales.id_sucursal = turnos.id_sucursal')
             ->where('turnos.id_docente', $idDocente);

        if ($periodo === 'anteriores') {
            return $this->where('turnos.fecha <', date('Y-m-d'))
                        ->orderBy('turnos.fecha', 'DESC')
                        ->orderBy('turnos.horario', 'DESC');
        }

        return $this->where('turnos.fecha >=', date('Y-m-d'))
                    ->orderBy('turnos.fecha', 'ASC')
                    ->orderBy('turnos.horario', 'ASC');
    }

    // Una clase puntual, solo si pertenece al docente indicado
    public function getClaseDeDocente($idTurno, $idDocente)
    {
        return $this->select('turnos.*, actividades.nombre as actividad, sucursales.nombre as sucursal')
                    ->join('actividades', 'actividades.id_actividad = turnos.id_actividad')
                    ->join('sucursales', 'sucursales.id_sucursal = turnos.id_sucursal')
                    ->where('turnos.id_turno', $idTurno)
                    ->where('turnos.id_docente', $idDocente)
                    ->first();
    }

    // Resumen de métricas para el panel principal del docente
    public function getResumenDeDocente($idDocente, $diasLimiteAsistencia = 7)
    {
        $hoy   = date('Y-m-d');
        $ahora = date('Y-m-d H:i:s');
        $desde = date('Y-m-d H:i:s', strtotime("-{$diasLimiteAsistencia} days"));

        $clasesHoy = $this->db->table('turnos')
                              ->where('id_docente', $idDocente)
                              ->where('fecha', $hoy)
                              ->countAllResults();

        $clasesProximas = $this->db->table('turnos')
                                   ->where('id_docente', $idDocente)
                                   ->where('fecha >=', $hoy)
                                   ->countAllResults();

        // Clases ya iniciadas dentro de la ventana habilitada que aún tienen alumnos sin asistencia registrada
        $pendientes = $this->db->query(
            "SELECT COUNT(DISTINCT t.id_turno) AS total
             FROM turnos t
             JOIN reservas r ON r.id_turno = t.id_turno AND r.estado = 'confirmada' AND r.asistencia IS NULL
             WHERE t.id_docente = ?
               AND TIMESTAMP(t.fecha, t.horario) BETWEEN ? AND ?",
            [$idDocente, $desde, $ahora]
        )->getRowArray();

        return [
            'clases_hoy'             => $clasesHoy,
            'clases_proximas'        => $clasesProximas,
            'asistencias_pendientes' => (int) ($pendientes['total'] ?? 0),
        ];
    }

        // Un turno puntual con los nombres de actividad, sucursal y docente
    public function getTurnoConDetalles($idTurno)
    {
        return $this->select('turnos.*, actividades.nombre as actividad, sucursales.nombre as sucursal, usuarios.nombre as docente_nombre, usuarios.apellido as docente_apellido')
                    ->join('actividades', 'actividades.id_actividad = turnos.id_actividad')
                    ->join('sucursales', 'sucursales.id_sucursal = turnos.id_sucursal')
                    ->join('usuarios', 'usuarios.id_usuario = turnos.id_docente')
                    ->where('turnos.id_turno', $idTurno)
                    ->first();
    }

    // Turnos futuros que coinciden con actividad, sucursal y horario dentro de un rango de fechas
    public function getTurnosParaAsignacion($idActividad, $idSucursal, $horario, $fechaDesde, $fechaHasta)
    {
        $ahora = date('Y-m-d H:i:s');

        return $this->where('id_actividad', $idActividad)
                    ->where('id_sucursal', $idSucursal)
                    ->where('horario', $horario)
                    ->where('fecha >=', $fechaDesde)
                    ->where('fecha <=', $fechaHasta)
                    ->where("TIMESTAMP(fecha, horario) > '" . $ahora . "'", null, false)
                    ->orderBy('fecha', 'ASC')
                    ->orderBy('horario', 'ASC')
                    ->findAll();
    }
}
