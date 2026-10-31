<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Gestión de Sucursales</h2>
            <p class="text-muted small">Administrá los locales físicos de Pilox</p>
        </div>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
    </div>

    <div class="row">
        <!-- Formulario para agregar nueva sucursal -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Nueva Sucursal</h5>
                    <form action="<?= base_url('admin/sucursales/guardar') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="nombre" class="form-label text-primary-dark small fw-bold">Nombre del Local</label>
                            <input type="text" class="form-control rounded-pill px-3" id="nombre" name="nombre" required placeholder="Ej: Pilox Centro">
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label text-primary-dark small fw-bold">Dirección</label>
                            <input type="text" class="form-control rounded-pill px-3" id="direccion" name="direccion" required placeholder="Calle 123">
                        </div>
                        <div class="mb-4">
                            <label for="telefono" class="form-label text-primary-dark small fw-bold">Teléfono (Opcional)</label>
                            <input type="text" class="form-control rounded-pill px-3" id="telefono" name="telefono" placeholder="351...">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Guardar Sucursal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla con el listado de sucursales -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="--pd-delay: .08s">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Sucursales Registradas</h5>
                    
                    <?php if(empty($sucursales)): ?>
                        <div class="alert alert-light text-center text-muted" role="alert">
                            Todavía no hay sucursales registradas.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-secondary small">NOMBRE</th>
                                        <th class="text-secondary small">DIRECCIÓN</th>
                                        <th class="text-secondary small">TELÉFONO</th>
                                        <th class="text-secondary small">ESTADO</th>
                                        <th class="text-secondary small text-end">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($sucursales as $sucursal): ?>
                                        <?php $estadoSuc = $sucursal['estado'] ?? 'activo'; ?>
                                        <tr>
                                            <td class="fw-bold text-primary-dark"><?= esc($sucursal['nombre']) ?></td>
                                            <td><?= esc($sucursal['direccion']) ?></td>
                                            <td><?= esc($sucursal['telefono']) ?: '-' ?></td>
                                            <td>
                                                <?php if ($estadoSuc === 'activo'): ?>
                                                    <span class="badge bg-success">Activa</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactiva</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end text-nowrap">
                                                <div class="d-inline-flex align-items-center gap-1">
                                                    <a href="<?= base_url('admin/sucursales/editar/' . $sucursal['id_sucursal']) ?>" 
                                                       class="btn btn-sm btn-outline-primary rounded-circle btn-icon"
                                                       title="Editar sucursal">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if ($estadoSuc === 'inactivo'): ?>
                                                        <form action="<?= base_url('admin/sucursales/estado/' . $sucursal['id_sucursal']) ?>" method="POST" class="d-inline"
                                                              data-confirmar="¿Deseas reactivar esta sucursal?" data-confirmar-titulo="Reactivar sucursal" data-confirmar-boton="Sí, reactivar">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-circle btn-icon" title="Reactivar sucursal">
                                                                <i class="bi bi-building-check"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <form action="<?= base_url('admin/sucursales/estado/' . $sucursal['id_sucursal']) ?>" method="POST" class="d-inline"
                                                              data-confirmar="¿Pasar esta sucursal a estado inactiva?" data-confirmar-titulo="Dar de baja sucursal" data-confirmar-boton="Sí, pasar a inactiva" data-confirmar-tipo="peligro">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-circle btn-icon" title="Dar de baja (pasar a inactiva)">
                                                                <i class="bi bi-building-slash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <form action="<?= base_url('admin/sucursales/eliminar/' . $sucursal['id_sucursal']) ?>" method="POST" class="d-inline"
                                                        data-confirmar="¿Estás seguro de eliminar esta sucursal?" data-confirmar-titulo="Eliminar sucursal" data-confirmar-boton="Sí, eliminar" data-confirmar-tipo="peligro">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle btn-icon" title="Eliminar sucursal">
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>