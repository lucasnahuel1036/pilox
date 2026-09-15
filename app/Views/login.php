<!-- app/Views/login.php -->
<main class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4" style="background-color: #F8F9F5;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="text-primary-dark fw-bold">Iniciar Sesión</h2>
                        <p class="text-muted small">Ingresá a tu cuenta para gestionar tus clases</p>
                    </div>
                    
                    <form action="<?= base_url('autenticar') ?>" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label text-primary-dark fw-bold small">Correo Electrónico</label>
                            <input type="email" class="form-control rounded-pill px-3" id="email" name="email" required placeholder="tu@email.com">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label text-primary-dark fw-bold small">Contraseña</label>
                            <input type="password" class="form-control rounded-pill px-3" id="password" name="password" required placeholder="********">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-custom-solid rounded-pill py-2">Ingresar</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="small text-muted mb-0">¿No tenés cuenta? <a href="<?= base_url('registro') ?>" class="text-primary-dark fw-bold text-decoration-none">Registrate acá</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>