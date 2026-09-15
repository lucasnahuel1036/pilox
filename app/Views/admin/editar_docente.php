<main class="container mt-5 mb-5">
    <div class="card border-0 shadow-sm rounded-4 mx-auto p-4" style="max-width: 600px; background-color: #F8F9F5;">
        <h5 class="fw-bold text-primary-dark mb-4">Editar Docente</h5>
        <form action="<?= base_url('admin/docentes/actualizar/' . $docente['id_usuario']) ?>" method="POST">
            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label text-primary-dark small fw-bold">Nombre</label>
                    <input type="text" class="form-control rounded-pill px-3" name="nombre" value="<?= esc($docente['nombre']) ?>" required>
                </div>
                <div class="col-6 mb-3">
                    <label class="form-label text-primary-dark small fw-bold">Apellido</label>
                    <input type="text" class="form-control rounded-pill px-3" name="apellido" value="<?= esc($docente['apellido']) ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label text-primary-dark small fw-bold">Email</label>
                <input type="email" class="form-control rounded-pill px-3" name="email" value="<?= esc($docente['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-primary-dark small fw-bold">Nueva Contraseña (dejar en blanco para no cambiarla)</label>
                <input type="password" class="form-control rounded-pill px-3" name="password">
            </div>
            <div class="mb-3">
                <label class="form-label text-primary-dark small fw-bold">Especialidad</label>
                <input type="text" class="form-control rounded-pill px-3" name="especialidad" value="<?= esc($docente['especialidad']) ?>">
            </div>
            <div class="mb-4">
                <label class="form-label text-primary-dark small fw-bold">Teléfono</label>
                <input type="text" class="form-control rounded-pill px-3" name="telefono" value="<?= esc($docente['telefono']) ?>">
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Actualizar</button>
                <a href="<?= base_url('admin/docentes') ?>" class="btn btn-outline-secondary rounded-pill py-2">Cancelar</a>
            </div>
        </form>
    </div>
</main>