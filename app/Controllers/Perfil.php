<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Perfil extends BaseController
{
    public function index()
    {
        $idUsuario = session()->get('id_usuario');
        $usuario   = (new UsuarioModel())->find($idUsuario);

        if (!$usuario) {
            return redirect()->to('/logout');
        }

        $data['usuario'] = $usuario;

        return view('templates/header')
             . view('public/perfil', $data)
             . view('templates/footer');
    }

    public function actualizar()
    {
        $idUsuario = session()->get('id_usuario');
        $model     = new UsuarioModel();
        $usuario   = $model->find($idUsuario);

        if (!$usuario) {
            return redirect()->to('/logout');
        }

        $nombre   = trim((string) $this->request->getPost('nombre'));
        $apellido = trim((string) $this->request->getPost('apellido'));
        $email    = trim((string) $this->request->getPost('email'));
        $telefono = trim((string) $this->request->getPost('telefono'));

        if ($nombre === '' || $apellido === '' || $email === '') {
            session()->setFlashdata('error', 'Nombre, apellido y correo electrónico son obligatorios.');
            return redirect()->back()->withInput();
        }

        if ($model->where('email', $email)->where('id_usuario !=', $idUsuario)->first()) {
            session()->setFlashdata('error', 'El correo electrónico ya está registrado en otra cuenta.');
            return redirect()->back()->withInput();
        }

        $data = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'email'    => $email,
            'telefono' => $telefono,
        ];

        if ($usuario['rol'] === 'alumno') {
            $data['fecha_nacimiento'] = $this->request->getPost('fecha_nacimiento') ?: null;
        } elseif ($usuario['rol'] === 'docente') {
            $data['especialidad'] = trim((string) $this->request->getPost('especialidad'));
        }

        $model->update($idUsuario, $data);

        // Actualizamos los datos en la sesión activa
        session()->set([
            'nombre'   => $nombre,
            'apellido' => $apellido,
        ]);

        session()->setFlashdata('success', 'Tus datos personales fueron actualizados.');
        return redirect()->to('/perfil');
    }

    public function cambiarPassword()
    {
        $idUsuario = session()->get('id_usuario');
        $model     = new UsuarioModel();
        $usuario   = $model->find($idUsuario);

        if (!$usuario) {
            return redirect()->to('/logout');
        }

        $actual  = (string) $this->request->getPost('password_actual');
        $nueva   = (string) $this->request->getPost('password_nueva');
        $repetir = (string) $this->request->getPost('repetir_password');

        if (!password_verify($actual, $usuario['password'])) {
            session()->setFlashdata('error', 'La contraseña actual ingresada es incorrecta.');
            return redirect()->to('/perfil');
        }

        if (strlen($nueva) < 8) {
            session()->setFlashdata('error', 'La nueva contraseña debe tener al menos 8 caracteres.');
            return redirect()->to('/perfil');
        }

        if ($nueva !== $repetir) {
            session()->setFlashdata('error', 'Las nuevas contraseñas no coinciden.');
            return redirect()->to('/perfil');
        }

        $model->update($idUsuario, [
            'password' => password_hash($nueva, PASSWORD_DEFAULT),
        ]);

        session()->setFlashdata('success', 'Tu contraseña se actualizó correctamente.');
        return redirect()->to('/perfil');
    }
}

