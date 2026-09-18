<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Auth extends BaseController
{
    // Muestra la vista de login
    public function login(): string
    {
        return view('templates/header')
             . view('login')
             . view('templates/footer');
    }

    // Procesa los datos del formulario de login
    public function autenticar()
    {
        $session = session();
        $model = new UsuarioModel();
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        // Buscamos al usuario por su email
        $user = $model->where('email', $email)->first();
        
        if ($user) {
            // Verificamos si la contraseña coincide con el hash de la base de datos
            if (password_verify($password, $user['password'])) {
                // Si coincide, creamos las variables de sesión
                $ses_data = [
                    'id_usuario'   => $user['id_usuario'],
                    'nombre'       => $user['nombre'],
                    'apellido'     => $user['apellido'],
                    'rol'          => $user['rol'],
                    'is_logged_in' => TRUE
                ];
                $session->set($ses_data);
                
            
                // Redirigimos según el rol del usuario
        if ($user['rol'] == 'admin') {
            return redirect()->to('/admin/dashboard');
        } else {
            return redirect()->to('/alumno/dashboard');
                }
            } else {
                $session->setFlashdata('error', 'Contraseña incorrecta.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'El correo no está registrado.');
            return redirect()->to('/login');
        }
    }

    // Muestra la vista de registro
    public function registro(): string
    {
        return view('templates/header')
             . view('registro')
             . view('templates/footer');
    }

    // Procesa los datos del formulario de registro
    public function registrar()
    {
        $session = session();
        $model = new UsuarioModel();

        // Capturamos los datos del formulario
        $nombre = $this->request->getPost('nombre');
        $apellido = $this->request->getPost('apellido');
        $dni = $this->request->getPost('dni');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $repetir_password = $this->request->getPost('repetir_password');

        // 1. Validar que las contraseñas coincidan
        if ($password !== $repetir_password) {
            $session->setFlashdata('error', 'Las contraseñas no coinciden.');
            return redirect()->back()->withInput();
        }

        // Dni formato valido
        $dni = $this->request->getPost('dni');
        if (!preg_match('/^[0-9]{7,8}$/', $dni)) {
            session()->setFlashdata('error', 'El DNI ingresado no es válido. Debe tener números sin puntos.');
            return redirect()->back()->withInput();
        }

        // control minimo de caracteres de contraseña
        if (strlen($this->request->getPost('password')) < 8) {
            $session->setFlashdata('error', 'La contraseña debe tener al menos 8 caracteres.');
            return redirect()->back()->withInput();
        }

        // Validar que el dni no este registrado
        if ($model->where('dni', $this->request->getPost('dni'))->first()) {
        $session->setFlashdata('error', 'El DNI ya se encuentra registrado.');
        return redirect()->back()->withInput();
    }

        // Validar que el correo no exista ya en la base de datos
        if ($model->where('email', $email)->first()) {
            $session->setFlashdata('error', 'El correo ya está registrado.');
            return redirect()->back()->withInput();
        }

        // Preparar los datos para insertar (hasheando la clave)
        $data = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'dni'      => $dni,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'rol'      => 'alumno', // Forzamos el rol por seguridad
            'estado'   => 'activo'
        ];

        // Guardar en la base de datos
        $model->insert($data);

        // Redirigir al login con mensaje de éxito
        $session->setFlashdata('success', 'Cuenta creada exitosamente. Ya podés iniciar sesión.');
        return redirect()->to('/login');
    }

    // Cierra la sesión
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}