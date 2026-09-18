<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilox - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-transparent py-3">
    <div class="container">
        <!-- Logo y Textos -->
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/') ?>">
            <img src="<?= base_url('assets/img/logo.jpg') ?>" alt="Logo Pilox" class="me-2 rounded-circle shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
            <div class="d-flex flex-column justify-content-center">
                <span class="text-secondary fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px; line-height: 1;">Sistema de Gestión</span>
                <span class="fw-bold text-dark fs-5" style="line-height: 1.1;">Pilox</span>
            </div>
        </a>

        <!-- Botón Hamburguesa p celular -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Abrir menú">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            
            <!-- Lista centrada -->
            <ul class="navbar-nav mx-auto mb-3 mb-lg-0 text-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/#disciplinas') ?>">Pilates</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/#disciplinas') ?>">Yoga</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/#disciplinas') ?>">GAP</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/#disciplinas') ?>">Pilates AFA</a>
                </li>
            </ul>

            <!-- Botones a la derecha -->
            <div class="d-flex justify-content-center mt-2 mt-lg-0">
                <?php if (session()->get('is_logged_in')): ?>
                    
                    <?php if (session()->get('rol') == 'admin'): ?>
                        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-custom-outline me-2">Mi Panel</a>
                    <?php else: ?>
                        <!-- Si es alumno o docente, va a este panel provisorio -->
                        <a href="<?= base_url('alumno/dashboard') ?>" class="btn btn-custom-outline me-2">Mi Panel</a>
                    <?php endif; ?>
                    
                    <a href="<?= base_url('logout') ?>" class="btn btn-danger rounded-pill">Salir</a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-custom-outline px-4 me-2">Iniciar sesión</a>
                    <a href="<?= base_url('registro') ?>" class="btn btn-custom-solid px-4">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>