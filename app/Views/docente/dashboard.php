<main class="container mt-5 mb-5">
    <div class="mb-4">
        <h2 class="text-primary-dark fw-bold mb-0">Panel de Docente</h2>
        <p class="text-muted small">Hola, <?= esc(session()->get('nombre')) ?></p>
    </div>

    <?php if (($resumen['asistencias_pendientes'] ?? 0) > 0): ?>
        <div class="alert alert-warning rounded-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong>Tenés <?= $resumen['asistencias_pendientes'] ?> <?= $resumen['asistencias_pendientes'] === 1 ? 'clase realizada' : 'clases realizadas' ?> con asistencia pendiente de registro.</strong>
                <span class="small d-block">Recordá que podés cargarla hasta <?= \App\Controllers\Docente\PanelDocente::DIAS_LIMITE_ASISTENCIA ?> días después de la clase.</span>
            </div>
            <a href="<?= base_url('docente/clases?ver=anteriores') ?>" class="btn btn-sm btn-dark rounded-pill px-3">Revisar clases</a>
        </div>
    <?php endif; ?>

    <!-- Tarjetas de Resumen -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3" style="background-color: #e9f2c6;">
                <h3 class="text-primary-dark fw-bold mb-0"><?= $resumen['clases_hoy'] ?? 0 ?></h3>
                <p class="text-muted small mb-0">Clases para Hoy</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3" style="background-color: #e9f2c6;">
                <h3 class="text-primary-dark fw-bold mb-0"><?= $resumen['clases_proximas'] ?? 0 ?></h3>
                <p class="text-muted small mb-0">Próximas Clases Asignadas</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3" style="background-color: #e9f2c6;">
                <h3 class="text-primary-dark fw-bold mb-0"><?= $resumen['asistencias_pendientes'] ?? 0 ?></h3>
                <p class="text-muted small mb-0">Asistencias por Cargar</p>
            </div>
        </div>
    </div>

    <!-- Agenda de Hoy -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-primary-dark mb-0">🕒 Mi Agenda de Hoy (<?= date('d/m/Y') ?>)</h5>
            </div>

            <?php if (empty($clasesHoy)): ?>
                <div class="alert alert-light text-center text-muted mb-0" role="alert">
                    Hoy no tenés clases asignadas.
                </div>
            <?php else: ?>
                <?= view('docente/tabla_clases', ['clases' => $clasesHoy]) ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">📅 Mis Clases</h5>
                    <p class="small text-muted">Consultá el cronograma completo de tus próximas clases, el historial de clases dictadas y registrá asistencias.</p>
                    <a href="<?= base_url('docente/clases') ?>" class="btn btn-sm btn-custom-solid">Ver cronograma</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">👤 Mi Perfil</h5>
                    <p class="small text-muted">Actualizá tus datos de contacto, especialidad o cambiá tu contraseña de acceso.</p>
                    <a href="<?= base_url('perfil') ?>" class="btn btn-sm btn-custom-solid">Editar perfil</a>
                </div>
            </div>
        </div>
    </div>
</main>