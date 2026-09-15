<main class="container mt-5 mb-5">
    <div class="card border-0 shadow-sm rounded-4 mx-auto p-4" style="max-width: 500px; background-color: #F8F9F5;">
        <h5 class="fw-bold text-primary-dark mb-4">Editar Actividad</h5>
        <form action="<?= base_url('admin/actividades/actualizar/' . $actividad['id_actividad']) ?>" method="POST">
            <div class="mb-3">
                <label class="form-label text-primary-dark small fw-bold">Nombre</label>
                <input type="text" class="form-control rounded-pill px-3" name="nombre" value="<?= esc($actividad['nombre']) ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label text-primary-dark small fw-bold">Descripción</label>
                <textarea class="form-control rounded-4 px-3 py-2" name="descripcion" rows="3"><?= esc($actividad['descripcion']) ?></textarea>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Actualizar</button>
                <a href="<?= base_url('admin/actividades') ?>" class="btn btn-outline-secondary rounded-pill py-2">Cancelar</a>
            </div>
        </form>
    </div>
</main>