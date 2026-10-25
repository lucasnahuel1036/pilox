<main class="container mt-5 mb-5">
    <div class="mb-4">
        <h2 class="text-primary-dark fw-bold mb-0">Mi Panel</h2>
        <p class="text-muted small">Hola, <?= esc(session()->get('nombre')) ?></p>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-primary-dark mb-0">📅 Próximas clases</h5>
                <a href="<?= base_url('docente/clases') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Ver todas mis clases</a>
            </div>

            <?php if (empty($clases)): ?>
                <div class="alert alert-light text-center text-muted mb-0" role="alert">
                    No tenés clases asignadas próximamente.
                </div>
            <?php else: ?>
                <?= view('docente/tabla_clases', ['clases' => $clases]) ?>
            <?php endif; ?>
        </div>
    </div>
</main>