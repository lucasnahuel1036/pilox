<footer class="bg-dark text-white pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row">
            <!--Marca y Descripción -->
            <div class="col-md-5 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <h5 class="ms-2 mb-0 fw-bold text-white">Pilox</h5>
                </div>
                <p class="text-secondary small pe-md-5">
                    Sistema de gestión para el centro deportivo Pilox.<br> Pilates, Yoga, GAP y Pilates AFA en un solo lugar.
                </p>
            </div>

            <!-- Disciplinas -->
            <div class="col-md-3 mb-4">
                <h6 class="text-uppercase fw-bold mb-3 small text-white">Disciplinas</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= base_url('/#disciplinas') ?>" class="text-secondary text-decoration-none">Pilates</a></li>
                    <li class="mb-2"><a href="<?= base_url('/#disciplinas') ?>" class="text-secondary text-decoration-none">Yoga</a></li>
                    <li class="mb-2"><a href="<?= base_url('/#disciplinas') ?>" class="text-secondary text-decoration-none">GAP</a></li>
                    <li class="mb-2"><a href="<?= base_url('/#disciplinas') ?>" class="text-secondary text-decoration-none">Pilates AFA</a></li>
                </ul>
            </div>

            <!-- Contacto -->
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

        <hr class="border-secondary mb-4 mt-2">
        <div class="text-secondary small">
            © <?= date('Y') ?> Pilox — Sistema de Gestión. Todos los derechos reservados.
        </div>
    </div>
</footer>

    <!-- Modal reutilizable de confirmación (reemplaza el confirm() nativo del navegador) -->
    <div class="modal fade" id="modalConfirmarPilox" tabindex="-1" aria-labelledby="modalConfirmarTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content pd-modal-confirm border-0">
                <div class="modal-body text-center p-4">
                    <div class="pd-modal-icono mx-auto mb-3" id="modalConfirmarIcono">
                        <i class="bi bi-question-circle"></i>
                    </div>
                    <h5 class="fw-bold text-primary-dark mb-2" id="modalConfirmarTitulo">Confirmar acción</h5>
                    <p class="text-muted small mb-4" id="modalConfirmarMensaje"></p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-custom-solid rounded-pill px-4" id="modalConfirmarBoton">
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de horarios sugeridos cada 30 min para los campos type="time" -->
    <datalist id="horariosPilox">
        <?php for ($h = 7; $h <= 22; $h++): ?>
            <option value="<?= sprintf('%02d:00', $h) ?>"></option>
            <?php if ($h < 22): ?>
                <option value="<?= sprintf('%02d:30', $h) ?>"></option>
            <?php endif; ?>
        <?php endfor; ?>
    </datalist>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Animaciones de los paneles (aparición al scrollear, contadores) -->
    <script src="<?= base_url('assets/js/panel.js') ?>"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Script para capturar los mensajes Flash de CodeIgniter y mostrarlos con SweetAlert -->
    <script>
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '<?= esc(session()->getFlashdata('success'), 'js') ?>',
                confirmButtonColor: '#1A3C34'
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= esc(session()->getFlashdata('error'), 'js') ?>',
                confirmButtonColor: '#1A3C34'
            });
        <?php endif; ?>
    </script>
</body>
</html>