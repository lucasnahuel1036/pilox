<?php

namespace App\Controllers\Docente;

use App\Controllers\BaseController;

use App\Models\TurnoModel;
use App\Models\ReservaModel;

class PanelDocente extends BaseController
{
    // Días posteriores al inicio de la clase en que se puede registrar o corregir la asistencia
    const DIAS_LIMITE_ASISTENCIA = 7;
    // Panel principal: próximas clases del docente
    public function dashboard()
    {
        $idDocente = session()->get('id_usuario');

        $data['clases'] = (new TurnoModel())->getClasesDeDocente($idDocente, 'proximas')->findAll(8);

        return view('templates/header')
             . view('docente/dashboard', $data)
             . view('templates/footer');
    }

    //  Consultar clases asignadas
    public function clases()
    {
        $idDocente = session()->get('id_usuario');
        $periodo   = ($this->request->getGet('ver') === 'anteriores') ? 'anteriores' : 'proximas';

        $model = new TurnoModel();

        $data['clases']  = $model->getClasesDeDocente($idDocente, $periodo)->paginate(10);
        $data['pager']   = $model->pager;
        $data['periodo'] = $periodo;

        return view('templates/header')
             . view('docente/clases', $data)
             . view('templates/footer');
    }

    //  Alumnos inscriptos en una clase
    public function alumnos($idTurno)
    {
        $idDocente = session()->get('id_usuario');

        // La clase debe existir y estar asignada a este docente
        $clase = (new TurnoModel())->getClaseDeDocente($idTurno, $idDocente);

        if (!$clase) {
            session()->setFlashdata('error', 'La clase no existe o no está asignada a tu usuario.');
            return redirect()->to('/docente/clases');
        }

        $data['clase']            = $clase;
        $data['inscriptos']       = (new ReservaModel())->getInscriptosDeTurno($idTurno);
        $data['estadoAsistencia'] = $this->estadoAsistencia($clase);
        $data['diasLimite']       = self::DIAS_LIMITE_ASISTENCIA;

        return view('templates/header')
             . view('docente/alumnos', $data)
             . view('templates/footer');
    }

        // CU10: Registrar asistencia
    public function registrarAsistencia($idTurno)
    {
        $idDocente = session()->get('id_usuario');
        $clase     = (new TurnoModel())->getClaseDeDocente($idTurno, $idDocente);

        if (!$clase) {
            session()->setFlashdata('error', 'La clase no existe o no está asignada a tu usuario.');
            return redirect()->to('/docente/clases');
        }

        // Solo se puede registrar dentro de la ventana de tiempo permitida
        $estado = $this->estadoAsistencia($clase);

        if ($estado === 'pendiente') {
            session()->setFlashdata('error', 'La asistencia se puede registrar una vez que comenzó la clase.');
            return redirect()->to('/docente/clases/' . $idTurno);
        }
        if ($estado === 'vencida') {
            session()->setFlashdata('error', 'Pasó el plazo de ' . self::DIAS_LIMITE_ASISTENCIA . ' días para registrar la asistencia de esta clase.');
            return redirect()->to('/docente/clases/' . $idTurno);
        }

        // Reservas que el docente marcó como presentes
        $presentes = $this->request->getPost('presentes');
        $presentes = is_array($presentes) ? array_map('intval', $presentes) : [];

        // Solo se actualizan las reservas confirmadas de ESTA clase: cualquier otro id se ignora
        $inscriptos = (new ReservaModel())->getInscriptosDeTurno($idTurno);

        if (empty($inscriptos)) {
            session()->setFlashdata('error', 'No hay alumnos inscriptos en esta clase.');
            return redirect()->to('/docente/clases/' . $idTurno);
        }

        $db = db_connect();
        $db->transBegin();

        foreach ($inscriptos as $alumno) {
            $db->table('reservas')
               ->where('id_reserva', $alumno['id_reserva'])
               ->update(['asistencia' => in_array((int) $alumno['id_reserva'], $presentes, true) ? 1 : 0]);
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            session()->setFlashdata('error', 'No se pudo registrar la asistencia. Intentá nuevamente.');
            return redirect()->to('/docente/clases/' . $idTurno);
        }

        $db->transCommit();

        session()->setFlashdata('success', 'Asistencia registrada correctamente.');
        return redirect()->to('/docente/clases/' . $idTurno);
    }

    // Estado de la ventana de asistencia: pendiente (no empezó), habilitada o vencida
    private function estadoAsistencia($clase)
    {
        $inicio = strtotime($clase['fecha'] . ' ' . $clase['horario']);
        $ahora  = time();

        if ($ahora < $inicio) {
            return 'pendiente';
        }
        if ($ahora > $inicio + (self::DIAS_LIMITE_ASISTENCIA * 86400)) {
            return 'vencida';
        }
        return 'habilitada';
    }
}