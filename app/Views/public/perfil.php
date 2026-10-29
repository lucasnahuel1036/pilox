<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Mi Perfil</h2>
            <p class="text-muted small">Administrá tus datos personales y tu contraseña de acceso</p>
        </div>
        <a href="<?= base_url(url_panel()) ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver a mi Panel</a>
    </div>

    <div class="row">
        <!-- Datos Personales -->
        <div class="col-md-7 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4"><i class="bi bi-person-lines-fill me-2"></i>Datos Personales</h5>
                    <form action="<?= base_url('perfil/actualizar') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-primary-dark small fw-bold">Nombre</label>
                                <input type="text" class="form-control rounded-pill px-3" name="nombre" value="<?= esc($usuario['nombre']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-primary-dark small fw-bold">Apellido</label>
                                <input type="text" class="form-control rounded-pill px-3" name="apellido" value="<?= esc($usuario['apellido']) ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-primary-dark small fw-bold">DNI</label>
                                <input type="text" class="form-control rounded-pill px-3 bg-light" value="<?= esc($usuario['dni']) ?>" disabled>
                                <div class="form-text small">Para modificar tu DNI consultá en administración.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-primary-dark small fw-bold">Teléfono</label>
                                <input type="text" class="form-control rounded-pill px-3" name="telefono" value="<?= esc($usuario['telefono']) ?>" placeholder="Ej: 351...">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Correo Electrónico</label>
                            <input type="email" class="form-control rounded-pill px-3" name="email" value="<?= esc($usuario['email']) ?>" required>
                        </div>

                        <?php if ($usuario['rol'] === 'alumno'): ?>
                            <div class="mb-4">
                                <label class="form-label text-primary-dark small fw-bold">Fecha de Nacimiento</label>
                                <input type="date" class="form-control rounded-pill px-3" name="fecha_nacimiento" value="<?= esc($usuario['fecha_nacimiento']) ?>">
                            </div>
                        <?php elseif ($usuario['rol'] === 'docente'): ?>
                            <div class="mb-4">
                                <label class="form-label text-primary-dark small fw-bold">Especialidad</label>
                                <input type="text" class="form-control rounded-pill px-3" name="especialidad" value="<?= esc($usuario['especialidad']) ?>" placeholder="Ej: Kinesiólogo, Yoga, Pilates...">
                            </div>
                        <?php endif; ?>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cambiar Contraseña -->
        <div class="col-md-5 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background-color: #F8F9F5;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary-dark mb-4"><i class="bi bi-shield-lock me-2"></i>Cambiar Contraseña</h5>
                    <form action="<?= base_url('perfil/password') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Contraseña Actual</label>
                            <input type="password" class="form-control rounded-pill px-3" name="password_actual" required placeholder="********">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-primary-dark small fw-bold">Nueva Contraseña</label>
                            <input type="password" class="form-control rounded-pill px-3" name="password_nueva" minlength="8" required placeholder="Mínimo 8 caracteres">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-primary-dark small fw-bold">Repetir Nueva Contraseña</label>
                            <input type="password" class="form-control rounded-pill px-3" name="repetir_password" minlength="8" required placeholder="Repetir contraseña">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-outline-dark rounded-pill py-2 fw-semibold">Actualizar Contraseña</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

