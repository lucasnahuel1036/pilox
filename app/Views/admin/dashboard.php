<?php
// app/Views/admin/dashboard.php
// Saludo según la hora y fecha en español
$hora    = (int) date('G');
$saludo  = $hora < 12 ? 'Buen día' : ($hora < 20 ? 'Buenas tardes' : 'Buenas noches');
$dias    = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
$meses   = [1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fechaHoy = $dias[(int) date('w')] . ' ' . date('j') . ' de ' . $meses[(int) date('n')];

// Métricas: [valor, etiqueta, icono, clase del icono]
$metricas = [
    [$turnos_activos ?? 0,    'Clases programadas', 'bi-calendar-check', 'pd-icono-oscuro'],
    [$total_alumnos ?? 0,     'Alumnos activos',    'bi-people',         ''],
    [$total_docentes ?? 0,    'Docentes',           'bi-person-badge',   ''],
    [$total_actividades ?? 0, 'Actividades',        'bi-activity',       ''],
    [$total_sucursales ?? 0,  'Sucursales',         'bi-building',       ''],
];

// Módulos agrupados por uso: [url, icono, título, descripción]
$operacion = [
    ['admin/turnos',  'bi-calendar3',         'Turnos',  'Grilla de clases, inscriptos y asignaciones.'],
    ['admin/abonos',  'bi-ticket-perforated', 'Abonos',  'Asigná abonos y controlá los pagos.'],
    ['admin/alumnos', 'bi-people',            'Alumnos', 'Datos, estado y altas de alumnos.'],
];
$configuracion = [
    ['admin/sucursales',  'bi-building',      'Sucursales',  'Locales de Pilox.'],
    ['admin/actividades', 'bi-activity',      'Actividades', 'Pilates, Yoga, GAP y más.'],
    ['admin/docentes',    'bi-person-badge',  'Docentes',    'Staff de profesores.'],
    ['admin/tarifas',     'bi-cash-coin',     'Tarifas',     'Precios de 4, 8 y 12 clases.'],
    ['perfil',            'bi-person-circle', 'Mi perfil',   'Tus datos de cuenta y contraseña.'],
];
?>
<main class="container mt-4 mb-5">

    <!-- Bienvenida + acciones rápidas -->
    <section class="pd-hero pd-reveal">
        <div class="pd-hero-fecha"><i class="bi bi-calendar-event me-1"></i><?= $fechaHoy ?></div>
        <h1 class="pd-hero-titulo"><?= $saludo ?>, <?= esc(session()->get('nombre')) ?></h1>
        <p class="pd-hero-sub">Panel de administración de Pilox. ¿Qué querés hacer hoy?</p>

        <div class="pd-acciones">
            <a href="<?= base_url('admin/turnos/asignar') ?>" class="pd-btn-lima"><i class="bi bi-person-check"></i> Asignar turno</a>
            <a href="<?= base_url('admin/abonos') ?>" class="pd-btn-glass"><i class="bi bi-ticket-perforated"></i> Nuevo abono</a>
            <a href="<?= base_url('admin/turnos') ?>" class="pd-btn-glass"><i class="bi bi-plus-circle"></i> Nuevo turno</a>
            <a href="<?= base_url('admin/turnos/masivo') ?>" class="pd-btn-glass"><i class="bi bi-calendar-range"></i> Generar turnos por período</a>
        </div>
    </section>

    <!-- Resumen -->
    <h2 class="pd-seccion-titulo">Resumen</h2>
    <div class="row g-3">
        <?php foreach ($metricas as $i => [$valor, $etiqueta, $icono, $claseIcono]): ?>
            <div class="<?= $i === 0 ? 'col-12' : 'col-6' ?> col-md-4 col-xl">
                <div class="pd-stat pd-reveal" style="--pd-delay: <?= $i * 0.06 ?>s">
                    <div class="pd-stat-icono <?= $claseIcono ?>"><i class="bi <?= $icono ?>"></i></div>
                    <div>
                        <div class="pd-stat-valor" data-contar="<?= (int) $valor ?>"><?= (int) $valor ?></div>
                        <div class="pd-stat-etiqueta"><?= $etiqueta ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Operación diaria (Información general primera a la izquierda) -->
    <h2 class="pd-seccion-titulo">Operación diaria</h2>
    <div class="row g-3">
        <div class="col-6 col-lg-3">
            <a href="<?= base_url('admin/informacion') ?>" class="pd-tile pd-tile-destacado pd-reveal">
                <i class="bi bi-arrow-right pd-tile-flecha"></i>
                <div class="pd-tile-icono"><i class="bi bi-bar-chart-line"></i></div>
                <h3 class="pd-tile-titulo">Información general</h3>
                <p class="pd-tile-texto">Ingresos, ocupación, asistencia y alumnos.</p>
            </a>
        </div>
        <?php foreach ($operacion as $i => [$url, $icono, $titulo, $texto]): ?>
            <div class="col-6 col-lg-3">
                <a href="<?= base_url($url) ?>" class="pd-tile pd-reveal" style="--pd-delay: <?= ($i + 1) * 0.06 ?>s">
                    <i class="bi bi-arrow-right pd-tile-flecha"></i>
                    <div class="pd-tile-icono"><i class="bi <?= $icono ?>"></i></div>
                    <h3 class="pd-tile-titulo"><?= $titulo ?></h3>
                    <p class="pd-tile-texto"><?= $texto ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Configuración del centro (incluye Mi perfil integrado) -->
    <h2 class="pd-seccion-titulo">Configuración del centro</h2>
    <div class="row g-3">
        <?php foreach ($configuracion as $i => [$url, $icono, $titulo, $texto]): ?>
            <div class="<?= $i === 4 ? 'col-12' : 'col-6' ?> col-md-4 col-xl">
                <a href="<?= base_url($url) ?>" class="pd-tile pd-reveal" style="--pd-delay: <?= $i * 0.06 ?>s">
                    <i class="bi bi-arrow-right pd-tile-flecha"></i>
                    <div class="pd-tile-icono"><i class="bi <?= $icono ?>"></i></div>
                    <h3 class="pd-tile-titulo"><?= $titulo ?></h3>
                    <p class="pd-tile-texto"><?= $texto ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</main>
