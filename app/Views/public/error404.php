<main class="container mt-5 mb-5 text-center py-5">
    <div class="card border-0 shadow-sm rounded-4 mx-auto p-5" style="max-width: 550px; background-color: #F8F9F5;">
        <h1 class="display-1 fw-bold text-primary-dark mb-0">404</h1>
        <h4 class="fw-bold text-primary-dark mt-2 mb-3">Página no encontrada</h4>
        <p class="text-muted mb-4">
            La dirección a la que intentaste entrar no existe o fue movida.
        </p>
        <div class="d-flex justify-content-center gap-2">
            <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary rounded-pill px-4">Ir al Inicio</a>
            <?php if (session()->get('is_logged_in')): ?>
                <a href="<?= base_url(url_panel()) ?>" class="btn btn-custom-solid rounded-pill px-4">Ir a mi Panel</a>
            <?php endif; ?>
        </div>
    </div>
</main>