<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class DocenteFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Si no está logueado o no es docente, lo rebotamos
        if (!$session->get('is_logged_in') || $session->get('rol') !== 'docente') {
            $session->setFlashdata('error', 'Acceso denegado. Área exclusiva de docentes.');
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}