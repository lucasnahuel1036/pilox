<!-- app/Views/inicio.php -->
<main class="container mt-5">
    <div class="row align-items-center">
        <div class="col-md-6 pe-5">
            <span class="badge bg-secondary mb-3">CENTRO DEPORTIVO - CÓRDOBA</span>
            <h1 class="display-3 text-primary-dark" style="font-family: serif; font-style: italic;">Movimiento<br>que transforma</h1>
            <p class="mt-4 text-muted">En Pilox creemos que el bienestar empieza con el movimiento consciente. Gestioná tus clases, seguí tu progreso y formá parte de una comunidad que cuida tu cuerpo y tu mente.</p>
           <div class="mt-4">
                <?php if (session()->get('is_logged_in')): ?>
                    <?php if (session()->get('rol') === 'admin'): ?>
                        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-custom-solid px-4 py-2 me-3">Ir a mi Panel</a>
                    <?php else: ?>
                        <a href="<?= base_url('turnos') ?>" class="btn btn-custom-solid px-4 py-2 me-3">Mis Reservas</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= base_url('registro') ?>" class="btn btn-custom-solid px-4 py-2 me-3">Comenzar ahora</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Marcador de posición para tu imagen -->
            <div class="bg-secondary rounded-4" style="height: 400px; width: 100%;"></div>
        </div>
    </div>
    <!-- Sección de Tarjetas -->
    <div class="row mt-5 pt-4">
        <!-- Tarjeta 1 -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="background-color: #F8F9F5;">
                <div class="bg-secondary rounded-top" style="height: 150px; width: 100%;"></div>
                <div class="card-body">
                    <h5 class="card-title text-primary-dark fw-bold">Pilates</h5>
                    <p class="card-text small text-muted">Fortalecé tu núcleo y mejorá tu postura con nuestras clases de Pilates adaptadas a todos los niveles.</p>
                    <a href="#" class="text-primary-dark text-decoration-none small fw-bold">Ver horarios &rarr;</a>
                </div>
            </div>
        </div>
        <!-- Tarjeta 2 -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="background-color: #F8F9F5;">
                <div class="bg-secondary rounded-top" style="height: 150px; width: 100%;"></div>
                <div class="card-body">
                    <h5 class="card-title text-primary-dark fw-bold">Yoga</h5>
                    <p class="card-text small text-muted">Encontrá el equilibrio entre cuerpo y mente. Nuestras clases integran movimiento y respiración.</p>
                    <a href="#" class="text-primary-dark text-decoration-none small fw-bold">Ver horarios &rarr;</a>
                </div>
            </div>
        </div>
        <!-- Tarjeta 3 -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="background-color: #F8F9F5;">
                <div class="bg-secondary rounded-top" style="height: 150px; width: 100%;"></div>
                <div class="card-body">
                    <h5 class="card-title text-primary-dark fw-bold">GAP</h5>
                    <p class="card-text small text-muted">Glúteos, abdomen y piernas en una sesión de alta eficiencia. Clases diseñadas para tonificar.</p>
                    <a href="#" class="text-primary-dark text-decoration-none small fw-bold">Ver horarios &rarr;</a>
                </div>
            </div>
        </div>
        <!-- Tarjeta 4 -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="background-color: #F8F9F5;">
                <div class="bg-secondary rounded-top" style="height: 150px; width: 100%;"></div>
                <div class="card-body">
                    <h5 class="card-title text-primary-dark fw-bold">Pilates AFA</h5>
                    <p class="card-text small text-muted">Una propuesta especializada que adapta el método Pilates para un movimiento seguro y sin dolor.</p>
                    <a href="#" class="text-primary-dark text-decoration-none small fw-bold">Ver horarios &rarr;</a>
                </div>
            </div>
        </div>
    </div>
    <?php if (!session()->get('is_logged_in')): ?>
    <!-- Seccion banner -->
    <div class="row mt-5 mb-5 pb-4">
        <div class="col-12">
            <div class="card border-0 rounded-4 p-4 p-md-5 bg-primary-dark text-white shadow-sm">
                <div class="row align-items-center">
                    <div class="col-md-7 mb-3 mb-md-0">
                        <h2 class="display-6 fw-bold" style="font-family: serif; font-style: italic;">¿Lista para empezar?</h2>
                        <p class="mb-0 mt-2" style="color: #F8F9F5;">Creá tu cuenta y gestioná tus clases de forma sencilla desde cualquier dispositivo.</p>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <a href="<?= base_url('registro') ?>" class="btn btn-custom-light px-4 py-2 me-2 mb-2 mb-md-0">Registrarse gratis</a>
                        <a href="<?= base_url('login') ?>" class="btn btn-outline-light px-4 py-2" style="border-radius: 20px;">Iniciar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</main>