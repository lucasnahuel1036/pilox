<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Si no está logueado o no es admin, lo rebotamos
        if (!$session->get('is_logged_in') || $session->get('rol') !== 'admin') {
            $session->setFlashdata('error', 'Acceso denegado. Área exclusiva de administración.');
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No necesitamos hacer nada después de que cargue la página
    }
}