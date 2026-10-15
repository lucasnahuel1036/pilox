<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Mis Reservas</h2>
            <p class="text-muted small">Podés cancelar hasta <?= \App\Controllers\Reserva::HORAS_CANCELACION ?> horas antes de la clase</p>
        </div>
        <a href="<?= base_url('alumno/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
    </div>

    <!-- Próximas clases -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold text-primary-dark mb-4">Próximas clases</h5>

            <?php if (empty($proximas)): ?>
                <div class="alert alert-light text-center text-muted mb-0" role="alert">
                    No tenés clases reservadas.
                    <a href="<?= base_url('alumno/turnos') ?>">Reservá un turno</a>
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
                                <th class="text-secondary small text-end">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proximas as $r): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-primary-dark"><?= date('d/m/Y', strtotime($r['fecha'])) ?></div>
                                        <div class="small text-muted"><?= date('H:i', strtotime($r['horario'])) ?> hs</div>
                                    </td>
                                    <td><?= esc($r['actividad']) ?></td>
                                    <td><?= esc($r['sucursal']) ?></td>
                                    <td><?= esc($r['docente_apellido'] . ', ' . $r['docente_nombre']) ?></td>
                                    <td class="text-end">
                                        <?php if ($r['puede_cancelar']): ?>
                                            <form action="<?= base_url('alumno/reservas/cancelar/' . $r['id_reserva']) ?>" method="POST" class="d-inline"
                                                  onsubmit="return confirm('¿Cancelar esta reserva? Se te devolverá la clase a tu abono.');">
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
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h5 class="fw-bold text-primary-dark mb-4">Historial</h5>

            <?php if (empty($historial)): ?>
                <div class="alert alert-light text-center text-muted mb-0" role="alert">
                    Todavía no tenés clases en el historial.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-secondary small">FECHA Y HORA</th>
                                <th class="text-secondary small">ACTIVIDAD</th>
                                <th class="text-secondary small">SUCURSAL</th>
                                <th class="text-secondary small">ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial as $r): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-primary-dark"><?= date('d/m/Y', strtotime($r['fecha'])) ?></div>
                                        <div class="small text-muted"><?= date('H:i', strtotime($r['horario'])) ?> hs</div>
                                    </td>
                                    <td><?= esc($r['actividad']) ?></td>
                                    <td><?= esc($r['sucursal']) ?></td>
                                    <td>
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