<?php

namespace App\Models;

/**
 * Consultas de la pantalla "Información general" (CU18).
 * No es un modelo de tabla: arma métricas con consultas de resumen.
 */
class InformeModel
{
    // Por debajo de este porcentaje de ocupación, un turno se considera "casi vacío"
    const UMBRAL_CASI_VACIO = 25;

    protected $db;
    protected $desde;
    protected $hasta;
    protected $sucursal;

    private $mesesCortos = [
        1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
        7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic',
    ];

    public function __construct($desde, $hasta, $idSucursal = null)
    {
        $this->db       = db_connect();
        $this->desde    = $desde;
        $this->hasta    = $hasta;
        $this->sucursal = $idSucursal ? (int) $idSucursal : null;
    }

    // Porcentaje con un decimal (0 si no hay base de cálculo)
    private function pct($parte, $total)
    {
        return $total > 0 ? round($parte * 100 / $total, 1) : 0;
    }

    // Condición sobre turnos (alias t): período elegido y sucursal opcional
    private function condTurnos()
    {
        $sql   = 't.fecha BETWEEN ? AND ?';
        $binds = [$this->desde, $this->hasta];

        if ($this->sucursal) {
            $sql    .= ' AND t.id_sucursal = ?';
            $binds[] = $this->sucursal;
        }

        return [$sql, $binds];
    }

    // ---------------------------------------------------------------- INGRESOS

    // Cobrado en el período (por fecha de pago) y desglose por tipo de abono
    public function ingresos()
    {
        $total = $this->db->query(
            "SELECT COALESCE(SUM(precio), 0) AS monto, COUNT(*) AS cantidad
             FROM abonos
             WHERE estado_pago = 'pagado' AND fecha_pago BETWEEN ? AND ?",
            [$this->desde, $this->hasta]
        )->getRowArray();

        $porTipo = $this->db->query(
            "SELECT clases_totales AS clases, COUNT(*) AS cantidad, COALESCE(SUM(precio), 0) AS monto
             FROM abonos
             WHERE estado_pago = 'pagado' AND fecha_pago BETWEEN ? AND ?
             GROUP BY clases_totales
             ORDER BY clases_totales",
            [$this->desde, $this->hasta]
        )->getResultArray();

        return ['total' => $total, 'por_tipo' => $porTipo];
    }

    // Cobrado por mes en los últimos 6 meses (no depende del período elegido)
    public function ingresosPorMes()
    {
        $base   = new \DateTime('first day of this month');
        $inicio = (clone $base)->modify('-5 months')->format('Y-m-d');

        $filas = $this->db->query(
            "SELECT DATE_FORMAT(fecha_pago, '%Y-%m') AS mes, SUM(precio) AS monto
             FROM abonos
             WHERE estado_pago = 'pagado' AND fecha_pago >= ?
             GROUP BY mes",
            [$inicio]
        )->getResultArray();

        $montos    = array_column($filas, 'monto', 'mes');
        $etiquetas = [];
        $valores   = [];

        for ($i = 5; $i >= 0; $i--) {
            $mes         = (clone $base)->modify("-$i months");
            $etiquetas[] = $this->mesesCortos[(int) $mes->format('n')] . ' ' . $mes->format('y');
            $valores[]   = (float) ($montos[$mes->format('Y-m')] ?? 0);
        }

        return ['etiquetas' => $etiquetas, 'valores' => $valores];
    }

    // Deuda: abonos pendientes de pago (cantidad, monto y los 10 más antiguos)
    public function deuda()
    {
        $resumen = $this->db->query(
            "SELECT COUNT(*) AS cantidad, COALESCE(SUM(precio), 0) AS monto
             FROM abonos WHERE estado_pago = 'pendiente'"
        )->getRowArray();

        $lista = $this->db->query(
            "SELECT u.nombre, u.apellido, u.dni, a.clases_totales, a.precio, a.fecha_inicio
             FROM abonos a
             JOIN usuarios u ON u.id_usuario = a.id_usuario
             WHERE a.estado_pago = 'pendiente'
             ORDER BY a.fecha_inicio ASC
             LIMIT 10"
        )->getResultArray();

        return ['resumen' => $resumen, 'lista' => $lista];
    }

    // --------------------------------------------------------------- OCUPACIÓN

    // Ocupación global del período: cupos usados sobre capacidad total
    public function ocupacionGlobal()
    {
        [$cond, $binds] = $this->condTurnos();

        $fila = $this->db->query(
            "SELECT COUNT(*) AS turnos,
                    COALESCE(SUM(t.capacidad), 0) AS capacidad,
                    COALESCE(SUM(t.capacidad - t.cupos_disponibles), 0) AS ocupados,
                    COALESCE(SUM(t.cupos_disponibles = 0), 0) AS llenos,
                    COALESCE(SUM((t.capacidad - t.cupos_disponibles) * 100 / t.capacidad < " . self::UMBRAL_CASI_VACIO . "), 0) AS casi_vacios
             FROM turnos t
             WHERE $cond",
            $binds
        )->getRowArray();

        $fila['pct'] = $this->pct($fila['ocupados'], $fila['capacidad']);

        return $fila;
    }

    // Ocupación agrupada por: 'actividad', 'sucursal', 'dia' (de la semana) o 'franja' horaria
    public function ocupacionPor($agrupar)
    {
        $definiciones = [
            'actividad' => ['JOIN actividades x ON x.id_actividad = t.id_actividad', 'x.nombre'],
            'sucursal'  => ['JOIN sucursales x ON x.id_sucursal = t.id_sucursal', 'x.nombre'],
            'dia'       => ['', 'DAYOFWEEK(t.fecha)'],
            'franja'    => ['', "CASE WHEN t.horario < '12:00:00' THEN 1 WHEN t.horario < '18:00:00' THEN 2 ELSE 3 END"],
        ];

        [$join, $expresion] = $definiciones[$agrupar];
        [$cond, $binds]     = $this->condTurnos();

        $filas = $this->db->query(
            "SELECT $expresion AS etiqueta,
                    COUNT(*) AS turnos,
                    SUM(t.capacidad) AS capacidad,
                    SUM(t.capacidad - t.cupos_disponibles) AS ocupados
             FROM turnos t $join
             WHERE $cond
             GROUP BY etiqueta
             ORDER BY etiqueta",
            $binds
        )->getResultArray();

        $dias    = [1 => 'Domingo', 2 => 'Lunes', 3 => 'Martes', 4 => 'Miércoles', 5 => 'Jueves', 6 => 'Viernes', 7 => 'Sábado'];
        $franjas = [1 => 'Mañana (hasta 12)', 2 => 'Tarde (12 a 18)', 3 => 'Noche (desde 18)'];

        foreach ($filas as &$fila) {
            if ($agrupar === 'dia') {
                $fila['orden']    = ((int) $fila['etiqueta'] + 5) % 7;   // lunes primero
                $fila['etiqueta'] = $dias[(int) $fila['etiqueta']];
            } elseif ($agrupar === 'franja') {
                $fila['etiqueta'] = $franjas[(int) $fila['etiqueta']];
            }
            $fila['pct'] = $this->pct($fila['ocupados'], $fila['capacidad']);
        }
        unset($fila);

        if ($agrupar === 'dia') {
            usort($filas, fn($a, $b) => $a['orden'] <=> $b['orden']);
        }

        return $filas;
    }

    // -------------------------------------------------------------- ASISTENCIA

    // Asistencia de las clases ya realizadas del período
    public function asistenciaGlobal()
    {
        [$cond, $binds] = $this->condTurnos();
        $binds[]        = date('Y-m-d H:i:s');

        $fila = $this->db->query(
            "SELECT COALESCE(SUM(r.asistencia = 1), 0) AS presentes,
                    COALESCE(SUM(r.asistencia = 0), 0) AS ausentes,
                    COALESCE(SUM(r.asistencia IS NULL), 0) AS sin_registrar
             FROM reservas r
             JOIN turnos t ON t.id_turno = r.id_turno
             WHERE r.estado = 'confirmada' AND $cond AND TIMESTAMP(t.fecha, t.horario) <= ?",
            $binds
        )->getRowArray();

        // El porcentaje se calcula solo sobre la asistencia ya registrada
        $fila['pct'] = $this->pct($fila['presentes'], $fila['presentes'] + $fila['ausentes']);

        return $fila;
    }

    // Asistencia por actividad (sobre clases ya realizadas con asistencia registrada)
    public function asistenciaPorActividad()
    {
        [$cond, $binds] = $this->condTurnos();
        $binds[]        = date('Y-m-d H:i:s');

        $filas = $this->db->query(
            "SELECT ac.nombre AS etiqueta,
                    COALESCE(SUM(r.asistencia = 1), 0) AS presentes,
                    COALESCE(SUM(r.asistencia = 0), 0) AS ausentes
             FROM reservas r
             JOIN turnos t ON t.id_turno = r.id_turno
             JOIN actividades ac ON ac.id_actividad = t.id_actividad
             WHERE r.estado = 'confirmada' AND $cond AND TIMESTAMP(t.fecha, t.horario) <= ?
             GROUP BY ac.id_actividad, ac.nombre
             ORDER BY ac.nombre",
            $binds
        )->getResultArray();

        foreach ($filas as &$fila) {
            $fila['pct'] = $this->pct($fila['presentes'], $fila['presentes'] + $fila['ausentes']);
        }
        unset($fila);

        return $filas;
    }

    // Clases ya realizadas con asistencia sin registrar, por docente
    public function clasesSinAsistencia()
    {
        [$cond, $binds] = $this->condTurnos();
        $binds[]        = date('Y-m-d H:i:s');

        return $this->db->query(
            "SELECT u.apellido, u.nombre, COUNT(DISTINCT t.id_turno) AS clases
             FROM turnos t
             JOIN usuarios u ON u.id_usuario = t.id_docente
             JOIN reservas r ON r.id_turno = t.id_turno AND r.estado = 'confirmada' AND r.asistencia IS NULL
             WHERE $cond AND TIMESTAMP(t.fecha, t.horario) <= ?
             GROUP BY u.id_usuario, u.apellido, u.nombre
             ORDER BY clases DESC, u.apellido",
            $binds
        )->getResultArray();
    }

    // ---------------------------------------------------------------- RESERVAS

    // Reservas de los turnos del período y porcentaje de cancelaciones
    public function reservas()
    {
        [$cond, $binds] = $this->condTurnos();

        $fila = $this->db->query(
            "SELECT COUNT(*) AS total,
                    COALESCE(SUM(r.estado = 'confirmada'), 0) AS confirmadas,
                    COALESCE(SUM(r.estado = 'cancelada'), 0) AS canceladas
             FROM reservas r
             JOIN turnos t ON t.id_turno = r.id_turno
             WHERE $cond",
            $binds
        )->getRowArray();

        $fila['pct_cancelacion'] = $this->pct($fila['canceladas'], $fila['total']);

        return $fila;
    }

    // ----------------------------------------------------------------- ALUMNOS

    // Alumnos por estado, activos sin abono vigente y abonos por vencer
    public function alumnos()
    {
        $hoy = date('Y-m-d');

        $estados   = $this->db->query(
            "SELECT estado, COUNT(*) AS cantidad FROM usuarios WHERE rol = 'alumno' GROUP BY estado"
        )->getResultArray();
        $porEstado = array_column($estados, 'cantidad', 'estado');

        $sinAbono = $this->db->query(
            "SELECT u.nombre, u.apellido, u.dni,
                    (SELECT MAX(a2.fecha_fin) FROM abonos a2 WHERE a2.id_usuario = u.id_usuario) AS ultimo_vencimiento
             FROM usuarios u
             WHERE u.rol = 'alumno' AND u.estado = 'activo'
               AND NOT EXISTS (
                   SELECT 1 FROM abonos a
                   WHERE a.id_usuario = u.id_usuario AND a.fecha_fin >= ? AND a.clases_restantes > 0
               )
             ORDER BY u.apellido, u.nombre",
            [$hoy]
        )->getResultArray();

        $porVencer = $this->db->query(
            "SELECT u.nombre, u.apellido, a.fecha_fin, a.clases_restantes
             FROM abonos a
             JOIN usuarios u ON u.id_usuario = a.id_usuario
             WHERE u.estado != 'inactivo' AND a.fecha_fin BETWEEN ? AND ? AND a.clases_restantes > 0
             ORDER BY a.fecha_fin ASC",
            [$hoy, date('Y-m-d', strtotime('+7 days'))]
        )->getResultArray();

        return [
            'activos'    => (int) ($porEstado['activo'] ?? 0),
            'morosos'    => (int) ($porEstado['moroso'] ?? 0),
            'inactivos'  => (int) ($porEstado['inactivo'] ?? 0),
            'sin_abono'  => $sinAbono,
            'por_vencer' => $porVencer,
        ];
    }
}
