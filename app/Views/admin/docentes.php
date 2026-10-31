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
            <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Nuevo Docente</h5>
                    <form action="<?= base_url('admin/docentes/guardar') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label text-primary-dark small fw-bold">Nombre</label>
                                <input type="text" class="form-control rounded-pill px-3" name="nombre" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label text-primary-dark small fw-bold">Apellido</label>
                                <input type="text" class="form-control rounded-pill px-3" name="apellido" required>
                            </div>
                            <div class="col-md-6 mb-3">
                            <label class="form-label text-primary-dark small fw-bold">DNI</label>
                            <input type="number" class="form-control rounded-pill px-3" name="dni" required placeholder="Sin puntos">
                             </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Email (Usuario)</label>
                            <input type="email" class="form-control rounded-pill px-3" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Contraseña</label>
                            <input type="password" class="form-control rounded-pill px-3" name="password" minlength="8" required>
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
            <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="--pd-delay: .08s">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Staff Registrado</h5>
                    <form action="<?= base_url('admin/docentes') ?>" method="GET" class="d-flex gap-2 mb-4">
                        <input type="text" class="form-control rounded-pill px-3" name="q"
                               value="<?= esc($busqueda ?? '') ?>" placeholder="Buscar por nombre, apellido, DNI o email">
                        <button type="submit" class="btn btn-custom-solid rounded-pill px-3">Buscar</button>
                        <?php if (!empty($busqueda)): ?>
                            <a href="<?= base_url('admin/docentes') ?>" class="btn btn-outline-secondary rounded-pill px-3">Limpiar</a>
                        <?php endif; ?>
                    </form>
                    <?php if(empty($docentes)): ?>
                        <div class="alert alert-light text-center text-muted" role="alert">
                            <?= !empty($busqueda) ? 'No se encontraron docentes para esa búsqueda.' : 'No hay docentes registrados.' ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-secondary small">NOMBRE</th>
                                        <th class="text-secondary small">EMAIL / TEL</th>
                                        <th class="text-secondary small">ESPECIALIDAD / ESTADO</th>
                                        <th class="text-secondary small text-end">ACCIONES</th>
                                    </tr>
                                </thead>
                            
                                <tbody>
                                    <?php foreach($docentes as $docente): ?>
                                        <tr>
                                            <td class="fw-bold text-primary-dark"> <?= esc($docente['nombre'] . ' ' . $docente['apellido']) ?><br> <span class="small text-muted fw-normal">DNI: <?= esc($docente['dni']) ?></span> </td>
                                            <td>
                                                <div class="small"><?= esc($docente['email']) ?></div>
                                                <div class="small text-muted"><?= esc($docente['telefono']) ?></div>
                                            </td>
                                            <td>
                                                <?php if (!empty($docente['especialidad'])): ?>
                                                    <span class="badge bg-secondary mb-1"><?= esc($docente['especialidad']) ?></span><br>
                                                <?php endif; ?>
                                                <?php if ($docente['estado'] === 'activo'): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end text-nowrap">
                                                <div class="d-inline-flex align-items-center gap-1">
                                                    <a href="<?= base_url('admin/docentes/editar/' . $docente['id_usuario']) ?>"
                                                       class="btn btn-sm btn-outline-primary rounded-circle btn-icon"
                                                       title="Editar docente">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if ($docente['estado'] === 'inactivo'): ?>
                                                        <form action="<?= base_url('admin/docentes/estado/' . $docente['id_usuario']) ?>" method="POST" class="d-inline"
                                                              data-confirmar="¿Deseas reactivar a este docente?" data-confirmar-titulo="Reactivar docente" data-confirmar-boton="Sí, reactivar">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-circle btn-icon" title="Reactivar docente">
                                                                <i class="bi bi-person-check"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <form action="<?= base_url('admin/docentes/estado/' . $docente['id_usuario']) ?>" method="POST" class="d-inline"
                                                              data-confirmar="¿Pasar este docente a estado inactivo?" data-confirmar-titulo="Dar de baja docente" data-confirmar-boton="Sí, pasar a inactivo" data-confirmar-tipo="peligro">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle btn-icon" title="Dar de baja (pasar a inactivo)">
                                                                <i class="bi bi-person-slash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <form action="<?= base_url('admin/docentes/eliminar/' . $docente['id_usuario']) ?>" method="POST" class="d-inline"
                                                        data-confirmar="¿Seguro que deseas eliminar este docente?" data-confirmar-titulo="Eliminar docente" data-confirmar-boton="Sí, eliminar" data-confirmar-tipo="peligro">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle btn-icon" title="Eliminar docente">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3"><?= $pager->links() ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>