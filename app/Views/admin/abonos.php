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
            <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Asignar Abono</h5>
                    <form action="<?= base_url('admin/abonos/guardar') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3 position-relative">
                            <label for="buscar_alumno" class="form-label text-primary-dark small fw-bold">Alumno</label>
                            <input type="text" class="form-control rounded-pill px-3" id="buscar_alumno"
                                   placeholder="Buscar por nombre, apellido o DNI" autocomplete="off">
                            <input type="hidden" name="id_usuario" id="id_usuario" value="">
                            <div class="list-group position-absolute w-100 shadow-sm" id="resultados_alumnos" style="z-index: 10;"></div>
                            <div class="form-text" id="alumno_elegido"></div>
                        </div>
                        <div class="mb-3">
                            <label for="clases" class="form-label text-primary-dark small fw-bold">Tipo de abono</label>
                            <select class="form-select rounded-pill px-3" id="clases" name="clases" required>
                                <?php foreach ($tarifas as $tarifa): ?>
                                    <option value="<?= $tarifa['clases'] ?>" <?= old('clases') == $tarifa['clases'] ? 'selected' : '' ?>>
                                        <?= $tarifa['clases'] ?> clases — $<?= number_format((float) $tarifa['precio'], 0, ',', '.') ?>
                                    </option>
                                <?php endforeach; ?>
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

                <!-- Abonos vigentes e historial -->
        <div class="col-md-8">

            <!-- Buscador (filtra las dos tablas) -->
            <form action="<?= base_url('admin/abonos') ?>" method="GET" class="d-flex gap-2 mb-4">
                <input type="text" class="form-control rounded-pill px-3" name="q"
                       value="<?= esc($busqueda ?? '') ?>" placeholder="Buscar por nombre, apellido o DNI del alumno">
                <button type="submit" class="btn btn-custom-solid rounded-pill px-3">Buscar</button>
                <?php if (!empty($busqueda)): ?>
                    <a href="<?= base_url('admin/abonos') ?>" class="btn btn-outline-secondary rounded-pill px-3">Limpiar</a>
                <?php endif; ?>
            </form>

            <!-- Vigentes -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 pd-reveal" style="--pd-delay: .06s">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Abonos Vigentes</h5>

                    <?php if (empty($vigentes)): ?>
                        <div class="alert alert-light text-center text-muted mb-0" role="alert">
                            <?= !empty($busqueda) ? 'No hay abonos vigentes para esa búsqueda.' : 'No hay abonos vigentes.' ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-secondary small">ALUMNO</th>
                                        <th class="text-secondary small">CLASES</th>
                                        <th class="text-secondary small">VIGENCIA</th>
                                        <th class="text-secondary small">PAGO</th>
                                        <th class="text-secondary small text-end">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($vigentes as $abono): ?>
                                        <tr>
                                            <td class="fw-bold text-primary-dark">
                                                <?= esc($abono['apellido'] . ', ' . $abono['nombre']) ?>
                                                <div class="small text-muted fw-normal">DNI <?= esc($abono['dni']) ?></div>
                                            </td>
                                            <td><?= $abono['clases_restantes'] ?> / <?= $abono['clases_totales'] ?></td>
                                            <td class="small">
                                                <?= date('d/m/Y', strtotime($abono['fecha_inicio'])) ?>
                                                al <?= date('d/m/Y', strtotime($abono['fecha_fin'])) ?>
                                            </td>
                                            <td>
                                                <?php if ($abono['estado_pago'] === 'pagado'): ?>
                                                    <span class="badge bg-success">Pagado</span>
                                                    <?php if (!empty($abono['fecha_pago'])): ?>
                                                        <div class="small text-muted"><?= date('d/m/Y', strtotime($abono['fecha_pago'])) ?></div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                                <?php endif; ?>
                                                <div class="small text-muted">$<?= number_format((float) $abono['precio'], 0, ',', '.') ?></div>
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
                        <div class="mt-3"><?= $pager->links('vigentes') ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Historial -->
            <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="--pd-delay: .12s">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-1">Historial de Abonos</h5>
                    <p class="text-muted small mb-4">Abonos vencidos o agotados. Los que quedaron sin pagar figuran en amarillo y se pueden regularizar.</p>

                    <?php if (empty($historial)): ?>
                        <div class="alert alert-light text-center text-muted mb-0" role="alert">
                            <?= !empty($busqueda) ? 'No hay abonos en el historial para esa búsqueda.' : 'Todavía no hay abonos en el historial.' ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-secondary small">ALUMNO</th>
                                        <th class="text-secondary small">CLASES USADAS</th>
                                        <th class="text-secondary small">VIGENCIA</th>
                                        <th class="text-secondary small">MOTIVO</th>
                                        <th class="text-secondary small">PAGO</th>
                                        <th class="text-secondary small text-end">ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historial as $abono): ?>
                                        <tr class="<?= $abono['estado_pago'] === 'pendiente' ? 'table-warning' : '' ?>">
                                            <td class="fw-bold text-primary-dark">
                                                <?= esc($abono['apellido'] . ', ' . $abono['nombre']) ?>
                                                <div class="small text-muted fw-normal">DNI <?= esc($abono['dni']) ?></div>
                                            </td>
                                            <td><?= $abono['clases_totales'] - $abono['clases_restantes'] ?> / <?= $abono['clases_totales'] ?></td>
                                            <td class="small">
                                                <?= date('d/m/Y', strtotime($abono['fecha_inicio'])) ?>
                                                al <?= date('d/m/Y', strtotime($abono['fecha_fin'])) ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?= $abono['clases_restantes'] <= 0 ? 'Agotado' : 'Vencido' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($abono['estado_pago'] === 'pagado'): ?>
                                                    <span class="badge bg-success">Pagado</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <?php if ($abono['estado_pago'] === 'pendiente'): ?>
                                                    <form action="<?= base_url('admin/abonos/pago/' . $abono['id_abono']) ?>" method="POST" class="d-inline">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">Marcar pagado</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3"><?= $pager->links('historial') ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
(function () {
    const input     = document.getElementById('buscar_alumno');
    const hidden    = document.getElementById('id_usuario');
    const lista     = document.getElementById('resultados_alumnos');
    const elegido   = document.getElementById('alumno_elegido');
    const urlBuscar = '<?= base_url('admin/alumnos/buscar') ?>';
    let temporizador = null;

    input.addEventListener('input', function () {
        hidden.value = '';              // si vuelve a escribir, se anula la selección anterior
        elegido.textContent = '';
        clearTimeout(temporizador);

        const texto = input.value.trim();
        if (texto.length < 2) { lista.innerHTML = ''; return; }

        // Esperamos 250 ms desde la última tecla para no saturar el servidor
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

    // No se puede enviar el formulario sin elegir un alumno de la lista
    input.form.addEventListener('submit', function (e) {
        if (!hidden.value) {
            e.preventDefault();
            elegido.textContent = 'Elegí un alumno de la lista.';
            input.focus();
        }
    });
})();
</script>