<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\InformeModel;
use App\Models\SucursalModel;

class Informacion extends BaseController
{
    // CU18: Consultar información general
    public function index()
    {
        // Período: por defecto, el mes en curso
        $desde = $this->fechaValida($this->request->getGet('desde')) ?? date('Y-m-01');
        $hasta = $this->fechaValida($this->request->getGet('hasta')) ?? date('Y-m-t');

        // Si las fechas vienen invertidas, las ordenamos
        if ($hasta < $desde) {
            [$desde, $hasta] = [$hasta, $desde];
        }

        $idSucursal = (int) $this->request->getGet('id_sucursal');
        $informe    = new InformeModel($desde, $hasta, $idSucursal);

        $data = [
            'desde'               => $desde,
            'hasta'               => $hasta,
            'idSucursal'          => $idSucursal,
            'sucursales'          => (new SucursalModel())->findAll(),
            'ingresos'            => $informe->ingresos(),
            'ingresosMes'         => $informe->ingresosPorMes(),
            'deuda'               => $informe->deuda(),
            'ocupacion'           => $informe->ocupacionGlobal(),
            'ocupacionActividad'  => $informe->ocupacionPor('actividad'),
            'ocupacionSucursal'   => $informe->ocupacionPor('sucursal'),
            'ocupacionDia'        => $informe->ocupacionPor('dia'),
            'ocupacionFranja'     => $informe->ocupacionPor('franja'),
            'asistencia'          => $informe->asistenciaGlobal(),
            'asistenciaActividad' => $informe->asistenciaPorActividad(),
            'sinAsistencia'       => $informe->clasesSinAsistencia(),
            'reservas'            => $informe->reservas(),
            'alumnos'             => $informe->alumnos(),
        ];

        return view('templates/header')
             . view('admin/informacion', $data)
             . view('templates/footer');
    }

    // Devuelve la fecha si tiene formato AAAA-MM-DD y es real; si no, null
    private function fechaValida($valor)
    {
        $fecha = \DateTime::createFromFormat('Y-m-d', (string) $valor);

        return ($fecha && $fecha->format('Y-m-d') === $valor) ? $valor : null;
    }
}
