<main class="container mt-5 mb-5">
    <div class="card border-0 shadow-sm rounded-4 mx-auto p-4" style="max-width: 600px; background-color: #F8F9F5;">
        <h5 class="fw-bold text-primary-dark mb-4">Editar Perfil de Alumno</h5>
        <form action="<?= base_url('admin/alumnos/actualizar/' . $alumno['id_usuario']) ?>" method="POST">
            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label text-primary-dark small fw-bold">Nombre</label>
                    <input type="text" class="form-control rounded-pill px-3" name="nombre" value="<?= esc($alumno['nombre']) ?>" required>
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label text-primary-dark small fw-bold">Apellido</label>
                    <input type="text" class="form-control rounded-pill px-3" name="apellido" value="<?= esc($alumno['apellido']) ?>" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label text-primary-dark small fw-bold">Email</label>
                    <input type="email" class="form-control rounded-pill px-3" name="email" value="<?= esc($alumno['email']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label text-primary-dark small fw-bold">Teléfono</label>
                    <input type="text" class="form-control rounded-pill px-3" name="telefono" value="<?= esc($alumno['telefono']) ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label text-primary-dark small fw-bold">Nueva Contraseña (dejar en blanco para no cambiarla)</label>
                <input type="password" class="form-control rounded-pill px-3" name="password">
            </div>
            
            <hr class="my-4">
            
            <div class="row">
                <!-- Selector de Estado -->
                <div class="col-md-6 mb-4">
                    <label class="form-label text-primary-dark small fw-bold">Estado del Alumno</label>
                    <select class="form-select rounded-pill px-3" name="estado">
                        <option value="activo" <?= $alumno['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="moroso" <?= $alumno['estado'] === 'moroso' ? 'selected' : '' ?>>Moroso</option>
                        <option value="inactivo" <?= $alumno['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
                
                <!-- Selector de Rol (La magia del sistema) -->
                <div class="col-md-6 mb-4">
                    <label class="form-label text-primary-dark small fw-bold">Rol en el Sistema</label>
                    <select class="form-select rounded-pill px-3" name="rol">
                        <option value="alumno" <?= $alumno['rol'] === 'alumno' ? 'selected' : '' ?>>Alumno</option>
                        <option value="docente" <?= $alumno['rol'] === 'docente' ? 'selected' : '' ?>>Docente</option>
                        <option value="admin" <?= $alumno['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Actualizar Perfil</button>
                <a href="<?= base_url('admin/alumnos') ?>" class="btn btn-outline-secondary rounded-pill py-2">Cancelar</a>
            </div>
        </form>
    </div>
</main>