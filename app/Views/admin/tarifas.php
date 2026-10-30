<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Tarifas de Abonos</h2>
            <p class="text-muted small">Precio de cada tipo de abono mensual</p>
        </div>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <form action="<?= base_url('admin/tarifas/actualizar') ?>" method="POST">
                        <?= csrf_field() ?>

                        <?php foreach ($tarifas as $tarifa): ?>
                            <div class="mb-3">
                                <label for="precio_<?= $tarifa['clases'] ?>" class="form-label text-primary-dark small fw-bold">
                                    Abono de <?= $tarifa['clases'] ?> clases
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text rounded-start-pill ps-3">$</span>
                                    <input type="number" step="0.01" min="1" class="form-control rounded-end-pill px-3"
                                           id="precio_<?= $tarifa['clases'] ?>" name="precio[<?= $tarifa['clases'] ?>]"
                                           value="<?= esc($tarifa['precio']) ?>" required>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="form-text mb-4">
                            El cambio solo afecta a los abonos que se asignen desde ahora.
                            Los abonos ya asignados conservan el precio con el que se vendieron.
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Guardar tarifas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>