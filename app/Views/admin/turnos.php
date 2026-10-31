<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Gestión de Turnos</h2>
            <p class="text-muted small">Configurá la grilla de clases y horarios</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('admin/turnos/asignar') ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="bi bi-person-check"></i> <strong>Asignar turnos recurrentes</strong>
            </a>
            <a href="<?= base_url('admin/turnos/masivo') ?>" class="btn btn-success btn-sm rounded-pill px-3">
                <i class="bi bi-calendar-plus"></i> <strong>Generar turnos por período</strong>
            </a>
            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
        </div>
    </div>

    <!-- Accesos rápidos en celular -->
    <nav class="pd-nav-secciones d-md-none" aria-label="Secciones de la página">
        <a href="#seccion-registrar" class="pd-nav-principal">
            <i class="bi bi-plus-circle"></i> Nuevo Turno
        </a>
        <a href="#seccion-listado">
            <i class="bi bi-calendar3"></i> Grilla de Clases <i class="bi bi-arrow-down-short"></i>
        </a>
    </nav>

    <div class="row">
        <!-- Formulario para crear un nuevo turno -->
        <div class="col-md-4 mb-4" id="seccion-registrar">
            <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Nuevo Turno</h5>
                    <form action="<?= base_url('admin/turnos/guardar') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label text-primary-dark small fw-bold">Fecha</label>
                                <input type="date" class="form-control rounded-pill px-3" name="fecha" min="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label text-primary-dark small fw-bold">Horario</label>
                                <input type="time" class="form-control rounded-pill px-3" name="horario" list="horariosPilox" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Actividad</label>
                            <select class="form-select rounded-pill px-3" name="id_actividad" required>
                                <option value="" disabled selected>Seleccioná una disciplina...</option>
                                <?php foreach($actividades as $act): ?>
                                    <option value="<?= $act['id_actividad'] ?>"><?= esc($act['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Docente</label>
                            <select class="form-select rounded-pill px-3" name="id_docente" required>
                                <option value="" disabled selected>Seleccioná un profesor...</option>
                                <?php foreach($docentes as $doc): ?>
                                    <option value="<?= $doc['id_usuario'] ?>"><?= esc($doc['nombre'] . ' ' . $doc['apellido']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Sucursal</label>
                            <select class="form-select rounded-pill px-3" name="id_sucursal" required>
                                <option value="" disabled selected>Seleccioná el local...</option>
                                <?php foreach($sucursales as $suc): ?>
                                    <option value="<?= $suc['id_sucursal'] ?>"><?= esc($suc['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-primary-dark small fw-bold">Capacidad (Cupos)</label>
                            <input type="number" class="form-control rounded-pill px-3" name="capacidad" min="1" max="50" required placeholder="Ej: 15">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Generar Turno</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla con el listado de turnos -->
        <div class="col-md-8" id="seccion-listado">
            <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="--pd-delay: .08s">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-primary-dark mb-2 mb-sm-0">Grilla de Clases</h5>
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link rounded-pill py-1 px-3 <?= $periodo === 'proximos' ? 'active' : '' ?>" href="<?= base_url('admin/turnos') ?>">Próximos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link rounded-pill py-1 px-3 <?= $periodo === 'anteriores' ? 'active' : '' ?>" href="<?= base_url('admin/turnos?ver=anteriores') ?>">Anteriores</a>
                            </li>
                        </ul>
                    </div>
                    
                    <?php if(empty($turnos)): ?>
                        <div class="alert alert-light text-center text-muted" role="alert">
                            <?= $periodo === 'proximos' ? 'No hay clases próximas programadas.' : 'Todavía no hay clases anteriores.' ?>
                        </div>
                    <?php else: ?>
                        <?php $hoy = date('Y-m-d'); ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-secondary small">FECHA / HORA</th>
                                        <th class="text-secondary small d-none d-md-table-cell">ACTIVIDAD</th>
                                        <th class="text-secondary small d-none d-md-table-cell">DOCENTE / LUGAR</th>
                                        <th class="text-secondary small text-center d-none d-md-table-cell">CUPOS</th>
                                        <th class="text-secondary small text-end">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($turnos as $turno): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-primary-dark">
                                                    <?= date('d/m/Y', strtotime($turno['fecha'])) ?>
                                                    <?php if ($turno['fecha'] === $hoy): ?>
                                                        <span class="badge bg-success ms-1">Hoy</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="small text-muted"><?= date('H:i', strtotime($turno['horario'])) ?> hs</div>
                                                <div class="d-md-none mt-1">
                                                    <div class="d-flex align-items-center flex-wrap gap-1 mb-1">
                                                        <span class="badge bg-secondary"><?= esc($turno['actividad']) ?></span>
                                                        <span class="badge rounded-pill bg-success"><?= $turno['cupos_disponibles'] ?> / <?= $turno['capacidad'] ?></span>
                                                    </div>
                                                    <div class="small fw-semibold"><?= esc($turno['docente_nombre'] . ' ' . $turno['docente_apellido']) ?></div>
                                                    <div class="small text-muted"><i class="bi bi-geo-alt"></i> <?= esc($turno['sucursal']) ?></div>
                                                </div>
                                            </td>
                                            <td class="d-none d-md-table-cell"><span class="badge bg-secondary"><?= esc($turno['actividad']) ?></span></td>
                                            <td class="d-none d-md-table-cell">
                                                <div class="small fw-bold"><?= esc($turno['docente_nombre'] . ' ' . $turno['docente_apellido']) ?></div>
                                                <div class="small text-muted"><i class="bi bi-geo-alt"></i> <?= esc($turno['sucursal']) ?></div>
                                            </td>
                                            <td class="text-center d-none d-md-table-cell">
                                                <span class="badge rounded-pill bg-success px-3"><?= $turno['cupos_disponibles'] ?> / <?= $turno['capacidad'] ?></span>
                                            </td>
                                            <td class="text-end text-nowrap">
                                                <div class="d-inline-flex align-items-center gap-1">
                                                    <a href="<?= base_url('admin/turnos/inscriptos/' . $turno['id_turno']) ?>"
                                                       class="btn btn-sm btn-outline-dark rounded-circle btn-icon"
                                                       title="Ver alumnos inscriptos">
                                                        <i class="bi bi-people"></i>
                                                    </a>
                                                    <?php if (strtotime($turno['fecha'] . ' ' . $turno['horario']) > time()): ?>
                                                        <a href="<?= base_url('admin/turnos/editar/' . $turno['id_turno']) ?>"
                                                           class="btn btn-sm btn-outline-primary rounded-circle btn-icon"
                                                           title="Editar turno">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <form action="<?= base_url('admin/turnos/eliminar/' . $turno['id_turno']) ?>" method="POST" class="d-inline"
                                                        data-confirmar="¿Seguro que deseas eliminar este turno?" data-confirmar-titulo="Eliminar turno" data-confirmar-boton="Sí, eliminar" data-confirmar-tipo="peligro">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle btn-icon" title="Eliminar turno">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3"><?= $pager->links() ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
