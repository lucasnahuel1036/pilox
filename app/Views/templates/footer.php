<!-- app/Views/templates/footer.php -->
<footer class="bg-dark text-white pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row">
            <!-- Columna 1: Marca y Descripción -->
            <div class="col-md-5 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="ms-2 mb-0 fw-bold text-white">Pilox</h5>
                </div>
                <p class="text-secondary small pe-md-5">
                    Sistema de gestión para el centro deportivo Pilox.<br> Pilates, Yoga, GAP y Pilates AFA en un solo lugar.
                </p>
            </div>

            <!-- Columna 2: Disciplinas -->
            <div class="col-md-3 mb-4">
                <h6 class="text-uppercase fw-bold mb-3 small text-white">Disciplinas</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= base_url('/#disciplinas') ?>" class="text-secondary text-decoration-none">Pilates</a></li>
                    <li class="mb-2"><a href="<?= base_url('/#disciplinas') ?>" class="text-secondary text-decoration-none">Yoga</a></li>
                    <li class="mb-2"><a href="<?= base_url('/#disciplinas') ?>" class="text-secondary text-decoration-none">GAP</a></li>
                    <li class="mb-2"><a href="<?= base_url('/#disciplinas') ?>" class="text-secondary text-decoration-none">Pilates AFA</a></li>
                </ul>
            </div>

            <!-- Columna 3: Contacto -->
            <div class="col-md-4 mb-4">
                <h6 class="text-uppercase fw-bold mb-3 small text-white">Contacto</h6>
                <ul class="list-unstyled text-secondary small">
                    <li class="mb-3 d-flex">
                        <i class="bi bi-geo-alt me-2 fs-6"></i> 
                        <span>Av. Cornelio Saavedra 3663,<br>Marqués de Sobremonte, Córdoba</span>
                    </li>
                    <li class="mb-2">
                        <a href="https://www.instagram.com/pilox.cba" target="_blank" class="text-secondary text-decoration-none d-flex align-items-center">
                            <i class="bi bi-instagram me-2 fs-6"></i> 
                            <span>@pilox.cba</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Línea divisoria y Copyright -->
        <hr class="border-secondary mb-4 mt-2">
        <div class="text-secondary small">
            © <?= date('Y') ?> Pilox — Sistema de Gestión. Todos los derechos reservados.
        </div>
    </div>
</footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script para capturar los mensajes Flash de CodeIgniter y mostrarlos con SweetAlert -->
    <script>
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '<?= session()->getFlashdata('success') ?>',
                confirmButtonColor: '#1A3C34'
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonColor: '#1A3C34'
            });
        <?php endif; ?>
    </script>
</body>
</html>