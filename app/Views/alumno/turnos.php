<?php
$filtrosActivos = array_filter($filtros ?? [], fn($v) => $v !== null && $v !== '');
$hayFiltros     = !empty($filtrosActivos);
?>
<main class="container mt-3 mt-md-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Turnos Disponibles</h2>
            <p class="text-muted small mb-0">Elegí una clase de la lista y reservá tu lugar</p>
        </div>
        <a href="<?= base_url('alumno/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
    </div>

    <!-- Estado del abono -->
    <?php if (!$abono): ?>
        <div class="alert alert-warning rounded-4 py-2 px-3 mb-3">No tenés un abono vigente con clases disponibles. Consultá en la administración para poder reservar.</div>
    <?php elseif ($abono['estado_pago'] !== 'pagado'): ?>
        <div class="alert alert-warning rounded-4 py-2 px-3 mb-3">Tu abono tiene el pago pendiente. Regularizalo para poder reservar.</div>
    <?php else: ?>
        <div class="alert alert-success rounded-4 py-2 px-3 mb-3 small">
            Te quedan <strong><?= $abono['clases_restantes'] ?></strong> clases, válidas hasta el <?= date('d/m/Y', strtotime($abono['fecha_fin'])) ?>.
        </div>
    <?php endif; ?>

    <!-- Barra compacta en celular para desplegar filtros -->
    <div class="d-flex d-md-none justify-content-between align-items-center mb-2">
        <span class="small text-muted fw-semibold">
            <?= count($turnos) ?> <?= count($turnos) === 1 ? 'turno disponible' : 'turnos disponibles' ?>
        </span>
        <div class="d-flex align-items-center gap-2">
            <?php if ($hayFiltros): ?>
                <a href="<?= base_url('alumno/turnos') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1 small">
                    Limpiar
                </a>
            <?php endif; ?>
            <button class="btn btn-sm <?= $hayFiltros ? 'btn-custom-solid' : 'btn-custom-outline' ?> rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseFiltrosTurnos"
                    aria-expanded="<?= $hayFiltros ? 'true' : 'false' ?>"
                    aria-controls="collapseFiltrosTurnos">
                <i class="bi bi-sliders"></i>
                <span>Filtrar</span>
                <?php if ($hayFiltros): ?>
                    <span class="badge rounded-pill bg-light text-dark ms-1"><?= count($filtrosActivos) ?></span>
                <?php endif; ?>
            </button>
        </div>
    </div>

    <!-- Filtros (desplegable en celular, siempre visible en escritorio) -->
    <div class="collapse d-md-block <?= $hayFiltros ? 'show' : '' ?>" id="collapseFiltrosTurnos">
        <div class="card border-0 shadow-sm rounded-4 mb-3 mb-md-4 pd-reveal" style="background-color: #F8F9F5;">
            <div class="card-body p-3 p-md-4">
                <form action="<?= base_url('alumno/turnos') ?>" method="GET" class="row g-2 g-md-3 align-items-end">
                    <div class="col-6 col-md-3">
                        <label class="form-label text-primary-dark small fw-bold mb-1">Actividad</label>
                        <select class="form-select form-select-sm rounded-pill px-3 py-2" name="id_actividad">
                            <option value="">Todas</option>
                            <?php foreach ($actividades as $act): ?>
                                <option value="<?= $act['id_actividad'] ?>" <?= $filtros['id_actividad'] == $act['id_actividad'] ? 'selected' : '' ?>>
                                    <?= esc($act['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label text-primary-dark small fw-bold mb-1">Sucursal</label>
                        <select class="form-select form-select-sm rounded-pill px-3 py-2" name="id_sucursal">
                            <option value="">Todas</option>
                            <?php foreach ($sucursales as $suc): ?>
                                <option value="<?= $suc['id_sucursal'] ?>" <?= $filtros['id_sucursal'] == $suc['id_sucursal'] ? 'selected' : '' ?>>
                                    <?= esc($suc['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-primary-dark small fw-bold mb-1">Día</label>
                        <input type="date" class="form-control form-control-sm rounded-pill px-3 py-2" name="fecha" min="<?= date('Y-m-d') ?>" value="<?= esc($filtros['fecha'] ?? '') ?>">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label text-primary-dark small fw-bold mb-1">Horario</label>
                        <select class="form-select form-select-sm rounded-pill px-3 py-2" name="franja">
                            <option value="">Todos</option>
                            <option value="manana" <?= $filtros['franja'] === 'manana' ? 'selected' : '' ?>>Mañana (hasta 12)</option>
                            <option value="tarde"  <?= $filtros['franja'] === 'tarde'  ? 'selected' : '' ?>>Tarde (12 a 18)</option>
                            <option value="noche"  <?= $filtros['franja'] === 'noche'  ? 'selected' : '' ?>>Noche (desde 18)</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex gap-2 mt-2 mt-md-0">
                        <button type="submit" class="btn btn-sm btn-custom-solid rounded-pill px-3 py-2 flex-fill">Filtrar</button>
                        <a href="<?= base_url('alumno/turnos') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-2 flex-fill text-center">Limpiar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lista de turnos -->
    <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="--pd-delay: .08s">
        <div class="card-body p-3 p-md-4">
            <?php if (empty($turnos)): ?>
                <div class="alert alert-light text-center text-muted mb-0" role="alert">
                    <div>No hay turnos disponibles con esos filtros.</div>
                    <?php if ($hayFiltros): ?>
                        <a href="<?= base_url('alumno/turnos') ?>" class="btn btn-sm btn-custom-outline rounded-pill px-3 mt-2">
                            Ver todos los turnos disponibles
                        </a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-secondary small">FECHA Y HORA</th>
                                <th class="text-secondary small d-none d-md-table-cell">ACTIVIDAD</th>
                                <th class="text-secondary small d-none d-md-table-cell">SUCURSAL</th>
                                <th class="text-secondary small d-none d-md-table-cell">DOCENTE</th>
                                <th class="text-secondary small d-none d-md-table-cell">CUPOS</th>
                                <th class="text-secondary small text-end">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($turnos as $turno): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center flex-wrap gap-1">
                                            <span class="fw-bold text-primary-dark"><?= date('d/m/Y', strtotime($turno['fecha'])) ?></span>
                                            <span class="small text-muted">· <?= date('H:i', strtotime($turno['horario'])) ?> hs</span>
                                        </div>
                                        <div class="d-md-none mt-1">
                                            <div class="d-flex align-items-center flex-wrap gap-1 mb-1">
                                                <span class="badge bg-secondary"><?= esc($turno['actividad']) ?></span>
                                                <span class="badge rounded-pill bg-success"><?= $turno['cupos_disponibles'] ?> / <?= $turno['capacidad'] ?> cupos</span>
                                            </div>
                                            <div class="small text-muted">
                                                <i class="bi bi-geo-alt"></i> <?= esc($turno['sucursal']) ?> · Prof. <?= esc($turno['docente_apellido'] . ', ' . $turno['docente_nombre']) ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell"><?= esc($turno['actividad']) ?></td>
                                    <td class="d-none d-md-table-cell"><?= esc($turno['sucursal']) ?></td>
                                    <td class="d-none d-md-table-cell"><?= esc($turno['docente_apellido'] . ', ' . $turno['docente_nombre']) ?></td>
                                    <td class="d-none d-md-table-cell"><span class="badge rounded-pill bg-success px-3"><?= $turno['cupos_disponibles'] ?> / <?= $turno['capacidad'] ?></span></td>
                                    <td class="text-end text-nowrap">
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
