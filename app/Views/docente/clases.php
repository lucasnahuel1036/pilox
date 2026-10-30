<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Mis Clases</h2>
            <p class="text-muted small">Clases que tenés asignadas</p>
        </div>
        <a href="<?= base_url('docente/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
    </div>

    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link rounded-pill <?= $periodo === 'proximas' ? 'active' : '' ?>" href="<?= base_url('docente/clases') ?>">Próximas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill <?= $periodo === 'anteriores' ? 'active' : '' ?>" href="<?= base_url('docente/clases?ver=anteriores') ?>">Anteriores</a>
        </li>
    </ul>

    <div class="card border-0 shadow-sm rounded-4 pd-reveal">
        <div class="card-body p-4">
            <?php if (empty($clases)): ?>
                <div class="alert alert-light text-center text-muted mb-0" role="alert">
                    <?= $periodo === 'proximas' ? 'No tenés clases próximas.' : 'Todavía no tenés clases anteriores.' ?>
                </div>
            <?php else: ?>
                <?= view('docente/tabla_clases', ['clases' => $clases]) ?>
                <div class="mt-3"><?= $pager->links() ?></div>
            <?php endif; ?>
        </div>
    </div>
</main>