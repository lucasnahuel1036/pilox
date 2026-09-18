<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0"><i class="bi bi-calendar-range"></i> Generador Periódico de Turnos</h2>
            <p class="text-muted small">Planificá la grilla regular de clases a largo plazo</p>
        </div>
        <a href="<?= base_url('admin/turnos') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver a Turnos</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
        <div class="card-body p-4 p-md-5">
            <form action="<?= base_url('admin/turnos/generar_masivo') ?>" method="POST">
                
                <h5 class="fw-bold text-primary-dark mb-4 border-bottom pb-2">1. Definir Período y Días</h5>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-primary-dark small fw-bold">Fecha de Inicio</label>
                        <input type="date" class="form-control rounded-pill px-3" name="fecha_inicio" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-primary-dark small fw-bold">Fecha de Fin</label>
                        <input type="date" class="form-control rounded-pill px-3" name="fecha_fin" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-primary-dark small fw-bold">Horario de la clase</label>
                        <input type="time" class="form-control rounded-pill px-3" name="horario" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-primary-dark small fw-bold d-block mb-3">Días de la semana que se dicta:</label>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="1" id="dia1">
                            <label class="form-check-label" for="dia1">Lunes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="2" id="dia2">
                            <label class="form-check-label" for="dia2">Martes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="3" id="dia3">
                            <label class="form-check-label" for="dia3">Miércoles</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="4" id="dia4">
                            <label class="form-check-label" for="dia4">Jueves</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="5" id="dia5">
                            <label class="form-check-label" for="dia5">Viernes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dias[]" value="6" id="dia6">
                            <label class="form-check-label" for="dia6">Sábado</label>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold text-primary-dark mb-4 border-bottom pb-2 mt-5">2. Detalles de la Clase</h5>
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-primary-dark small fw-bold">Actividad</label>
                        <select class="form-select rounded-pill px-3" name="id_actividad" required>
                            <option value="" disabled selected>Seleccionar...</option>
                            <?php foreach($actividades as $act): ?>
                                <option value="<?= $act['id_actividad'] ?>"><?= esc($act['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-primary-dark small fw-bold">Docente</label>
                        <select class="form-select rounded-pill px-3" name="id_docente" required>
                            <option value="" disabled selected>Seleccionar...</option>
                            <?php foreach($docentes as $doc): ?>
                                <option value="<?= $doc['id_usuario'] ?>"><?= esc($doc['nombre'] . ' ' . $doc['apellido']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-primary-dark small fw-bold">Sucursal</label>
                        <select class="form-select rounded-pill px-3" name="id_sucursal" required>
                            <option value="" disabled selected>Seleccionar...</option>
                            <?php foreach($sucursales as $suc): ?>
                                <option value="<?= $suc['id_sucursal'] ?>"><?= esc($suc['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label text-primary-dark small fw-bold">Capacidad Total</label>
                        <input type="number" class="form-control rounded-pill px-3" name="capacidad" min="1" required placeholder="Ej: 15">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-custom-solid rounded-pill px-5 py-2" onclick="return confirm('¿Confirmas la generación de esta grilla por periodo?');">Generar Grilla</button>
                </div>
            </form>
        </div>
    </div>
</main>