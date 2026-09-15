<main class="container mt-5 mb-5">
    <div class="card border-0 shadow-sm rounded-4 mx-auto p-4" style="max-width: 600px; background-color: #F8F9F5;">
        <h5 class="fw-bold text-primary-dark mb-4">Editar Turno</h5>
        <form action="<?= base_url('admin/turnos/actualizar/' . $turno['id_turno']) ?>" method="POST">
            
            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label text-primary-dark small fw-bold">Fecha</label>
                    <input type="date" class="form-control rounded-pill px-3" name="fecha" value="<?= $turno['fecha'] ?>" required>
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label text-primary-dark small fw-bold">Horario</label>
                    <input type="time" class="form-control rounded-pill px-3" name="horario" value="<?= $turno['horario'] ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-primary-dark small fw-bold">Actividad</label>
                <select class="form-select rounded-pill px-3" name="id_actividad" required>
                    <?php foreach($actividades as $act): ?>
                        <option value="<?= $act['id_actividad'] ?>" <?= ($act['id_actividad'] == $turno['id_actividad']) ? 'selected' : '' ?>>
                            <?= esc($act['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-primary-dark small fw-bold">Docente</label>
                <select class="form-select rounded-pill px-3" name="id_docente" required>
                    <?php foreach($docentes as $doc): ?>
                        <option value="<?= $doc['id_usuario'] ?>" <?= ($doc['id_usuario'] == $turno['id_docente']) ? 'selected' : '' ?>>
                            <?= esc($doc['nombre'] . ' ' . $doc['apellido']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-primary-dark small fw-bold">Sucursal</label>
                <select class="form-select rounded-pill px-3" name="id_sucursal" required>
                    <?php foreach($sucursales as $suc): ?>
                        <option value="<?= $suc['id_sucursal'] ?>" <?= ($suc['id_sucursal'] == $turno['id_sucursal']) ? 'selected' : '' ?>>
                            <?= esc($suc['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label text-primary-dark small fw-bold">Capacidad Total</label>
                <input type="number" class="form-control rounded-pill px-3" name="capacidad" value="<?= $turno['capacidad'] ?>" min="1" required>
                <div class="form-text small">Nota: Al actualizar, los cupos disponibles se reiniciarán a este valor.</div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Actualizar Turno</button>
                <a href="<?= base_url('admin/turnos') ?>" class="btn btn-outline-secondary rounded-pill py-2">Cancelar</a>
            </div>
        </form>
    </div>
</main>