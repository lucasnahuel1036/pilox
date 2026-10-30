<main class="container mt-5 mb-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Alumnos Inscriptos en el Turno</h2>
            <p class="text-muted small mb-0">
                <?= esc($turno['actividad']) ?> · <?= esc($turno['sucursal']) ?> ·
                Docente: <?= esc($turno['docente_nombre'] . ' ' . $turno['docente_apellido']) ?> ·
                <?= date('d/m/Y', strtotime($turno['fecha'])) ?> a las <?= date('H:i', strtotime($turno['horario'])) ?> hs
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('admin/turnos') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver a Turnos</a>
            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
        </div>
    </div>

    <div class="row">
        <!-- Columna izquierda: inscribir un alumno puntual en este turno -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-3">Inscribir Alumno</h5>

                    <?php if (!$esFuturo): ?>
                        <div class="alert alert-secondary small mb-0 rounded-4">
                            Este turno ya comenzó o finalizó. No se pueden agregar ni cancelar reservas.
                        </div>
                    <?php elseif ((int) $turno['cupos_disponibles'] <= 0): ?>
                        <div class="alert alert-warning small mb-0 rounded-4">
                            El turno no tiene cupos disponibles (<?= $turno['capacidad'] ?> / <?= $turno['capacidad'] ?> ocupados).
                        </div>
                    <?php else: ?>
                        <form action="<?= base_url('admin/turnos/inscribir/' . $turno['id_turno']) ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-3 position-relative">
                                <label for="buscar_alumno" class="form-label text-primary-dark small fw-bold">Buscar alumno activo</label>
                                <input type="text" class="form-control rounded-pill px-3" id="buscar_alumno"
                                       placeholder="Nombre, apellido o DNI..." autocomplete="off">
                                <input type="hidden" name="id_usuario" id="id_usuario" value="">
                                <div class="list-group position-absolute w-100 shadow-sm" id="resultados_alumnos" style="z-index: 10;"></div>
                                <div class="form-text" id="alumno_elegido"></div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-custom-solid rounded-pill py-2">
                                    Inscribir en este Turno
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Columna derecha: listado de inscriptos -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="--pd-delay: .08s">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-primary-dark mb-0">
                            Listado de Inscriptos (<?= count($inscriptos) ?> / <?= $turno['capacidad'] ?>)
                        </h5>
                        <span class="badge rounded-pill bg-success px-3">
                            Cupos libres: <?= $turno['cupos_disponibles'] ?>
                        </span>
                    </div>

                    <?php if (empty($inscriptos)): ?>
                        <div class="alert alert-light text-center text-muted mb-0" role="alert">
                            Todavía no hay alumnos inscriptos en este turno.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-secondary small">#</th>
                                        <th class="text-secondary small">ALUMNO</th>
                                        <th class="text-secondary small">DNI</th>
                                        <th class="text-secondary small">ASISTENCIA</th>
                                        <?php if ($esFuturo): ?>
                                            <th class="text-secondary small text-end">ACCIÓN</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inscriptos as $i => $alumno): ?>
                                        <tr>
                                            <td class="text-muted"><?= $i + 1 ?></td>
                                            <td class="fw-bold text-primary-dark"><?= esc($alumno['apellido'] . ', ' . $alumno['nombre']) ?></td>
                                            <td><?= esc($alumno['dni']) ?></td>
                                            <td>
                                                <?php if ($alumno['asistencia'] === null): ?>
                                                    <span class="badge bg-secondary">Sin registrar</span>
                                                <?php elseif ((int) $alumno['asistencia'] === 1): ?>
                                                    <span class="badge bg-success">Presente</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Ausente</span>
                                                <?php endif; ?>
                                            </td>
                                            <?php if ($esFuturo): ?>
                                                <td class="text-end">
                                                    <form action="<?= base_url('admin/turnos/cancelar_reserva/' . $alumno['id_reserva']) ?>" method="POST" class="d-inline"
                                                          onsubmit="return confirm('¿Cancelar la reserva de <?= esc($alumno['nombre'] . ' ' . $alumno['apellido']) ?>? Se devolverá la clase a su abono.');">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                            Cancelar reserva
                                                        </button>
                                                    </form>
                                                </td>
                                            <?php endif; ?>
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

<?php if ($esFuturo && (int) $turno['cupos_disponibles'] > 0): ?>
<script>
(function () {
    const input     = document.getElementById('buscar_alumno');
    const hidden    = document.getElementById('id_usuario');
    const lista     = document.getElementById('resultados_alumnos');
    const elegido   = document.getElementById('alumno_elegido');
    const urlBuscar = '<?= base_url('admin/alumnos/buscar') ?>';
    let temporizador = null;

    input.addEventListener('input', function () {
        hidden.value = '';
        elegido.textContent = '';
        clearTimeout(temporizador);

        const texto = input.value.trim();
        if (texto.length < 2) { lista.innerHTML = ''; return; }

        temporizador = setTimeout(function () {
            fetch(urlBuscar + '?q=' + encodeURIComponent(texto))
                .then(function (r) { return r.json(); })
                .then(function (alumnos) {
                    lista.innerHTML = '';

                    if (alumnos.length === 0) {
                        const vacio = document.createElement('div');
                        vacio.className = 'list-group-item small text-muted';
                        vacio.textContent = 'Sin resultados';
                        lista.appendChild(vacio);
                        return;
                    }

                    alumnos.forEach(function (a) {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action small';
                        item.textContent = a.apellido + ', ' + a.nombre + (a.dni ? ' (DNI ' + a.dni + ')' : '');
                        item.addEventListener('click', function () {
                            hidden.value = a.id_usuario;
                            input.value = a.apellido + ', ' + a.nombre;
                            elegido.textContent = 'Alumno seleccionado ✔';
                            lista.innerHTML = '';
                        });
                        lista.appendChild(item);
                    });
                })
                .catch(function () { lista.innerHTML = ''; });
        }, 250);
    });

    input.form.addEventListener('submit', function (e) {
        if (!hidden.value) {
            e.preventDefault();
            elegido.textContent = 'Elegí un alumno de la lista.';
            input.focus();
        }
    });
})();
</script>
<?php endif; ?>
