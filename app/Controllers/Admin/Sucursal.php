<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\SucursalModel;
use App\Models\TurnoModel;

class Sucursal extends BaseController
{
    // Muestra la lista de sucursales y el formulario para crear una nueva
    public function index()
    {
        

        $model = new SucursalModel();
        // Traemos todas las sucursales de la base de datos
        $data['sucursales'] = $model->findAll();

        return view('templates/header')
             . view('admin/sucursales', $data)
             . view('templates/footer');
    }

    // Procesa el formulario para guardar una nueva sucursal
    public function guardar()
    {
        $model = new SucursalModel();
        
        $data = [
            'nombre'    => $this->request->getPost('nombre'),
            'direccion' => $this->request->getPost('direccion'),
            'telefono'  => $this->request->getPost('telefono'),
        ];
       
        // Validar que el nombre de la sucursal no exista
        $nombre = $this->request->getPost('nombre');
        if ($model->where('nombre', $nombre)->first()) {
            session()->setFlashdata('error', 'Ya existe una sucursal con este nombre.');
            return redirect()->back()->withInput();
        }

        $model->insert($data);
        
        session()->setFlashdata('success', 'Sucursal agregada exitosamente.');
        return redirect()->to('/admin/sucursales');
    }

    // Elimina una sucursal por su ID
    public function eliminar($id)
    {
        $turnoModel = new TurnoModel();
        if ($turnoModel->where('id_sucursal', $id)->countAllResults() > 0) {
            session()->setFlashdata('error', 'No se puede eliminar la sucursal porque tiene turnos asociados.');
            return redirect()->to('/admin/sucursales');
        }
        $model = new SucursalModel();
        $model->delete($id);
        
        session()->setFlashdata('success', 'Sucursal eliminada.');
        return redirect()->to('/admin/sucursales');
    }

    // Muestra el formulario para editar
    public function editar($id)
    {
        $model = new SucursalModel();
        $sucursal = $model->find($id);

        if (!$sucursal) {
            session()->setFlashdata('error', 'La sucursal no existe.');
            return redirect()->to('/admin/sucursales');
        }

        $data['sucursal'] = $sucursal;

        return view('templates/header')
             . view('admin/editar_sucursal', $data)
             . view('templates/footer');
    }

    // Procesa la modificacion en la base de datos
    public function actualizar($id)
    {
        $model = new SucursalModel();
        
        $data = [
            'nombre'    => $this->request->getPost('nombre'),
            'direccion' => $this->request->getPost('direccion'),
            'telefono'  => $this->request->getPost('telefono'),
        ];
        
        $nombre = $this->request->getPost('nombre');
        if ($model->where('nombre', $nombre)->where('id_sucursal !=', $id)->first()) {
            session()->setFlashdata('error', 'Ya existe otra sucursal con este nombre.');
            return redirect()->back()->withInput();
        }

        $model->update($id, $data);
        
        session()->setFlashdata('success', 'Sucursal modificada correctamente.');
        return redirect()->to('/admin/sucursales');
    }
}