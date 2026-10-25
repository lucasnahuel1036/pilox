<?php
    $presentes = 0;
    $ausentes = 0;
    $sinRegistrar = 0;
    foreach ($inscriptos as $a) {
        if ($a['asistencia'] === null) {
            $sinRegistrar++;
        } elseif ((int) $a['asistencia'] === 1) {
            $presentes++;
        } else {
            $ausentes++;
        }
    }
?>
<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Alumnos Inscriptos</h2>
            <p class="text-muted small mb-0">
                <?= esc($clase['actividad']) ?> · <?= esc($clase['sucursal']) ?> ·
                <?= date('d/m/Y', strtotime($clase['fecha'])) ?> a las <?= date('H:i', strtotime($clase['horario'])) ?> hs
            </p>
        </div>
        <a href="<?= base_url('docente/clases') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver a Mis Clases</a>
    </div>

    <?php if ($estadoAsistencia === 'pendiente'): ?>
        <div class="alert alert-info rounded-4">La asistencia se podrá registrar cuando comience la clase.</div>
    <?php elseif ($estadoAsistencia === 'vencida'): ?>
        <div class="alert alert-secondary rounded-4">
            Pasaron más de <?= $diasLimite ?> días desde la clase: la asistencia queda solo de consulta.
        </div>
    <?php else: ?>
        <div class="alert alert-info rounded-4">
            Tildá a los alumnos que asistieron y guardá. Los que no tildes quedan como ausentes.
            Podés corregirlo hasta <?= $diasLimite ?> días después del inicio de la clase.
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-primary-dark mb-2 mb-md-0">
                    Inscriptos: <?= count($inscriptos) ?> de <?= $clase['capacidad'] ?>
                </h5>
                <div>
                    <span class="badge bg-success me-1">Presentes: <?= $presentes ?></span>
                    <span class="badge bg-danger me-1">Ausentes: <?= $ausentes ?></span>
                    <span class="badge bg-secondary">Sin registrar: <?= $sinRegistrar ?></span>
                </div>
            </div>

            <?php if (empty($inscriptos)): ?>
                <div class="alert alert-light text-center text-muted mb-0" role="alert">
                    Todavía no hay alumnos inscriptos en esta clase.
                </div>
            <?php else: ?>
                <?php if ($estadoAsistencia === 'habilitada'): ?>
                    <form action="<?= base_url('docente/clases/' . $clase['id_turno'] . '/asistencia') ?>" method="POST">
                        <?= csrf_field() ?>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-secondary small">#</th>
                                <th class="text-secondary small">ALUMNO</th>
                                <th class="text-secondary small">DNI</th>
                                <th class="text-secondary small">ASISTENCIA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscriptos as $i => $alumno): ?>
                                <tr>
                                    <td class="text-muted"><?= $i + 1 ?></td>
                                    <td class="fw-bold text-primary-dark"><?= esc($alumno['apellido'] . ', ' . $alumno['nombre']) ?></td>
                                    <td><?= esc($alumno['dni']) ?></td>
                                    <td>
                                        <?php if ($estadoAsistencia === 'habilitada'): ?>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="presentes[]"
                                                       value="<?= $alumno['id_reserva'] ?>" id="reserva_<?= $alumno['id_reserva'] ?>"
                                                       <?= ($alumno['asistencia'] !== null && (int) $alumno['asistencia'] === 1) ? 'checked' : '' ?>>
                                                <label class="form-check-label small" for="reserva_<?= $alumno['id_reserva'] ?>">Presente</label>
                                            </div>
                                        <?php elseif ($alumno['asistencia'] === null): ?>
                                            <span class="badge bg-secondary">Sin registrar</span>
                                        <?php elseif ((int) $alumno['asistencia'] === 1): ?>
                                            <span class="badge bg-success">Presente</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Ausente</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($estadoAsistencia === 'habilitada'): ?>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-custom-solid rounded-pill px-4">Guardar asistencia</button>
                        </div>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</main>