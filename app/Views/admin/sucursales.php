<!-- app/Views/admin/sucursales.php -->
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
            <div class="card border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4">Nueva Sucursal</h5>
                    <form action="<?= base_url('admin/sucursales/guardar') ?>" method="POST">
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
            <div class="card border-0 shadow-sm rounded-4">
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
                                        <th class="text-secondary small text-end">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($sucursales as $sucursal): ?>
                                        <tr>
                                            <td class="fw-bold text-primary-dark"><?= esc($sucursal['nombre']) ?></td>
                                            <td><?= esc($sucursal['direccion']) ?></td>
                                            <td><?= esc($sucursal['telefono']) ?: '-' ?></td>
                                            <td class="text-end">
                                                <a href="<?= base_url('admin/sucursales/eliminar/' . $sucursal['id_sucursal']) ?>" 
                                                   class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                   onclick="return confirm('¿Estás seguro de eliminar esta sucursal?');">
                                                   Eliminar
                                                </a>
                                                <a href="<?= base_url('admin/sucursales/editar/' . $sucursal['id_sucursal']) ?>" 
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">
                                                    Editar
                                                </a>
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