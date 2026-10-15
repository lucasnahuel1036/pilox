<?php

namespace App\Controllers;

use App\Models\AbonoModel;
use App\Models\UsuarioModel;

class Abono extends BaseController
{
    // Lista de abonos y formulario para asignar uno nuevo
    public function index()
    {
        $abonoModel   = new AbonoModel();
        $usuarioModel = new UsuarioModel();

        $data['abonos']  = $abonoModel->getAbonosConDetalles();
        $data['alumnos'] = $usuarioModel->where('rol', 'alumno')
                                        ->where('estado', 'activo')
                                        ->orderBy('apellido', 'ASC')
                                        ->findAll();

        return view('templates/header')
             . view('admin/abonos', $data)
             . view('templates/footer');
    }

    // Asigna un abono nuevo a un alumno
    public function guardar()
    {
        $abonoModel   = new AbonoModel();
        $usuarioModel = new UsuarioModel();

        $idUsuario   = $this->request->getPost('id_usuario');
        $clases      = (int) $this->request->getPost('clases');
        $fechaInicio = $this->request->getPost('fecha_inicio');

        // Solo existen abonos de 4, 8 o 12 clases
        if (!in_array($clases, [4, 8, 12], true)) {
            session()->setFlashdata('error', 'El abono debe ser de 4, 8 o 12 clases.');
            return redirect()->back()->withInput();
        }

        // El destinatario debe ser un alumno existente
        $alumno = $usuarioModel->where('id_usuario', $idUsuario)->where('rol', 'alumno')->first();
        if (!$alumno) {
            session()->setFlashdata('error', 'El alumno seleccionado no es válido.');
            return redirect()->back()->withInput();
        }

        // La fecha de inicio debe ser una fecha real (AAAA-MM-DD)
        $inicio = \DateTime::createFromFormat('Y-m-d', (string) $fechaInicio);
        if (!$inicio || $inicio->format('Y-m-d') !== $fechaInicio) {
            session()->setFlashdata('error', 'La fecha de inicio no es válida.');
            return redirect()->back()->withInput();
        }

        // Un alumno no puede tener dos abonos vigentes a la vez
        if ($abonoModel->getAbonoVigente($idUsuario)) {
            session()->setFlashdata('error', 'El alumno ya tiene un abono vigente.');
            return redirect()->back()->withInput();
        }

        // El abono dura un mes. Si el mes siguiente no tiene ese día
        // (ej: 31/01), usamos el último día de febrero.
        $fin = clone $inicio;
        $fin->modify('+1 month');
        if ($fin->format('d') !== $inicio->format('d')) {
            $fin->modify('last day of last month');
        }

        $abonoModel->insert([
            'fecha_inicio'     => $inicio->format('Y-m-d'),
            'fecha_fin'        => $fin->format('Y-m-d'),
            'clases_totales'   => $clases,
            'clases_restantes' => $clases,
            'estado_pago'      => 'pendiente',
            'id_usuario'       => $idUsuario,
        ]);

        session()->setFlashdata('success', 'Abono asignado correctamente.');
        return redirect()->to('/admin/abonos');
    }

    // Elimina un abono (solo si no tiene reservas asociadas)
    public function eliminar($id)
    {
        $abonoModel = new AbonoModel();

        if (db_connect()->table('reservas')->where('id_abono', $id)->countAllResults() > 0) {
            session()->setFlashdata('error', 'No se puede eliminar el abono porque tiene reservas asociadas.');
            return redirect()->to('/admin/abonos');
        }

        $abonoModel->delete($id);

        session()->setFlashdata('success', 'Abono eliminado.');
        return redirect()->to('/admin/abonos');
    }

        // Alterna el estado de pago de un abono (pagado <-> pendiente)
    public function cambiarPago($id)
    {
        $abonoModel = new AbonoModel();
        $abono = $abonoModel->find($id);

        if (!$abono) {
            session()->setFlashdata('error', 'El abono no existe.');
            return redirect()->to('/admin/abonos');
        }

        $nuevo = ($abono['estado_pago'] === 'pagado') ? 'pendiente' : 'pagado';
        $abonoModel->update($id, ['estado_pago' => $nuevo]);

        // Si se regulariza el pago el alumno morosov uelve a estar activo
        if ($nuevo === 'pagado') {
            $pendientes = $abonoModel->where('id_usuario', $abono['id_usuario'])
                                     ->where('estado_pago', 'pendiente')
                                     ->countAllResults();
            if ($pendientes === 0) {
                $usuarioModel = new UsuarioModel();
                $alumno = $usuarioModel->find($abono['id_usuario']);
                if ($alumno && $alumno['estado'] === 'moroso') {
                    $usuarioModel->update($alumno['id_usuario'], ['estado' => 'activo']);
                }
            }
        }

        session()->setFlashdata('success', 'Estado de pago actualizado a ' . $nuevo . '.');
        return redirect()->to('/admin/abonos');
    }
}