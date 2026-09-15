<!-- app/Views/templates/header.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilox - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-transparent py-3">
        <div class="container">
            <a class="navbar-brand text-primary-dark fw-bold" href="<?= base_url() ?>">Pilox</a>
            <div class="collapse navbar-collapse justify-content-center">
                <ul class="navbar-nav">
                    <li class="nav-item"><span class="nav-link">Pilates</span></li>
                    <li class="nav-item"><span class="nav-link">Yoga</span></li>
                    <li class="nav-item"><span class="nav-link">GAP</span></li>
                    <li class="nav-item"><span class="nav-link">Pilates AFA</span></li>
                </ul>
            </div>
            <div class="d-flex">
                <?php if (session()->get('is_logged_in')): ?>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-custom-outline me-2">Mi Panel</a>
                    <a href="<?= base_url('logout') ?>" class="btn btn-danger rounded-pill">Salir</a>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-custom-outline px-4 me-2">Iniciar sesión</a>
                    <a href="<?= base_url('registro') ?>" class="btn btn-custom-solid px-4">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>