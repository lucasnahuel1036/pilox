<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Gestión de Actividades</h2>
            <p class="text-muted small">Administrá las disciplinas de Pilox</p>
        </div>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Nueva Actividad</h5>
                    <form action="<?= base_url('admin/actividades/guardar') ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Nombre</label>
                            <input type="text" class="form-control rounded-pill px-3" name="nombre" required placeholder="Ej: Pilates AFA">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-primary-dark small fw-bold">Descripción</label>
                            <textarea class="form-control rounded-4 px-3 py-2" name="descripcion" rows="3" placeholder="Breve detalle de la clase..."></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Guardar Actividad</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Disciplinas Registradas</h5>
                    <?php if(empty($actividades)): ?>
                        <div class="alert alert-light text-center text-muted" role="alert">Todavía no hay actividades.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-secondary small">NOMBRE</th>
                                        <th class="text-secondary small">DESCRIPCIÓN</th>
                                        <th class="text-secondary small text-end">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($actividades as $act): ?>
                                        <tr>
                                            <td class="fw-bold text-primary-dark"><?= esc($act['nombre']) ?></td>
                                            <td><small><?= esc($act['descripcion']) ?></small></td>
                                            <td class="text-end">
                                                <a href="<?= base_url('admin/actividades/editar/' . $act['id_actividad']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">Editar</a>
                                                <a href="<?= base_url('admin/actividades/eliminar/' . $act['id_actividad']) ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('¿Seguro?');">Eliminar</a>
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