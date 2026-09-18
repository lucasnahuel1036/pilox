<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// Rutas para mostrar las vistas
$routes->get('/', 'Home::index');
$routes->get('login', 'Auth::login');
$routes->get('registro', 'Auth::registro');


// Rutas para procesar los formularios (usan POST)
$routes->post('autenticar', 'Auth::autenticar');
$routes->post('registrar', 'Auth::registrar');

// Ruta para cerrar sesión
$routes->get('logout', 'Auth::logout');

// Ruta para dashboard admin
$routes->get('dashboard', 'Dashboard::index');

// Rutas exclusivas del Panel de Administración
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    // Ruta para dashboard admin
    $routes->get('dashboard', 'Dashboard::index');
    // ABM de Sucursales
    $routes->get('sucursales', 'Sucursal::index');
    $routes->post('sucursales/guardar', 'Sucursal::guardar');
    $routes->get('sucursales/eliminar/(:num)', 'Sucursal::eliminar/$1');
    $routes->get('sucursales/editar/(:num)', 'Sucursal::editar/$1');
    $routes->post('sucursales/actualizar/(:num)', 'Sucursal::actualizar/$1');
    // ABM de Actividades
    $routes->get('actividades', 'Actividad::index');
    $routes->post('actividades/guardar', 'Actividad::guardar');
    $routes->get('actividades/eliminar/(:num)', 'Actividad::eliminar/$1');
    $routes->get('actividades/editar/(:num)', 'Actividad::editar/$1');
    $routes->post('actividades/actualizar/(:num)', 'Actividad::actualizar/$1');
    // ABM de Docentes
    $routes->get('docentes', 'Docente::index');
    $routes->post('docentes/guardar', 'Docente::guardar');
    $routes->get('docentes/eliminar/(:num)', 'Docente::eliminar/$1');
    $routes->get('docentes/editar/(:num)', 'Docente::editar/$1');
    $routes->post('docentes/actualizar/(:num)', 'Docente::actualizar/$1');
    // ABM de Alumnos
    $routes->get('alumnos', 'Alumno::index');
    $routes->post('alumnos/guardar', 'Alumno::guardar');
    $routes->get('alumnos/eliminar/(:num)', 'Alumno::eliminar/$1');
    $routes->get('alumnos/editar/(:num)', 'Alumno::editar/$1');
    $routes->post('alumnos/actualizar/(:num)', 'Alumno::actualizar/$1');
    // ABM de Turnos
    $routes->get('turnos', 'Turno::index');
    $routes->post('turnos/guardar', 'Turno::guardar');
    $routes->get('turnos/eliminar/(:num)', 'Turno::eliminar/$1');
    $routes->get('turnos/editar/(:num)', 'Turno::editar/$1');
    $routes->post('turnos/actualizar/(:num)', 'Turno::actualizar/$1');
    // Generación Masiva de Turnos
    $routes->get('turnos/masivo', 'Turno::masivo');
    $routes->post('turnos/generar_masivo', 'Turno::generar_masivo');
});
// Alumno
$routes->get('alumno/dashboard', 'Alumno::dashboard');