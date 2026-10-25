<?php $hoy = date('Y-m-d'); ?>
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th class="text-secondary small">FECHA Y HORA</th>
                <th class="text-secondary small">ACTIVIDAD</th>
                <th class="text-secondary small">SUCURSAL</th>
                <th class="text-secondary small">INSCRIPTOS</th>
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
                    </td>
                    <td><?= esc($clase['actividad']) ?></td>
                    <td><?= esc($clase['sucursal']) ?></td>
                    <td><span class="badge rounded-pill bg-secondary px-3"><?= $clase['inscriptos'] ?> / <?= $clase['capacidad'] ?></span>
                        <?php if ($clase['sin_registrar'] > 0 && strtotime($clase['fecha'] . ' ' . $clase['horario']) <= time()): ?>
                            <span class="badge bg-warning text-dark ms-1">Sin registrar</span>
                        <?php endif; ?></td>
                    <td class="text-end">
                        <a href="<?= base_url('docente/clases/' . $clase['id_turno']) ?>" class="btn btn-sm btn-custom-solid rounded-pill px-3">Ver alumnos</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>