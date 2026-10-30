<?php
// app/Views/alumno/dashboard.php
$hora     = (int) date('G');
$saludo   = $hora < 12 ? 'Buen día' : ($hora < 20 ? 'Buenas tardes' : 'Buenas noches');
$dias     = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
$meses    = [1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$fechaHoy = $dias[(int) date('w')] . ' ' . date('j') . ' de ' . $meses[(int) date('n')];

$hoy        = date('Y-m-d');
$estado     = null;
$proporcion = 0;
$diasRestan = null;

if ($abono) {
    if ($abono['fecha_fin'] < $hoy) {
        $estado = ['Vencido', 'pd-chip-gris', 'bi-x-circle'];
    } elseif ($abono['clases_restantes'] <= 0) {
        $estado = ['Agotado', 'pd-chip-gris', 'bi-dash-circle'];
    } else {
        $estado = ['Vigente', 'pd-chip-oscuro', 'bi-check-circle'];
        $diasRestan = (int) floor((strtotime($abono['fecha_fin']) - strtotime($hoy)) / 86400);
    }
    $proporcion = $abono['clases_totales'] > 0 ? $abono['clases_restantes'] / $abono['clases_totales'] : 0;
}

$esMoroso      = $alumno && $alumno['estado'] === 'moroso';
$puedeReservar = $abono && $estado[0] === 'Vigente' && $abono['estado_pago'] === 'pagado' && !$esMoroso;
?>
<main class="container mt-4 mb-5">

    <!-- Bienvenida -->
    <section class="pd-hero pd-reveal">
        <div class="pd-hero-fecha"><i class="bi bi-calendar-event me-1"></i><?= $fechaHoy ?></div>
        <h1 class="pd-hero-titulo"><?= $saludo ?>, <?= esc(session()->get('nombre')) ?></h1>
        <p class="pd-hero-sub">
            <?php if ($puedeReservar): ?>
                Te <?= (int) $abono['clases_restantes'] === 1 ? 'queda' : 'quedan' ?>
                <strong class="text-white"><?= (int) $abono['clases_restantes'] ?> <?= (int) $abono['clases_restantes'] === 1 ? 'clase' : 'clases' ?></strong> para reservar.
            <?php else: ?>
                Bienvenido a tu panel de Pilox.
            <?php endif; ?>
        </p>
    </section>

    <!-- Avisos -->
    <?php if ($esMoroso): ?>
        <div class="pd-alerta mt-3 pd-reveal">
            <div class="pd-alerta-icono"><i class="bi bi-exclamation-lg"></i></div>
            <div><strong>Tenés un pago pendiente.</strong>
                <div class="small">Regularizalo con la administración para volver a reservar clases.</div>
            </div>
        </div>
    <?php elseif ($abono && $estado[0] === 'Vigente' && $abono['estado_pago'] !== 'pagado'): ?>
        <div class="pd-alerta mt-3 pd-reveal">
            <div class="pd-alerta-icono"><i class="bi bi-wallet2"></i></div>
            <div><strong>Tu abono tiene el pago pendiente.</strong>
                <div class="small">Cuando se registre el pago vas a poder reservar.</div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Acción principal -->
    <div class="mt-3 pd-reveal" style="--pd-delay: .06s">
        <a href="<?= base_url('alumno/turnos') ?>" class="pd-cta">
            <div class="pd-cta-icono"><i class="bi bi-calendar-plus"></i></div>
            <div class="flex-grow-1">
                Reservar una clase
                <small><?= $puedeReservar ? 'Mirá los turnos disponibles y asegurá tu lugar' : 'Consultá los turnos disponibles' ?></small>
            </div>
            <i class="bi bi-arrow-right fs-4"></i>
        </a>
    </div>

    <!-- Mi abono -->
    <h2 class="pd-seccion-titulo">Mi abono</h2>
    <?php if (!$abono): ?>
        <div class="pd-vacio pd-reveal">
            <i class="bi bi-ticket-perforated"></i>
            Todavía no tenés un abono asignado.<br>Consultá en la administración del centro.
        </div>
    <?php else: ?>
        <div class="pd-abono pd-reveal">
            <div class="row align-items-center g-4">
                <div class="col-md-5 text-center">
                    <div class="pd-anillo">
                        <svg viewBox="0 0 140 140" aria-hidden="true">
                            <circle class="pd-anillo-fondo" cx="70" cy="70" r="58"></circle>
                            <circle class="pd-anillo-valor" cx="70" cy="70" r="58" data-anillo="<?= round($proporcion, 3) ?>"></circle>
                        </svg>
                        <div class="pd-anillo-centro">
                            <span class="pd-anillo-numero" data-contar="<?= (int) $abono['clases_restantes'] ?>"><?= (int) $abono['clases_restantes'] ?></span>
                            <span class="pd-anillo-texto">de <?= (int) $abono['clases_totales'] ?> clases</span>
                        </div>
                    </div>
                    <span class="pd-chip <?= $estado[1] ?> mt-3"><i class="bi <?= $estado[2] ?>"></i> <?= $estado[0] ?></span>
                </div>
                <div class="col-md-7">
                    <div class="pd-dato">
                        <div class="pd-dato-icono"><i class="bi bi-calendar-range"></i></div>
                        <div>
                            <div class="pd-dato-etiqueta">Vigencia</div>
                            <div class="pd-dato-valor">
                                <?= date('d/m/Y', strtotime($abono['fecha_inicio'])) ?> al <?= date('d/m/Y', strtotime($abono['fecha_fin'])) ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($diasRestan !== null): ?>
                        <div class="pd-dato">
                            <div class="pd-dato-icono"><i class="bi bi-hourglass-split"></i></div>
                            <div>
                                <div class="pd-dato-etiqueta">Vence</div>
                                <div class="pd-dato-valor">
                                    <?= $diasRestan === 0 ? 'Hoy' : ($diasRestan === 1 ? 'Mañana' : 'En ' . $diasRestan . ' días') ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="pd-dato">
                        <div class="pd-dato-icono"><i class="bi bi-wallet2"></i></div>
                        <div>
                            <div class="pd-dato-etiqueta">Pago</div>
                            <?php if ($abono['estado_pago'] === 'pagado'): ?>
                                <span class="pd-chip pd-chip-oscuro"><i class="bi bi-check2"></i> Pagado</span>
                            <?php else: ?>
                                <span class="pd-chip pd-chip-ambar"><i class="bi bi-clock"></i> Pendiente</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Accesos -->
    <h2 class="pd-seccion-titulo">Accesos</h2>
    <div class="row g-3">
        <div class="col-6">
            <a href="<?= base_url('alumno/reservas') ?>" class="pd-tile pd-reveal">
                <i class="bi bi-arrow-right pd-tile-flecha"></i>
                <div class="pd-tile-icono"><i class="bi bi-bookmark-check"></i></div>
                <h3 class="pd-tile-titulo">Mis reservas</h3>
                <p class="pd-tile-texto">Próximas clases, historial y cancelaciones.</p>
            </a>
        </div>
        <div class="col-6">
            <a href="<?= base_url('perfil') ?>" class="pd-tile pd-reveal" style="--pd-delay: .06s">
                <i class="bi bi-arrow-right pd-tile-flecha"></i>
                <div class="pd-tile-icono"><i class="bi bi-person-circle"></i></div>
                <h3 class="pd-tile-titulo">Mi perfil</h3>
                <p class="pd-tile-texto">Datos personales y contraseña.</p>
            </a>
        </div>
    </div>
</main>
