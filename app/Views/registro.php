<!-- app/Views/registro.php -->
<main class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="text-primary-dark fw-bold">Crear Cuenta</h2>
                        <p class="text-muted small">Sumate a Pilox y empezá a moverte</p>
                    </div>
                    
                    <form action="<?= base_url('registrar') ?>" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label text-primary-dark fw-bold small">Nombre</label>
                                <input type="text" class="form-control rounded-pill px-3" id="nombre" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label text-primary-dark fw-bold small">Apellido</label>
                                <input type="text" class="form-control rounded-pill px-3" id="apellido" name="apellido" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-primary-dark small fw-bold">DNI</label>
                            <input type="text" inputmode="numeric" pattern="[0-9]{7,8}" maxlength="8" title="Debe contener 7 u 8 números, sin puntos" class="form-control rounded-pill px-3" name="dni" required placeholder="Ej: 41987654">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label text-primary-dark fw-bold small">Correo Electrónico</label>
                            <input type="email" class="form-control rounded-pill px-3" id="email" name="email" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label text-primary-dark fw-bold small">Contraseña</label>
                            <input type="password" class="form-control rounded-pill px-3" id="password" name="password" minlength="8" required>
                        </div>
                        <div class="mb-4">
                            <label for="repetir_password" class="form-label text-primary-dark fw-bold small mt-3">
                            Repetir contraseña
                            </label>
                            <input type="password" class="form-control rounded-pill px-3" id="repetir_password" name="repetir_password" minlength="8" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Registrarme</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="small text-muted mb-0">¿Ya tenés cuenta? <a href="<?= base_url('login') ?>" class="text-primary-dark fw-bold text-decoration-none">Iniciá sesión</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>