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

// Rutas de Perfil (para cualquier usuario autenticado: admin, docente o alumno)
$routes->group('perfil', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Perfil::index');
    $routes->post('actualizar', 'Perfil::actualizar');
    $routes->post('password', 'Perfil::cambiarPassword');
});



// Rutas exclusivas del Panel de Administración
$routes->group('admin', ['filter' => 'admin', 'namespace' => 'App\Controllers\Admin'], function($routes) {
    // Ruta para dashboard admin
    $routes->get('dashboard', 'PanelAdmin::dashboard');
    // ABM de Sucursales
    $routes->get('sucursales', 'Sucursal::index');
    $routes->post('sucursales/guardar', 'Sucursal::guardar');
    $routes->post('sucursales/eliminar/(:num)', 'Sucursal::eliminar/$1');
    $routes->get('sucursales/editar/(:num)', 'Sucursal::editar/$1');
    $routes->post('sucursales/actualizar/(:num)', 'Sucursal::actualizar/$1');
    $routes->post('sucursales/estado/(:num)', 'Sucursal::cambiarEstado/$1');
    // ABM de Actividades
    $routes->get('actividades', 'Actividad::index');
    $routes->post('actividades/guardar', 'Actividad::guardar');
    $routes->post('actividades/eliminar/(:num)', 'Actividad::eliminar/$1');
    $routes->get('actividades/editar/(:num)', 'Actividad::editar/$1');
    $routes->post('actividades/actualizar/(:num)', 'Actividad::actualizar/$1');
    // ABM de Docentes
    $routes->get('docentes', 'Docente::index');
    $routes->post('docentes/guardar', 'Docente::guardar');
    $routes->post('docentes/eliminar/(:num)', 'Docente::eliminar/$1');
    $routes->get('docentes/editar/(:num)', 'Docente::editar/$1');
    $routes->post('docentes/actualizar/(:num)', 'Docente::actualizar/$1');
    $routes->post('docentes/estado/(:num)', 'Docente::cambiarEstado/$1');
    // ABM de Alumnos
    $routes->get('alumnos', 'Alumno::index');
    $routes->post('alumnos/guardar', 'Alumno::guardar');
    $routes->post('alumnos/eliminar/(:num)', 'Alumno::eliminar/$1');
    $routes->get('alumnos/editar/(:num)', 'Alumno::editar/$1');
    $routes->post('alumnos/actualizar/(:num)', 'Alumno::actualizar/$1');
    $routes->post('alumnos/estado/(:num)', 'Alumno::cambiarEstado/$1');
    $routes->get('alumnos/buscar', 'Alumno::buscar');
    // ABM de Turnos
    $routes->get('turnos', 'Turno::index');
    $routes->post('turnos/guardar', 'Turno::guardar');
    $routes->post('turnos/eliminar/(:num)', 'Turno::eliminar/$1');
    $routes->get('turnos/editar/(:num)', 'Turno::editar/$1');
    $routes->post('turnos/actualizar/(:num)', 'Turno::actualizar/$1');
    // Generación Masiva de Turnos
    $routes->get('turnos/masivo', 'Turno::masivo');
    $routes->post('turnos/generar_masivo', 'Turno::generar_masivo');
    // Abono alumnos
    $routes->get('abonos', 'Abono::index');
    $routes->post('abonos/guardar', 'Abono::guardar');
    $routes->post('abonos/eliminar/(:num)', 'Abono::eliminar/$1');
    $routes->post('abonos/pago/(:num)', 'Abono::cambiarPago/$1');
    // Tarifas
    $routes->get('tarifas', 'Tarifa::index');
    $routes->post('tarifas/actualizar', 'Tarifa::actualizar');
    // Informacion
    $routes->get('informacion', 'Informacion::index');
    // Asignación de turnos a alumnos y gestión de inscriptos por Admin
    $routes->get('turnos/asignar', 'Turno::asignar');
    $routes->post('turnos/guardar_asignacion', 'Turno::guardar_asignacion');
    $routes->get('turnos/inscriptos/(:num)', 'Turno::inscriptos/$1');
    $routes->post('turnos/inscribir/(:num)', 'Turno::inscribir/$1');
    $routes->post('turnos/cancelar_reserva/(:num)', 'Turno::cancelar_reserva/$1');
});

// Alumno
$routes->group('alumno', ['filter' => 'alumno', 'namespace' => 'App\Controllers\Alumno'], function($routes) {
    $routes->get('dashboard', 'PanelAlumno::dashboard');
    $routes->get('turnos', 'Reserva::turnos');
    $routes->post('reservar/(:num)', 'Reserva::reservar/$1');
    $routes->get('reservas', 'Reserva::misReservas');
    $routes->post('reservas/cancelar/(:num)', 'Reserva::cancelar/$1');
});

$routes->group('docente', ['filter' => 'docente', 'namespace' => 'App\Controllers\Docente'], function($routes) {
    $routes->get('dashboard', 'PanelDocente::dashboard');
    $routes->get('clases', 'PanelDocente::clases');
    $routes->get('clases/(:num)', 'PanelDocente::alumnos/$1');
    $routes->post('clases/(:num)/asistencia', 'PanelDocente::registrarAsistencia/$1');
});


// Pagina error 404 personalizada
$routes->set404Override(function () {
    response()->setStatusCode(404);

    return view('templates/header')
         . view('public/error404') 
         . view('templates/footer');
});