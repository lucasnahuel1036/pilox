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
                        <a href="<?= base_url('alumno/dashboard') ?>" class="btn btn-custom-solid px-4 py-2 me-3">Mis Reservas</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= base_url('registro') ?>" class="btn btn-custom-solid px-4 py-2 me-3">Comenzar ahora</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            
             
            <div class="rounded-4 overflow-hidden shadow-sm" style="height: 400px; width: 100%;">
                <img src="<?= base_url('assets/img/principal.png') ?>" alt="Clases Pilox" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            
        </div>
    </div>
    <!-- Sección de Tarjetas -->
    <div id="disciplinas" class="row mt-5 pt-4">
        <!-- Tarjeta 1 -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="background-color: #F8F9F5;">
                <div class="rounded-top overflow-hidden" style="height: 150px; width: 100%;">
                    <img src="<?= base_url('assets/img/pilates.png') ?>" class="card-img-top" alt="Clase de Pilates" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="card-body">
                    <h5 class="card-title text-primary-dark fw-bold">Pilates</h5>
                    <p class="card-text small text-muted">Fortalecé tu núcleo y mejorá tu postura con nuestras clases de Pilates adaptadas a todos los niveles.</p>
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalPilates">
                        Ver horarios
                    </button>
                </div>
            </div>
        </div>
        <!-- Tarjeta 2 -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="background-color: #F8F9F5;">
                <div class="rounded-top overflow-hidden" style="height: 150px; width: 100%;">
                    <img src="<?= base_url('assets/img/yoga.png') ?>" class="card-img-top" alt="Clase de Yoga" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="card-body">
                    <h5 class="card-title text-primary-dark fw-bold">Yoga</h5>
                    <p class="card-text small text-muted">Encontrá el equilibrio entre cuerpo y mente. Nuestras clases integran movimiento y respiración.</p>
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalYoga">
                        Ver horarios
                    </button>
                </div>
            </div>
        </div>
        <!-- Tarjeta 3 -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="background-color: #F8F9F5;">
                <div class="rounded-top overflow-hidden" style="height: 150px; width: 100%;">
                    <img src="<?= base_url('assets/img/GAP.png') ?>" class="card-img-top" alt="Clase de GAP" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="card-body">
                    <h5 class="card-title text-primary-dark fw-bold">GAP</h5>
                    <p class="card-text small text-muted">Glúteos, abdomen y piernas en una sesión de alta eficiencia. Clases diseñadas para tonificar.</p>
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalGap">
                        Ver horarios
                    </button>
                </div>
            </div>
        </div>
        <!-- Tarjeta 4 -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 border-0 shadow-sm" style="background-color: #F8F9F5;">
                <div class="rounded-top overflow-hidden" style="height: 150px; width: 100%;">
                    <img src="<?= base_url('assets/img/pilatesAFA.png') ?>" class="card-img-top" alt="Clase de Pilates AFA" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="card-body">
                    <h5 class="card-title text-primary-dark fw-bold">Pilates AFA</h5>
                    <p class="card-text small text-muted">Una propuesta especializada que adapta el método Pilates para un movimiento seguro y sin dolor.</p>
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalPilatesAfa">
                        Ver horarios
                    </button>
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

    <!-- Modal para ver horarios de cards -->
    <div class="modal fade" id="modalPilates" tabindex="-1" aria-labelledby="modalPilatesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0 mt-2">
                <h5 class="modal-title fw-bold text-primary-dark" id="modalPilatesLabel">Horarios de Pilates</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mb-2">
                <ul class="list-group list-group-flush text-muted">
                    <li class="list-group-item d-flex flex-column mb-2">
                        <strong>Martes y Jueves</strong>
                        <span>07:00 • 08:00 • 09:00 • 10:00</span>
                        <span>14:00 • 15:00 • 16:00 • 17:00</span>
                        <span>18:00 • 19:00</span>
                    </li>
                    <li class="list-group-item d-flex flex-column mb-2">
                        <strong>Miércoles y Viernes</strong>
                        <span>08:00 • 09:00 • 10:00</span>
                    </li>
                    <li class="list-group-item d-flex flex-column">
                        <strong>Lunes y Miércoles</strong>
                        <span>14:00 • 15:00 • 16:00</span>
                        <span>17:00 • 18:00 • 19:00</span>
                    </li>
                </ul>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill w-100" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalYoga" tabindex="-1" aria-labelledby="modalYogaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0 mt-2">
                <h5 class="modal-title fw-bold text-primary-dark" id="modalYogaLabel">Horarios de Yoga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mb-2">
                <ul class="list-group list-group-flush text-muted">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Martes y Viernes</strong>
                        <span>09:30 - 10:30</span>
                    </li>
                </ul>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill w-100" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalGap" tabindex="-1" aria-labelledby="modalGapLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0 mt-2">
                <h5 class="modal-title fw-bold text-primary-dark" id="modalGapLabel">Horarios de GAP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mb-2">
                <ul class="list-group list-group-flush text-muted">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Martes y Jueves</strong>
                        <span>14:00, 15:00, 16:00, 17:00, 18:00</span>
                    </li>
                </ul>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill w-100" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalPilatesAfa" tabindex="-1" aria-labelledby="modalPilatesAfaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0 mt-2">
                <h5 class="modal-title fw-bold text-primary-dark" id="modalPilatesAfaLabel">Horarios de Pilates AFA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mb-2">
                <h6 class="text-center text-primary-dark fw-bold mb-3">Lunes y Miércoles</h6>
                <ul class="list-group list-group-flush text-muted">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>15:00</strong>
                        <span>Hombro y Cervicales</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>16:00</strong>
                        <span>Cadera y Lumbares</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>17:00</strong>
                        <span>Rodilla y Tobillos</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>18:00</strong>
                        <span class="text-end">Artritis, Artrosis y Osteoporosis</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>19:00</strong>
                        <span>Suelo Pélvico</span>
                    </li>
                </ul>
                <p class="small text-success mt-3 mb-0 text-center fw-bold"><i class="bi bi-check-circle"></i> Clases dictadas por kinesiólogos</p>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-pill w-100" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
</main>