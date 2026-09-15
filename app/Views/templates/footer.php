<!-- app/Views/templates/footer.php -->
    <footer class="bg-primary-dark text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold text-light">Pilox</h5>
                    <p class="small">Sistema de gestión para el centro deportivo Pilox. Pilates, Yoga, GAP y Pilates AFA en un solo lugar.</p>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="text-uppercase text-secondary">Disciplinas</h6>
                    <ul class="list-unstyled small">
                        <li>Pilates</li><li>Yoga</li><li>GAP</li><li>Pilates AFA</li>
                    </ul>
                </div>
                <div class="col-md-3 mb-3">
                    <h6 class="text-uppercase text-secondary">Contacto</h6>
                    <p class="small">📍 Córdoba, Argentina</p>
                </div>
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