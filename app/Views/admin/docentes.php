<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Gestión de Docentes</h2>
            <p class="text-muted small">Administrá el staff de profesores de Pilox</p>
        </div>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Nuevo Docente</h5>
                    <form action="<?= base_url('admin/docentes/guardar') ?>" method="POST">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label text-primary-dark small fw-bold">Nombre</label>
                                <input type="text" class="form-control rounded-pill px-3" name="nombre" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label text-primary-dark small fw-bold">Apellido</label>
                                <input type="text" class="form-control rounded-pill px-3" name="apellido" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Email (Usuario)</label>
                            <input type="email" class="form-control rounded-pill px-3" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Contraseña</label>
                            <input type="password" class="form-control rounded-pill px-3" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Especialidad</label>
                            <input type="text" class="form-control rounded-pill px-3" name="especialidad" placeholder="Ej: Kinesiólogo, Yoga...">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-primary-dark small fw-bold">Teléfono</label>
                            <input type="text" class="form-control rounded-pill px-3" name="telefono">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Guardar Docente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Staff Registrado</h5>
                    <?php if(empty($docentes)): ?>
                        <div class="alert alert-light text-center text-muted" role="alert">Todavía no hay docentes registrados.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-secondary small">NOMBRE</th>
                                        <th class="text-secondary small">EMAIL / TEL</th>
                                        <th class="text-secondary small">ESPECIALIDAD</th>
                                        <th class="text-secondary small text-end">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($docentes as $docente): ?>
                                        <tr>
                                            <td class="fw-bold text-primary-dark"><?= esc($docente['nombre'] . ' ' . $docente['apellido']) ?></td>
                                            <td>
                                                <div class="small"><?= esc($docente['email']) ?></div>
                                                <div class="small text-muted"><?= esc($docente['telefono']) ?></div>
                                            </td>
                                            <td><span class="badge bg-secondary"><?= esc($docente['especialidad']) ?></span></td>
                                            <td class="text-end">
                                                <a href="<?= base_url('admin/docentes/editar/' . $docente['id_usuario']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">Editar</a>
                                                <a href="<?= base_url('admin/docentes/eliminar/' . $docente['id_usuario']) ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('¿Seguro?');">Eliminar</a>
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