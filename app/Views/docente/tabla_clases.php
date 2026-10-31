<?php $hoy = date('Y-m-d'); ?>
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th class="text-secondary small">FECHA Y HORA</th>
                <th class="text-secondary small d-none d-md-table-cell">ACTIVIDAD</th>
                <th class="text-secondary small d-none d-md-table-cell">SUCURSAL</th>
                <th class="text-secondary small d-none d-md-table-cell">INSCRIPTOS</th>
                <th class="text-secondary small text-end">ACCIÓN</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clases as $clase): ?>
                <tr>
                    <td>
                        <div class="fw-bold text-primary-dark">
                            <?= date('d/m/Y', strtotime($clase['fecha'])) ?>
                            <?php if ($clase['fecha'] === $hoy): ?>
                                <span class="badge bg-success ms-1">Hoy</span>
                            <?php endif; ?>
                        </div>
                        <div class="small text-muted"><?= date('H:i', strtotime($clase['horario'])) ?> hs</div>
                        <div class="d-md-none mt-1">
                            <div class="d-flex align-items-center flex-wrap gap-1 mb-1">
                                <span class="badge bg-secondary"><?= esc($clase['actividad']) ?></span>
                                <span class="badge rounded-pill bg-secondary"><?= $clase['inscriptos'] ?> / <?= $clase['capacidad'] ?></span>
                                <?php if ($clase['sin_registrar'] > 0 && strtotime($clase['fecha'] . ' ' . $clase['horario']) <= time()): ?>
                                    <span class="badge bg-warning text-dark">Sin registrar</span>
                                <?php endif; ?>
                            </div>
                            <div class="small text-muted"><i class="bi bi-geo-alt"></i> <?= esc($clase['sucursal']) ?></div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell"><?= esc($clase['actividad']) ?></td>
                    <td class="d-none d-md-table-cell"><?= esc($clase['sucursal']) ?></td>
                    <td class="d-none d-md-table-cell">
                        <span class="badge rounded-pill bg-secondary px-3"><?= $clase['inscriptos'] ?> / <?= $clase['capacidad'] ?></span>
                        <?php if ($clase['sin_registrar'] > 0 && strtotime($clase['fecha'] . ' ' . $clase['horario']) <= time()): ?>
                            <span class="badge bg-warning text-dark ms-1">Sin registrar</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="<?= base_url('docente/clases/' . $clase['id_turno']) ?>" class="btn btn-sm btn-custom-solid rounded-pill px-3">Ver alumnos</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
