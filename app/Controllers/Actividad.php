<?php

namespace App\Controllers;

use App\Models\ActividadModel;

class Actividad extends BaseController
{
    public function index()
    {
       

        $model = new ActividadModel();
        $data['actividades'] = $model->findAll();

        return view('templates/header')
             . view('admin/actividades', $data)
             . view('templates/footer');
    }

    public function guardar()
    {
        $model = new ActividadModel();
        
        $model->insert([
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
        ]);
        
        session()->setFlashdata('success', 'Actividad agregada exitosamente.');
        return redirect()->to('/admin/actividades');
    }

    public function editar($id)
    {
        $model = new ActividadModel();
        $data['actividad'] = $model->find($id);

        return view('templates/header')
             . view('admin/editar_actividad', $data)
             . view('templates/footer');
    }

    public function actualizar($id)
    {
        $model = new ActividadModel();
        
        $model->update($id, [
            'nombre'      => $this->request->getPost('nombre'),
            'descripcion' => $this->request->getPost('descripcion'),
        ]);
        
        session()->setFlashdata('success', 'Actividad actualizada correctamente.');
        return redirect()->to('/admin/actividades');
    }

    public function eliminar($id)
    {
        $model = new ActividadModel();
        $model->delete($id);
        
        session()->setFlashdata('success', 'Actividad eliminada.');
        return redirect()->to('/admin/actividades');
    }
}