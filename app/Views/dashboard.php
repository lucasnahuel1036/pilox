<!-- app/Views/dashboard.php -->
<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Panel de Administración</h2>
            <p class="text-muted small">Bienvenido, <?= esc(session()->get('nombre')) ?></p>
        </div>
    </div>

    <!-- Tarjetas de Métricas -->
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3" style="background-color: #F8F9F5;">
                <h3 class="text-primary-dark fw-bold mb-0"><?= $total_alumnos ?? 0 ?></h3>
                <p class="text-muted small mb-0">Alumnos Activos</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3" style="background-color: #F8F9F5;">
                <h3 class="text-primary-dark fw-bold mb-0"><?= $total_docentes ?? 0 ?></h3>
                <p class="text-muted small mb-0">Docentes</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3" style="background-color: #F8F9F5;">
                <h3 class="text-primary-dark fw-bold mb-0"><?= $total_actividades ?? 0 ?></h3>
                <p class="text-muted small mb-0">Actividades</p>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3" style="background-color: #F8F9F5;">
                <h3 class="text-primary-dark fw-bold mb-0"><?= $total_sucursales ?? 0 ?></h3>
                <p class="text-muted small mb-0">Sucursales Operativas</p>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3" style="background-color: #e9f2c6;">
                <h3 class="text-primary-dark fw-bold mb-0"><?= $turnos_activos ?? 0 ?></h3>
                <p class="text-muted small mb-0">Clases Programadas</p>
            </div>
        </div>
    </div>

    <!-- Módulos de Gestión (Incremento 1) -->
    <h4 class="text-primary-dark fw-bold border-bottom pb-2 mb-4">Módulos del Sistema</h4>
    <div class="row">
        <!-- Sucursales CU14 -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">🏢 Sucursales</h5>
                    <p class="small text-muted">Gestioná los locales de Pilox.</p>
                    <a href="<?= base_url('admin/sucursales') ?>" class="btn btn-sm btn-custom-outline">Gestionar</a>
                </div>
            </div>
        </div>
        <!-- Actividades CU13 -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">🧘‍♀️ Actividades</h5>
                    <p class="small text-muted">Pilates, Yoga, GAP y más.</p>
                    <a href="<?= base_url('admin/actividades') ?>" class="btn btn-sm btn-custom-outline">Gestionar</a>
                </div>
            </div>
        </div>
        <!-- Turnos CU15 -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">🗓️ Turnos</h5>
                    <p class="small text-muted">Configurá la grilla de horarios.</p>
                    <a href="<?= base_url('admin/turnos') ?>" class="btn btn-sm btn-custom-outline">Gestionar</a>
                </div>
            </div>
        </div>
        <!-- Alumnos CU11 -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #e9f2c6;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">👥 Alumnos</h5>
                    <p class="small text-muted">Consultá y modificá la información de los alumnos registrados.</p>
                    <a href="<?= base_url('admin/alumnos') ?>" class="btn btn-sm btn-custom-solid">Gestionar</a>
                </div>
            </div>
        </div>
        <!-- Docentes CU12 -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #e9f2c6;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">👨‍🏫 Docentes</h5>
                    <p class="small text-muted">Administrá el staff de profesores.</p>
                    <a href="<?= base_url('admin/docentes') ?>" class="btn btn-sm btn-custom-solid">Gestionar</a>
                </div>
            </div>
        </div>
    </div>
</main>