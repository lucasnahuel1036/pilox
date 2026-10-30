<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Turnos Disponibles</h2>
            <p class="text-muted small">Elegí una clase y reservá tu lugar</p>
        </div>
        <a href="<?= base_url('alumno/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
    </div>

    <!-- Estado del abono -->
    <?php if (!$abono): ?>
        <div class="alert alert-warning rounded-4">No tenés un abono vigente con clases disponibles. Consultá en la administración para poder reservar.</div>
    <?php elseif ($abono['estado_pago'] !== 'pagado'): ?>
        <div class="alert alert-warning rounded-4">Tu abono tiene el pago pendiente. Regularizalo para poder reservar.</div>
    <?php else: ?>
        <div class="alert alert-success rounded-4">
            Te quedan <strong><?= $abono['clases_restantes'] ?></strong> clases, válidas hasta el <?= date('d/m/Y', strtotime($abono['fecha_fin'])) ?>.
        </div>
    <?php endif; ?>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 pd-reveal" style="background-color: #F8F9F5;">
        <div class="card-body p-4">
            <form action="<?= base_url('alumno/turnos') ?>" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-primary-dark small fw-bold">Actividad</label>
                    <select class="form-select rounded-pill px-3" name="id_actividad">
                        <option value="">Todas</option>
                        <?php foreach ($actividades as $act): ?>
                            <option value="<?= $act['id_actividad'] ?>" <?= $filtros['id_actividad'] == $act['id_actividad'] ? 'selected' : '' ?>>
                                <?= esc($act['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-primary-dark small fw-bold">Sucursal</label>
                    <select class="form-select rounded-pill px-3" name="id_sucursal">
                        <option value="">Todas</option>
                        <?php foreach ($sucursales as $suc): ?>
                            <option value="<?= $suc['id_sucursal'] ?>" <?= $filtros['id_sucursal'] == $suc['id_sucursal'] ? 'selected' : '' ?>>
                                <?= esc($suc['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-primary-dark small fw-bold">Día</label>
                    <input type="date" class="form-control rounded-pill px-3" name="fecha" value="<?= esc($filtros['fecha'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-primary-dark small fw-bold">Horario</label>
                    <select class="form-select rounded-pill px-3" name="franja">
                        <option value="">Todos</option>
                        <option value="manana" <?= $filtros['franja'] === 'manana' ? 'selected' : '' ?>>Mañana (hasta 12)</option>
                        <option value="tarde"  <?= $filtros['franja'] === 'tarde'  ? 'selected' : '' ?>>Tarde (12 a 18)</option>
                        <option value="noche"  <?= $filtros['franja'] === 'noche'  ? 'selected' : '' ?>>Noche (desde 18)</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-custom-solid rounded-pill px-3">Filtrar</button>
                    <a href="<?= base_url('alumno/turnos') ?>" class="btn btn-outline-secondary rounded-pill px-3">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de turnos -->
    <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="--pd-delay: .08s">
        <div class="card-body p-4">
            <?php if (empty($turnos)): ?>
                <div class="alert alert-light text-center text-muted mb-0" role="alert">
                    No hay turnos disponibles con esos filtros.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-secondary small">FECHA Y HORA</th>
                                <th class="text-secondary small">ACTIVIDAD</th>
                                <th class="text-secondary small">SUCURSAL</th>
                                <th class="text-secondary small">DOCENTE</th>
                                <th class="text-secondary small">CUPOS</th>
                                <th class="text-secondary small text-end">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($turnos as $turno): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-primary-dark"><?= date('d/m/Y', strtotime($turno['fecha'])) ?></div>
                                        <div class="small text-muted"><?= date('H:i', strtotime($turno['horario'])) ?> hs</div>
                                    </td>
                                    <td><?= esc($turno['actividad']) ?></td>
                                    <td><?= esc($turno['sucursal']) ?></td>
                                    <td><?= esc($turno['docente_apellido'] . ', ' . $turno['docente_nombre']) ?></td>
                                    <td><span class="badge rounded-pill bg-success px-3"><?= $turno['cupos_disponibles'] ?> / <?= $turno['capacidad'] ?></span></td>
                                    <td class="text-end">
                                        <?php if (in_array($turno['id_turno'], $reservados)): ?>
                                            <span class="badge bg-secondary">Ya reservado</span>
                                        <?php elseif (in_array($turno['fecha'] . ' ' . $turno['horario'], $horariosOcupados)): ?>
                                            <span class="badge bg-warning text-dark">Horario ocupado</span>
                                        <?php else: ?>
                                            <form action="<?= base_url('alumno/reservar/' . $turno['id_turno']) ?>" method="POST" class="d-inline"
                                                 data-confirmar="¿Confirmás la reserva? Se descontará una clase de tu abono." data-confirmar-titulo="Confirmar reserva" data-confirmar-boton="Sí, reservar">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-custom-solid rounded-pill px-3">Reservar</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>