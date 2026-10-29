<!-- app/Views/admin/dashboard.php -->
<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Panel de Administración</h2>
            <p class="text-muted small">Bienvenido, <?= esc(session()->get('nombre')) ?></p>
        </div>
    </div>

    <!-- Información general -->
    <a href="<?= base_url('admin/informacion') ?>" class="text-decoration-none">
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-3" style="background-color: #1A3C34;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1" style="color: #C5E063;">📊 Información general</h5>
                    <p class="small mb-0 text-white-50">Ingresos, ocupación, asistencia, reservas y alumnos del centro.</p>
                </div>
                <span class="btn btn-custom-light btn-sm">Ver métricas</span>
            </div>
        </div>
    </a>

    <!-- Tarjetas de Métricas -->
    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3" style="background-color: #e9f2c6;">
                <h3 class="text-primary-dark fw-bold mb-0"><?= $total_alumnos ?? 0 ?></h3>
                <p class="text-muted small mb-0">Alumnos Activos</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3" style="background-color: #e9f2c6;">
                <h3 class="text-primary-dark fw-bold mb-0"><?= $total_docentes ?? 0 ?></h3>
                <p class="text-muted small mb-0">Docentes</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3" style="background-color: #e9f2c6;">
                <h3 class="text-primary-dark fw-bold mb-0"><?= $total_actividades ?? 0 ?></h3>
                <p class="text-muted small mb-0">Actividades</p>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-3" style="background-color: #e9f2c6;">
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

    <!-- Módulos de Gestión -->
    <h4 class="text-primary-dark fw-bold border-bottom pb-2 mb-4">Módulos del Sistema</h4>
    <div class="row">
        <!-- Sucursales-->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #e9f2c6;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">🏢 Sucursales</h5>
                    <p class="small text-muted">Gestioná los locales de Pilox.</p>
                    <a href="<?= base_url('admin/sucursales') ?>" class="btn btn-sm btn-custom-solid">Gestionar</a>
                </div>
            </div>
        </div>
        <!-- Actividades -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #e9f2c6;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">🧘‍♀️ Actividades</h5>
                    <p class="small text-muted">Pilates, Yoga, GAP y más.</p>
                    <a href="<?= base_url('admin/actividades') ?>" class="btn btn-sm btn-custom-solid">Gestionar</a>
                </div>
            </div>
        </div>
        <!-- Turnos  -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #e9f2c6;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">🗓️ Turnos</h5>
                    <p class="small text-muted">Configurá la grilla de horarios.</p>
                    <a href="<?= base_url('admin/turnos') ?>" class="btn btn-sm btn-custom-solid">Gestionar</a>
                </div>
            </div>
        </div>
        <!-- Alumnos  -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #e9f2c6;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">👥 Alumnos</h5>
                    <p class="small text-muted">Consultá y modificá la información de los alumnos registrados.</p>
                    <a href="<?= base_url('admin/alumnos') ?>" class="btn btn-sm btn-custom-solid">Gestionar</a>
                </div>
            </div>
        </div>
        <!-- Docentes  -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #e9f2c6;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">👨‍🏫 Docentes</h5>
                    <p class="small text-muted">Administrá el staff de profesores.</p>
                    <a href="<?= base_url('admin/docentes') ?>" class="btn btn-sm btn-custom-solid">Gestionar</a>
                </div>
            </div>
        </div>
                <!-- Abonos -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #e9f2c6;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">🎟️ Abonos</h5>
                    <p class="small text-muted">Asigná abonos y controlá los pagos.</p>
                    <a href="<?= base_url('admin/abonos') ?>" class="btn btn-sm btn-custom-solid">Gestionar</a>
                </div>
            </div>
        </div>
                <!-- Tarifas -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #e9f2c6;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">💲 Tarifas</h5>
                    <p class="small text-muted">Definí el precio de los abonos de 4, 8 y 12 clases.</p>
                    <a href="<?= base_url('admin/tarifas') ?>" class="btn btn-sm btn-custom-solid">Configurar</a>
                </div>
            </div>
        </div>
        <!-- Mi Perfil -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm rounded-4" style="background-color: #e9f2c6;">
                <div class="card-body">
                    <h5 class="fw-bold text-primary-dark">👤 Mi Perfil</h5>
                    <p class="small text-muted">Actualizá tus datos de cuenta y tu contraseña de acceso.</p>
                    <a href="<?= base_url('perfil') ?>" class="btn btn-sm btn-custom-solid">Editar perfil</a>
                </div>
            </div>
        </div>
    </div>
</main>