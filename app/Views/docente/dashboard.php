<?php
// app/Views/docente/dashboard.php
$hora     = (int) date('G');
$saludo   = $hora < 12 ? 'Buen día' : ($hora < 20 ? 'Buenas tardes' : 'Buenas noches');
$dias     = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
$meses    = [1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fechaHoy = $dias[(int) date('w')] . ' ' . date('j') . ' de ' . $meses[(int) date('n')];

$pendientes  = (int) ($resumen['asistencias_pendientes'] ?? 0);
$cantHoy     = count($clasesHoy ?? []);
$diasLimite  = \App\Controllers\Docente\PanelDocente::DIAS_LIMITE_ASISTENCIA;
$ahora       = time();
?>
<main class="container mt-4 mb-5">

    <!-- Bienvenida -->
    <section class="pd-hero pd-reveal">
        <div class="pd-hero-fecha"><i class="bi bi-calendar-event me-1"></i><?= $fechaHoy ?></div>
        <h1 class="pd-hero-titulo"><?= $saludo ?>, <?= esc(session()->get('nombre')) ?></h1>
        <p class="pd-hero-sub">
            <?php if ($cantHoy === 0): ?>
                Hoy no tenés clases asignadas.
            <?php else: ?>
                Hoy tenés <strong class="text-white"><?= $cantHoy ?> <?= $cantHoy === 1 ? 'clase' : 'clases' ?></strong> en tu agenda.
            <?php endif; ?>
        </p>
        <div class="pd-acciones">
            <a href="<?= base_url('docente/clases') ?>" class="pd-btn-lima"><i class="bi bi-calendar3"></i> Mis clases</a>
            <a href="<?= base_url('docente/clases?ver=anteriores') ?>" class="pd-btn-glass"><i class="bi bi-clock-history"></i> Anteriores</a>
        </div>
    </section>

    <!-- Aviso de asistencias pendientes -->
    <?php if ($pendientes > 0): ?>
        <div class="pd-alerta mt-3 pd-reveal">
            <div class="pd-alerta-icono"><i class="bi bi-exclamation-lg"></i></div>
            <div class="flex-grow-1">
                <strong><?= $pendientes ?> <?= $pendientes === 1 ? 'clase' : 'clases' ?> con asistencia sin cargar</strong>
                <div class="small">Podés registrarla hasta <?= $diasLimite ?> días después de la clase.</div>
            </div>
            <a href="<?= base_url('docente/clases?ver=anteriores') ?>" class="btn btn-sm btn-dark rounded-pill px-3">Revisar</a>
        </div>
    <?php endif; ?>

    <!-- Resumen -->
    <h2 class="pd-seccion-titulo">Resumen</h2>
    <div class="row g-3">
        <div class="col-6 col-md-4">
            <div class="pd-stat pd-reveal">
                <div class="pd-stat-icono pd-icono-oscuro"><i class="bi bi-sun"></i></div>
                <div>
                    <div class="pd-stat-valor" data-contar="<?= (int) ($resumen['clases_hoy'] ?? 0) ?>"><?= (int) ($resumen['clases_hoy'] ?? 0) ?></div>
                    <div class="pd-stat-etiqueta">Clases hoy</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="pd-stat pd-reveal" style="--pd-delay: .06s">
                <div class="pd-stat-icono"><i class="bi bi-calendar-week"></i></div>
                <div>
                    <div class="pd-stat-valor" data-contar="<?= (int) ($resumen['clases_proximas'] ?? 0) ?>"><?= (int) ($resumen['clases_proximas'] ?? 0) ?></div>
                    <div class="pd-stat-etiqueta">Próximas clases</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="pd-stat pd-reveal <?= $pendientes > 0 ? 'pd-stat-alerta' : '' ?>" style="--pd-delay: .12s">
                <div class="pd-stat-icono <?= $pendientes > 0 ? 'pd-icono-ambar' : '' ?>"><i class="bi bi-clipboard-check"></i></div>
                <div>
                    <div class="pd-stat-valor" data-contar="<?= $pendientes ?>"><?= $pendientes ?></div>
                    <div class="pd-stat-etiqueta">Asistencias por cargar</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agenda de hoy -->
    <h2 class="pd-seccion-titulo">Mi agenda de hoy</h2>
    <?php if (empty($clasesHoy)): ?>
        <div class="pd-vacio pd-reveal">
            <i class="bi bi-cup-hot"></i>
            Hoy no tenés clases asignadas. ¡Disfrutá el día!
        </div>
    <?php else: ?>
        <div class="pd-agenda">
            <?php foreach ($clasesHoy as $i => $clase): ?>
                <?php
                    $inicio   = strtotime($clase['fecha'] . ' ' . $clase['horario']);
                    $empezo   = $inicio <= $ahora;
                    $sinCarga = $empezo && (int) $clase['sin_registrar'] > 0;
                    $claseItem = $empezo ? ($sinCarga ? 'pd-en-curso' : 'pd-finalizada') : '';
                ?>
                <div class="pd-agenda-item pd-reveal <?= $claseItem ?>" style="--pd-delay: <?= $i * 0.06 ?>s">
                    <div class="pd-agenda-hora">
                        <?= date('H:i', $inicio) ?>
                        <small>hs</small>
                    </div>
                    <div class="pd-agenda-info">
                        <p class="pd-agenda-titulo"><?= esc($clase['actividad']) ?></p>
                        <div class="pd-agenda-meta mb-1"><i class="bi bi-geo-alt"></i> <?= esc($clase['sucursal']) ?></div>
                        <span class="pd-chip"><i class="bi bi-people"></i> <?= (int) $clase['inscriptos'] ?>/<?= (int) $clase['capacidad'] ?></span>
                        <?php if ($sinCarga): ?>
                            <span class="pd-chip pd-chip-ambar"><i class="bi bi-clipboard"></i> Cargar asistencia</span>
                        <?php elseif ($empezo): ?>
                            <span class="pd-chip pd-chip-gris"><i class="bi bi-check2"></i> Iniciada</span>
                        <?php else: ?>
                            <span class="pd-chip pd-chip-oscuro"><i class="bi bi-hourglass-split"></i> Próxima</span>
                        <?php endif; ?>
                    </div>
                    <a href="<?= base_url('docente/clases/' . $clase['id_turno']) ?>" class="pd-btn-circulo" title="Ver alumnos" aria-label="Ver alumnos de <?= esc($clase['actividad']) ?>">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Accesos -->
    <h2 class="pd-seccion-titulo">Accesos</h2>
    <div class="row g-3">
        <div class="col-6">
            <a href="<?= base_url('docente/clases') ?>" class="pd-tile pd-reveal">
                <i class="bi bi-arrow-right pd-tile-flecha"></i>
                <div class="pd-tile-icono"><i class="bi bi-calendar3"></i></div>
                <h3 class="pd-tile-titulo">Mis clases</h3>
                <p class="pd-tile-texto">Cronograma completo, historial y registro de asistencias.</p>
            </a>
        </div>
        <div class="col-6">
            <a href="<?= base_url('perfil') ?>" class="pd-tile pd-reveal" style="--pd-delay: .06s">
                <i class="bi bi-arrow-right pd-tile-flecha"></i>
                <div class="pd-tile-icono"><i class="bi bi-person-circle"></i></div>
                <h3 class="pd-tile-titulo">Mi perfil</h3>
                <p class="pd-tile-texto">Datos de contacto, especialidad y contraseña.</p>
            </a>
        </div>
    </div>
</main>
