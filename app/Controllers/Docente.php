<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Docente extends BaseController
{
    public function index()
    {
      

        $model = new UsuarioModel();
        // Traemos solo a los usuarios que son docentes
        $data['docentes'] = $model->where('rol', 'docente')->findAll();

        return view('templates/header')
             . view('admin/docentes', $data)
             . view('templates/footer');
    }

    public function guardar()
    {
        $model = new UsuarioModel();
        
        // Verificamos que el mail no exista
        if ($model->where('email', $this->request->getPost('email'))->first()) {
            session()->setFlashdata('error', 'El correo ya está registrado.');
            return redirect()->back()->withInput();
        }

        $model->insert([
            'nombre'       => $this->request->getPost('nombre'),
            'apellido'     => $this->request->getPost('apellido'),
            'email'        => $this->request->getPost('email'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'especialidad' => $this->request->getPost('especialidad'),
            'telefono'     => $this->request->getPost('telefono'),
            'rol'          => 'docente', // Forzamos el rol
            'estado'       => 'activo'
        ]);
        
        session()->setFlashdata('success', 'Docente agregado exitosamente.');
        return redirect()->to('/admin/docentes');
    }

    public function editar($id)
    {
        $model = new UsuarioModel();
        $data['docente'] = $model->find($id);

        return view('templates/header')
             . view('admin/editar_docente', $data)
             . view('templates/footer');
    }

    public function actualizar($id)
    {
        $model = new UsuarioModel();
        
        $data = [
            'nombre'       => $this->request->getPost('nombre'),
            'apellido'     => $this->request->getPost('apellido'),
            'email'        => $this->request->getPost('email'),
            'especialidad' => $this->request->getPost('especialidad'),
            'telefono'     => $this->request->getPost('telefono'),
        ];

        // Si escribió una contraseña nueva, la actualizamos. Si lo dejó en blanco, conserva la anterior.
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $model->update($id, $data);
        
        session()->setFlashdata('success', 'Docente actualizado correctamente.');
        return redirect()->to('/admin/docentes');
    }

    public function eliminar($id)
    {
        $model = new UsuarioModel();
        $model->delete($id);
        
        session()->setFlashdata('success', 'Docente eliminado.');
        return redirect()->to('/admin/docentes');
    }
}