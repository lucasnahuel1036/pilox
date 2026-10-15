<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AlumnoFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Si no está logueado o no es alumno, lo rebotamos
        if (!$session->get('is_logged_in') || $session->get('rol') !== 'alumno') {
            $session->setFlashdata('error', 'Acceso denegado. Área exclusiva de alumnos.');
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}