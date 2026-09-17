<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Alumno extends BaseController
{
    public function index()
    {
       

        $model = new UsuarioModel();
        // Traemos solo a los usuarios que son alumnos
        $data['alumnos'] = $model->where('rol', 'alumno')->findAll();

        return view('templates/header')
             . view('admin/alumnos', $data)
             . view('templates/footer');
    }

    public function guardar()
    {
        $model = new UsuarioModel();
        
        $dni = $this->request->getPost('dni');
        if (!preg_match('/^[0-9]{7,8}$/', $dni)) {
            session()->setFlashdata('error', 'El DNI ingresado no es válido. Debe tener números sin puntos.');
            return redirect()->back()->withInput();
        }

        // Control contraseña cantidad de caracteres
        if (strlen($this->request->getPost('password')) < 8) {
            $session->setFlashdata('error', 'La contraseña debe tener al menos 8 caracteres.');
            return redirect()->back()->withInput();
        }

        if ($model->where('email', $this->request->getPost('email'))->first()) {
            session()->setFlashdata('error', 'El correo ya está registrado.');
            return redirect()->back()->withInput();
        }

        $model->insert([
            'nombre'           => $this->request->getPost('nombre'),
            'apellido'         => $this->request->getPost('apellido'),
            'dni'              => $this->request->getPost('dni'),
            'email'            => $this->request->getPost('email'),
            'password'         => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'telefono'         => $this->request->getPost('telefono'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento') ?: null,
            'rol'              => 'alumno',
            'estado'           => 'activo'
        ]);
        
        session()->setFlashdata('success', 'Alumno registrado exitosamente.');
        return redirect()->to('/admin/alumnos');
    }

    public function editar($id)
    {
        $model = new UsuarioModel();
        $data['alumno'] = $model->find($id);

        return view('templates/header')
             . view('admin/editar_alumno', $data)
             . view('templates/footer');
    }

    public function actualizar($id)
    {
        $model = new UsuarioModel();
        
        $data = [
            'nombre'           => $this->request->getPost('nombre'),
            'apellido'         => $this->request->getPost('apellido'),
            'dni'              => $this->request->getPost('dni'),
            'email'            => $this->request->getPost('email'),
            'telefono'         => $this->request->getPost('telefono'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento') ?: null,
            'rol'              => $this->request->getPost('rol'), // ACÁ PERMITIMOS CAMBIAR EL ROL
            'estado'           => $this->request->getPost('estado') // Y EL ESTADO
        ];

        
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 8) {
                session()->setFlashdata('error', 'La nueva contraseña debe tener al menos 8 caracteres.');
                return redirect()->back()->withInput();
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $model->update($id, $data);
        
        session()->setFlashdata('success', 'Perfil actualizado correctamente.');
        
        // Si el admin le cambió el rol a docente o admin, lo redirigimos al dashboard porque ya no es alumno
        if ($data['rol'] !== 'alumno') {
            return redirect()->to('/dashboard');
        }
        
        return redirect()->to('/admin/alumnos');
    }

    public function eliminar($id)
    {
        $model = new UsuarioModel();
        $model->delete($id);
        
        session()->setFlashdata('success', 'Alumno eliminado del sistema.');
        return redirect()->to('/admin/alumnos');
    }
}