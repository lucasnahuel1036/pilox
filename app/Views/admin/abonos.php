<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Gestión de Abonos</h2>
            <p class="text-muted small">Asigná abonos mensuales de 4, 8 o 12 clases</p>
        </div>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
    </div>

    <div class="row">
        <!-- Formulario para asignar un abono -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Asignar Abono</h5>
                    <form action="<?= base_url('admin/abonos/guardar') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="id_usuario" class="form-label text-primary-dark small fw-bold">Alumno</label>
                            <select class="form-select rounded-pill px-3" id="id_usuario" name="id_usuario" required>
                                <option value="">Seleccioná un alumno</option>
                                <?php foreach ($alumnos as $alumno): ?>
                                    <option value="<?= $alumno['id_usuario'] ?>" <?= old('id_usuario') == $alumno['id_usuario'] ? 'selected' : '' ?>>
                                        <?= esc($alumno['apellido'] . ', ' . $alumno['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="clases" class="form-label text-primary-dark small fw-bold">Tipo de abono</label>
                            <select class="form-select rounded-pill px-3" id="clases" name="clases" required>
                                <option value="4"  <?= old('clases') == 4  ? 'selected' : '' ?>>4 clases</option>
                                <option value="8"  <?= old('clases') == 8  ? 'selected' : '' ?>>8 clases</option>
                                <option value="12" <?= old('clases') == 12 ? 'selected' : '' ?>>12 clases</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="fecha_inicio" class="form-label text-primary-dark small fw-bold">Fecha de inicio</label>
                            <input type="date" class="form-control rounded-pill px-3" id="fecha_inicio" name="fecha_inicio"
                                   value="<?= old('fecha_inicio', date('Y-m-d')) ?>" required>
                            <div class="form-text">El abono dura un mes desde esta fecha.</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Asignar Abono</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de abonos -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Abonos Registrados</h5>

                    <?php if (empty($abonos)): ?>
                        <div class="alert alert-light text-center text-muted" role="alert">
                            Todavía no hay abonos asignados.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-secondary small">ALUMNO</th>
                                        <th class="text-secondary small">CLASES</th>
                                        <th class="text-secondary small">VIGENCIA</th>
                                        <th class="text-secondary small">ESTADO</th>
                                        <th class="text-secondary small">PAGO</th>
                                        <th class="text-secondary small text-end">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $hoy = date('Y-m-d'); ?>
                                    <?php foreach ($abonos as $abono): ?>
                                        <?php
                                            if ($abono['fecha_fin'] < $hoy) {
                                                $estado = ['Vencido', 'secondary'];
                                            } elseif ($abono['clases_restantes'] <= 0) {
                                                $estado = ['Agotado', 'secondary'];
                                            } else {
                                                $estado = ['Vigente', 'success'];
                                            }
                                        ?>
                                        <tr>
                                            <td class="fw-bold text-primary-dark">
                                                <?= esc($abono['apellido'] . ', ' . $abono['nombre']) ?>
                                            </td>
                                            <td><?= $abono['clases_restantes'] ?> / <?= $abono['clases_totales'] ?></td>
                                            <td class="small">
                                                <?= date('d/m/Y', strtotime($abono['fecha_inicio'])) ?>
                                                al <?= date('d/m/Y', strtotime($abono['fecha_fin'])) ?>
                                            </td>
                                            <td><span class="badge bg-<?= $estado[1] ?>"><?= $estado[0] ?></span></td>
                                            <td>
                                                <?php if ($abono['estado_pago'] === 'pagado'): ?>
                                                    <span class="badge bg-success">Pagado</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <form action="<?= base_url('admin/abonos/pago/' . $abono['id_abono']) ?>" method="POST" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <?php if ($abono['estado_pago'] === 'pagado'): ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-3 me-1">Marcar pendiente</button>
                                                    <?php else: ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3 me-1">Marcar pagado</button>
                                                    <?php endif; ?>
                                                </form>
                                                <form action="<?= base_url('admin/abonos/eliminar/' . $abono['id_abono']) ?>" method="POST" class="d-inline"
                                                      onsubmit="return confirm('¿Seguro que deseas eliminar este abono?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>