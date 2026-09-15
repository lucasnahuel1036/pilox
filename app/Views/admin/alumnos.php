<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Gestión de Alumnos</h2>
            <p class="text-muted small">Administrá los estudiantes de Pilox</p>
        </div>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
    </div>

    <div class="row">
        <!-- Formulario de Alta Manual -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Registrar Alumno</h5>
                    <form action="<?= base_url('admin/alumnos/guardar') ?>" method="POST">
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
                            <label class="form-label text-primary-dark small fw-bold">Email</label>
                            <input type="email" class="form-control rounded-pill px-3" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Contraseña Provisoria</label>
                            <input type="password" class="form-control rounded-pill px-3" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Teléfono</label>
                            <input type="text" class="form-control rounded-pill px-3" name="telefono">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-primary-dark small fw-bold">Fecha de Nacimiento</label>
                            <input type="date" class="form-control rounded-pill px-3" name="fecha_nacimiento">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Guardar Alumno</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de Alumnos -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Alumnos Registrados</h5>
                    <?php if(empty($alumnos)): ?>
                        <div class="alert alert-light text-center text-muted" role="alert">No hay alumnos registrados.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-secondary small">NOMBRE</th>
                                        <th class="text-secondary small">CONTACTO</th>
                                        <th class="text-secondary small">ESTADO</th>
                                        <th class="text-secondary small text-end">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($alumnos as $alumno): ?>
                                        <tr>
                                            <td class="fw-bold text-primary-dark"><?= esc($alumno['nombre'] . ' ' . $alumno['apellido']) ?></td>
                                            <td>
                                                <div class="small"><?= esc($alumno['email']) ?></div>
                                                <div class="small text-muted"><?= esc($alumno['telefono']) ?></div>
                                            </td>
                                            <td>
                                                <?php if($alumno['estado'] === 'activo'): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php elseif($alumno['estado'] === 'moroso'): ?>
                                                    <span class="badge bg-danger">Moroso</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= base_url('admin/alumnos/editar/' . $alumno['id_usuario']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">Editar</a>
                                                <a href="<?= base_url('admin/alumnos/eliminar/' . $alumno['id_usuario']) ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('¿Seguro que deseas eliminar este alumno?');">Eliminar</a>
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