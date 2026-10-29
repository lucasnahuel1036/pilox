<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Gestión de Turnos</h2>
            <p class="text-muted small">Configurá la grilla de clases y horarios</p>
        </div>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
        <a href="<?= base_url('admin/turnos/masivo') ?>" class="btn btn-success btn-sm rounded-pill px-3 me-2">
    <i class="bi bi-calendar-plus"></i> <strong>Generación por periodos</strong> 
</a>
    </div>

    <div class="row">
        <!-- Formulario para crear un nuevo turno -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Nuevo Turno</h5>
                    <form action="<?= base_url('admin/turnos/guardar') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label text-primary-dark small fw-bold">Fecha</label>
                                <input type="date" class="form-control rounded-pill px-3" name="fecha" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label text-primary-dark small fw-bold">Horario</label>
                                <input type="time" class="form-control rounded-pill px-3" name="horario" required>
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
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
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
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-secondary small">FECHA / HORA</th>
                                        <th class="text-secondary small">ACTIVIDAD</th>
                                        <th class="text-secondary small">DOCENTE / LUGAR</th>
                                        <th class="text-secondary small text-center">CUPOS</th>
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
                                            </td>
                                            <td><span class="badge bg-secondary"><?= esc($turno['actividad']) ?></span></td>
                                            <td>
                                                <div class="small fw-bold"><?= esc($turno['docente_nombre'] . ' ' . $turno['docente_apellido']) ?></div>
                                                <div class="small text-muted"><i class="bi bi-geo-alt"></i> <?= esc($turno['sucursal']) ?></div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill bg-success px-3"><?= $turno['cupos_disponibles'] ?> / <?= $turno['capacidad'] ?></span>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= base_url('admin/turnos/editar/' . $turno['id_turno']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1 mb-1">Editar</a>
                                                <form action="<?= base_url('admin/turnos/eliminar/' . $turno['id_turno']) ?>" method="POST" class="d-inline"
                                                    onsubmit="return confirm('¿Seguro que deseas eliminar este turno?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 mb-1">Eliminar</button>
                                                </form>
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