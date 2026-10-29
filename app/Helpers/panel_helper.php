<?php

if (! function_exists('url_panel')) {
    /**
     * Devuelve la ruta del panel principal según el rol del usuario.
     * Si no se indica el rol, se toma el de la sesión.
     * Cualquier rol que no sea admin o docente se trata como alumno.
     */
    function url_panel(?string $rol = null): string
    {
        $rol ??= session()->get('rol');

        return match ($rol) {
            'admin'   => 'admin/dashboard',
            'docente' => 'docente/dashboard',
            default   => 'alumno/dashboard',
        };
    }
}

