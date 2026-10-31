<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Mis Reservas</h2>
            <p class="text-muted small">Podés cancelar hasta <?= \App\Controllers\Alumno\Reserva::HORAS_CANCELACION ?> horas antes de la clase</p>
        </div>
        <a href="<?= base_url('alumno/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
    </div>

    <!-- Accesos rápidos en celular -->
    <nav class="pd-nav-secciones d-md-none" aria-label="Secciones de la página">
        <a href="#seccion-proximas" class="pd-nav-principal">
            <i class="bi bi-calendar-check"></i> Próximas clases
        </a>
        <a href="#seccion-historial">
            <i class="bi bi-clock-history"></i> Historial <i class="bi bi-arrow-down-short"></i>
        </a>
    </nav>

    <!-- Próximas clases -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 pd-reveal" id="seccion-proximas">
        <div class="card-body p-3 p-md-4">
            <h5 class="fw-bold text-primary-dark mb-4">Próximas clases</h5>

            <?php if (empty($proximas)): ?>
                <div class="alert alert-light text-center text-muted mb-0" role="alert">
                    No tenés clases reservadas.
                    <a href="<?= base_url('alumno/turnos') ?>">Reservá un turno</a>
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
                                <th class="text-secondary small text-end">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proximas as $r): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-primary-dark"><?= date('d/m/Y', strtotime($r['fecha'])) ?></div>
                                        <div class="small text-muted"><?= date('H:i', strtotime($r['horario'])) ?> hs</div>
                                        <div class="d-md-none mt-1">
                                            <span class="badge bg-secondary mb-1"><?= esc($r['actividad']) ?></span>
                                            <div class="small text-muted">
                                                <i class="bi bi-geo-alt"></i> <?= esc($r['sucursal']) ?> · Prof. <?= esc($r['docente_apellido'] . ', ' . $r['docente_nombre']) ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell"><?= esc($r['actividad']) ?></td>
                                    <td class="d-none d-md-table-cell"><?= esc($r['sucursal']) ?></td>
                                    <td class="d-none d-md-table-cell"><?= esc($r['docente_apellido'] . ', ' . $r['docente_nombre']) ?></td>
                                    <td class="text-end text-nowrap">
                                        <?php if ($r['puede_cancelar']): ?>
                                            <form action="<?= base_url('alumno/reservas/cancelar/' . $r['id_reserva']) ?>" method="POST" class="d-inline"
                                                  data-confirmar="¿Cancelar esta reserva? Se te devolverá la clase a tu abono." data-confirmar-titulo="Cancelar reserva" data-confirmar-boton="Sí, cancelar" data-confirmar-tipo="peligro">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Cancelar</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="small text-muted">Ya no se puede cancelar</span>
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

    <!-- Historial -->
    <div class="card border-0 shadow-sm rounded-4 pd-reveal" id="seccion-historial" style="--pd-delay: .08s">
        <div class="card-body p-3 p-md-4">
            <h5 class="fw-bold text-primary-dark mb-4">Historial</h5>

            <?php if (empty($historial)): ?>
                <div class="alert alert-light text-center text-muted mb-0" role="alert">
                    Todavía no tenés clases en el historial.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-secondary small">FECHA Y HORA</th>
                                <th class="text-secondary small d-none d-md-table-cell">ACTIVIDAD</th>
                                <th class="text-secondary small d-none d-md-table-cell">SUCURSAL</th>
                                <th class="text-secondary small text-end text-md-start">ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial as $r): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-primary-dark"><?= date('d/m/Y', strtotime($r['fecha'])) ?></div>
                                        <div class="small text-muted"><?= date('H:i', strtotime($r['horario'])) ?> hs</div>
                                        <div class="d-md-none mt-1 small text-muted">
                                            <span class="fw-semibold text-dark"><?= esc($r['actividad']) ?></span> · <?= esc($r['sucursal']) ?>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell"><?= esc($r['actividad']) ?></td>
                                    <td class="d-none d-md-table-cell"><?= esc($r['sucursal']) ?></td>
                                    <td class="text-end text-md-start text-nowrap">
                                        <?php if ($r['estado'] === 'cancelada'): ?>
                                            <span class="badge bg-secondary">Cancelada</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Finalizada</span>
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
