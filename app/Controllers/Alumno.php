<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Alumno extends BaseController
{
    public function index()
    {
        $model    = new UsuarioModel();
        $busqueda = $this->request->getGet('q');

        $data['alumnos']  = $model->buscarPorRol('alumno', $busqueda)->paginate(10);
        $data['pager']    = $model->pager;
        $data['busqueda'] = $busqueda;

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
            session()->setFlashdata('error', 'La contraseña debe tener al menos 8 caracteres.');
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
        // Checkea rol/estado/dni/mail
        $rol    = $this->request->getPost('rol');
        $estado = $this->request->getPost('estado');

        if (!in_array($rol, ['alumno', 'docente', 'admin'], true) || !in_array($estado, ['activo', 'inactivo', 'moroso'], true)) {
            session()->setFlashdata('error', 'Rol o estado no válido.');
            return redirect()->back()->withInput();
        }

        if (!preg_match('/^[0-9]{7,8}$/', (string) $this->request->getPost('dni'))) {
            session()->setFlashdata('error', 'El DNI ingresado no es válido. Debe tener números sin puntos.');
            return redirect()->back()->withInput();
        }

        if ($model->where('email', $this->request->getPost('email'))->where('id_usuario !=', $id)->first()) {
            session()->setFlashdata('error', 'El correo ya está registrado en otro usuario.');
            return redirect()->back()->withInput();
        }
        
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
        
        
        
        return redirect()->to('/admin/alumnos');
    }

    public function eliminar($id)
    {
        $db = db_connect();
        $tieneAbonos   = $db->table('abonos')->where('id_usuario', $id)->countAllResults() > 0;
        $tieneReservas = $db->table('reservas')->where('id_usuario', $id)->countAllResults() > 0;

        if ($tieneAbonos || $tieneReservas) {
            session()->setFlashdata('error', 'No se puede eliminar el alumno porque tiene abonos o reservas registrados. Podés pasarlo a inactivo.');
            return redirect()->to('/admin/alumnos');
        }

        $model = new UsuarioModel();
        $model->delete($id);
        
        session()->setFlashdata('success', 'Alumno eliminado del sistema.');
        return redirect()->to('/admin/alumnos');
    }

        public function dashboard()
    {
        // Los docentes siguen viendo la página provisoria hasta el Incremento 3
        if (session()->get('rol') !== 'alumno') {
            return view('templates/header')
                 . view('alumno/en_construccion')
                 . view('templates/footer');
        }

        $idUsuario = session()->get('id_usuario');

        $data['alumno'] = (new \App\Models\UsuarioModel())->find($idUsuario);
        $data['abono']  = (new \App\Models\AbonoModel())->getUltimoAbono($idUsuario);

        return view('templates/header')
             . view('alumno/dashboard', $data)
             . view('templates/footer');
    }
        
    // Búsqueda rápida de alumnos activos (JSON, para el buscador de abonos)
    public function buscar()
    {
        $q = trim((string) $this->request->getGet('q'));

        if (mb_strlen($q) < 2) {
            return $this->response->setJSON([]);
        }

        $alumnos = (new UsuarioModel())->buscarPorRol('alumno', $q)
                                       ->where('estado', 'activo')
                                       ->select('id_usuario, nombre, apellido, dni')
                                       ->findAll(10);

        return $this->response->setJSON($alumnos);
    }
}