<main class="container mt-5 mb-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0"><i class="bi bi-person-check"></i> Asignación Periódica a Alumno</h2>
            <p class="text-muted small mb-0">Inscribí a un alumno en sus días y horario fijos dentro de la vigencia de su abono</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= base_url('admin/turnos') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver a Turnos</a>
            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="background-color: #F8F9F5;">
        <div class="card-body p-4 p-md-5">
            <form action="<?= base_url('admin/turnos/guardar_asignacion') ?>" method="POST">
                <?= csrf_field() ?>

                <h5 class="fw-bold text-primary-dark mb-4 border-bottom pb-2">1. Seleccionar Alumno</h5>
                <div class="row mb-4">
                    <div class="col-md-6 position-relative">
                        <label for="buscar_alumno" class="form-label text-primary-dark small fw-bold">Alumno (debe tener abono vigente y pagado)</label>
                        <input type="text" class="form-control rounded-pill px-3" id="buscar_alumno"
                               placeholder="Buscar por nombre, apellido o DNI..." autocomplete="off">
                        <input type="hidden" name="id_usuario" id="id_usuario" value="">
                        <div class="list-group position-absolute w-100 shadow-sm" id="resultados_alumnos" style="z-index: 10;"></div>
                        <div class="form-text" id="alumno_elegido"></div>
                    </div>
                </div>

                <h5 class="fw-bold text-primary-dark mb-4 border-bottom pb-2 mt-4">2. Días y Horario Fijos</h5>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-primary-dark small fw-bold">Actividad</label>
                        <select class="form-select rounded-pill px-3" name="id_actividad" required>
                            <option value="" disabled selected>Seleccionar disciplina...</option>
                            <?php foreach ($actividades as $act): ?>
                                <option value="<?= $act['id_actividad'] ?>" <?= old('id_actividad') == $act['id_actividad'] ? 'selected' : '' ?>>
                                    <?= esc($act['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-primary-dark small fw-bold">Sucursal</label>
                        <select class="form-select rounded-pill px-3" name="id_sucursal" required>
                            <option value="" disabled selected>Seleccionar local...</option>
                            <?php foreach ($sucursales as $suc): ?>
                                <option value="<?= $suc['id_sucursal'] ?>" <?= old('id_sucursal') == $suc['id_sucursal'] ? 'selected' : '' ?>>
                                    <?= esc($suc['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-primary-dark small fw-bold">Horario de la clase</label>
                        <input type="time" class="form-control rounded-pill px-3" name="horario" value="<?= esc(old('horario')) ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-primary-dark small fw-bold d-block mb-3">Días de la semana que asiste el alumno:</label>
                    <div class="pd-dias-grupo">
                        <div class="pd-dia-check">
                            <input type="checkbox" name="dias[]" value="1" id="dia1">
                            <label for="dia1">Lunes</label>
                        </div>
                        <div class="pd-dia-check">
                            <input type="checkbox" name="dias[]" value="2" id="dia2">
                            <label for="dia2">Martes</label>
                        </div>
                        <div class="pd-dia-check">
                            <input type="checkbox" name="dias[]" value="3" id="dia3">
                            <label for="dia3">Miércoles</label>
                        </div>
                        <div class="pd-dia-check">
                            <input type="checkbox" name="dias[]" value="4" id="dia4">
                            <label for="dia4">Jueves</label>
                        </div>
                        <div class="pd-dia-check">
                            <input type="checkbox" name="dias[]" value="5" id="dia5">
                            <label for="dia5">Viernes</label>
                        </div>
                        <div class="pd-dia-check">
                            <input type="checkbox" name="dias[]" value="6" id="dia6">
                            <label for="dia6">Sábado</label>
                        </div>
                    </div>
                    <div class="form-text mt-2">
                        Se buscarán automáticamente las clases creadas en esos días y horario dentro del período de vigencia del abono del alumno, hasta completar sus clases disponibles.
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-custom-solid rounded-pill px-5 py-2"
                            onclick="return confirm('¿Confirmás la asignación periódica de turnos para este alumno?');">
                        Asignar Turnos
                    </button>
                </div>
            </form>
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
