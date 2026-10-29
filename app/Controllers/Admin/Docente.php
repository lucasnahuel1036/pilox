<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\UsuarioModel;

class Docente extends BaseController
{
    public function index()
    {
        $model    = new UsuarioModel();
        $busqueda = $this->request->getGet('q');

        $data['docentes'] = $model->buscarPorRol('docente', $busqueda)->paginate(10);
        $data['pager']    = $model->pager;
        $data['busqueda'] = $busqueda;

        return view('templates/header')
             . view('admin/docentes', $data)
             . view('templates/footer');
    }

    public function guardar()
    {
        $model = new UsuarioModel();   

        //Verifica que el dni sea valido
        $dni = $this->request->getPost('dni');
        if (!preg_match('/^[0-9]{7,8}$/', $dni)) {
            session()->setFlashdata('error', 'El DNI ingresado no es válido. Debe tener números sin puntos.');
            return redirect()->back()->withInput();
        }
        
        // Verificamos que el mail no exista
        if ($model->where('email', $this->request->getPost('email'))->first()) {
            session()->setFlashdata('error', 'El correo ya está registrado.');
            return redirect()->back()->withInput();
        }

        //Control minimo de caracteres contraseña
        if (strlen($this->request->getPost('password')) < 8) {
            session()->setFlashdata('error', 'La contraseña debe tener al menos 8 caracteres.');
            return redirect()->back()->withInput();
        }

        $model->insert([
            'nombre'       => $this->request->getPost('nombre'),
            'apellido'     => $this->request->getPost('apellido'),
            'dni'          => $this->request->getPost('dni'),
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
            'dni'          => $this->request->getPost('dni'),
            'email'        => $this->request->getPost('email'),
            'especialidad' => $this->request->getPost('especialidad'),
            'telefono'     => $this->request->getPost('telefono'),
        ];

        // Si escribio una contraseña nueva, la actualizamos y controla cant de caracteres
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 8) {
                session()->setFlashdata('error', 'La nueva contraseña debe tener al menos 8 caracteres.');
                return redirect()->back()->withInput();
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $model->update($id, $data);
        
        session()->setFlashdata('success', 'Docente actualizado correctamente.');
        return redirect()->to('/admin/docentes');
    }

    public function eliminar($id)
    {
        $turnoModel = new \App\Models\TurnoModel();
        if ($turnoModel->where('id_docente', $id)->countAllResults() > 0) {
            session()->setFlashdata('error', 'No se puede eliminar el docente porque tiene turnos asociados.');
            return redirect()->to('/admin/docentes');
        }
        $model = new UsuarioModel();
        $model->delete($id);
        
        session()->setFlashdata('success', 'Docente eliminado.');
        return redirect()->to('/admin/docentes');
    }
}