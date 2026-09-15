<?php

namespace App\Controllers;

use App\Models\SucursalModel;

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

        $model->insert($data);
        
        session()->setFlashdata('success', 'Sucursal agregada exitosamente.');
        return redirect()->to('/admin/sucursales');
    }

    // Elimina una sucursal por su ID
    public function eliminar($id)
    {
        $model = new SucursalModel();
        $model->delete($id);
        
        session()->setFlashdata('success', 'Sucursal eliminada.');
        return redirect()->to('/admin/sucursales');
    }

    // Muestra el formulario para editar
    public function editar($id)
    {
        $model = new SucursalModel();
        $data['sucursal'] = $model->find($id);

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

        $model->update($id, $data);
        
        session()->setFlashdata('success', 'Sucursal modificada correctamente.');
        return redirect()->to('/admin/sucursales');
    }
}