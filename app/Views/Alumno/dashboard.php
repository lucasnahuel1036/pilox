<?php
    $hoy = date('Y-m-d');
    $estado = null;
    if ($abono) {
        if ($abono['fecha_fin'] < $hoy) {
            $estado = ['Vencido', 'secondary'];
        } elseif ($abono['clases_restantes'] <= 0) {
            $estado = ['Agotado', 'secondary'];
        } else {
            $estado = ['Vigente', 'success'];
        }
    }
?>
<main class="container mt-5 mb-5">
    <div class="mb-4">
        <h2 class="text-primary-dark fw-bold mb-0">Mi Panel</h2>
        <p class="text-muted small">Bienvenido, <?= esc(session()->get('nombre')) ?></p>
    </div>

    <?php if ($alumno && $alumno['estado'] === 'moroso'): ?>
        <div class="alert alert-warning rounded-4">
            Tu cuenta tiene un pago pendiente. Regularizalo con la administración para volver a reservar clases.
        </div>
    <?php endif; ?>

    <!-- Estado del abono -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background-color: #e9f2c6;">
        <div class="card-body p-4">
            <h5 class="fw-bold text-primary-dark mb-3">🎟️ Mi Abono</h5>

            <?php if (!$abono): ?>
                <p class="text-muted mb-0">Todavía no tenés un abono asignado. Consultá en la administración del centro.</p>
            <?php else: ?>
                <div class="row align-items-center">
                    <div class="col-md-4 text-center mb-3 mb-md-0">
                        <h1 class="text-primary-dark fw-bold mb-0"><?= $abono['clases_restantes'] ?></h1>
                        <p class="text-muted small mb-0">clases disponibles de <?= $abono['clases_totales'] ?></p>
                    </div>
                    <div class="col-md-8">
                        <p class="mb-2">
                            <span class="text-muted small">Vigencia:</span>
                            <?= date('d/m/Y', strtotime($abono['fecha_inicio'])) ?>
                            al <?= date('d/m/Y', strtotime($abono['fecha_fin'])) ?>
                        </p>
                        <p class="mb-2">
                            <span class="text-muted small">Estado:</span>
                            <span class="badge bg-<?= $estado[1] ?>"><?= $estado[0] ?></span>
                        </p>
                        <p class="mb-0">
                            <span class="text-muted small">Pago:</span>
                            <?php if ($abono['estado_pago'] === 'pagado'): ?>
                                <span class="badge bg-success">Pagado</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">📅 Turnos</h5>
                    <p class="small text-muted">Consultá las clases disponibles y reservá tu lugar.</p>
                    <a href="<?= base_url('alumno/turnos') ?>" class="btn btn-sm btn-custom-solid">Ver turnos</a>
                </div>
            </div>
        </div>
                <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">✅ Mis Reservas</h5>
                    <p class="small text-muted">Revisá tus próximas clases, tu historial y cancelá si lo necesitás.</p>
                    <a href="<?= base_url('alumno/reservas') ?>" class="btn btn-sm btn-custom-solid">Ver reservas</a>
                </div>
            </div>
        </div>
    </div>
</main>