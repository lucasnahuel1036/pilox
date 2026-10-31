/* public/assets/js/panel.js
   Animaciones sutiles de los dashboards y modal de confirmación. Respeta "reducir movimiento" del sistema.
   - .pd-reveal         → aparece al entrar en pantalla (con --pd-delay opcional)
   - [data-contar]      → número que cuenta desde 0 hasta su valor
   - [data-anillo]      → anillo SVG de progreso (valor de 0 a 1)
   - [data-confirmar]   → muestra el modal de confirmación antes de enviar un <form>
*/
(function () {
    'use strict';

    var reducir = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var hayObserver = 'IntersectionObserver' in window;

    // ---------- Modal de confirmación para formularios ----------
    function iniciarConfirmaciones() {
        var modalEl = document.getElementById('modalConfirmarPilox');
        if (!modalEl || typeof bootstrap === 'undefined') { return; }

        var tituloEl  = document.getElementById('modalConfirmarTitulo');
        var mensajeEl = document.getElementById('modalConfirmarMensaje');
        var iconoEl   = document.getElementById('modalConfirmarIcono');
        var botonEl   = document.getElementById('modalConfirmarBoton');
        var formPendiente = null;

        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!form || !form.hasAttribute('data-confirmar')) { return; }

            // Si otra validación previa canceló el envío (ej. falta elegir alumno), no abrimos el modal
            if (e.defaultPrevented) { return; }

            e.preventDefault();
            formPendiente = form;

            var mensaje = form.getAttribute('data-confirmar') || '¿Estás seguro de continuar?';
            var titulo  = form.getAttribute('data-confirmar-titulo') || 'Confirmar acción';
            var textoBt = form.getAttribute('data-confirmar-boton') || 'Confirmar';
            var tipo    = form.getAttribute('data-confirmar-tipo') || 'primario';

            tituloEl.textContent  = titulo;
            mensajeEl.textContent = mensaje;
            botonEl.textContent   = textoBt;

            if (tipo === 'peligro') {
                iconoEl.className = 'pd-modal-icono pd-modal-icono-peligro mx-auto mb-3';
                iconoEl.innerHTML = '<i class="bi bi-exclamation-triangle"></i>';
                botonEl.className = 'btn btn-danger rounded-pill px-4';
            } else {
                iconoEl.className = 'pd-modal-icono mx-auto mb-3';
                iconoEl.innerHTML = '<i class="bi bi-check2-circle"></i>';
                botonEl.className = 'btn btn-custom-solid rounded-pill px-4';
            }

            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        });

        botonEl.addEventListener('click', function () {
            if (!formPendiente) { return; }
            var form = formPendiente;
            formPendiente = null;
            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            form.submit();
        });
    }

    // ---------- Contador animado ----------
    function contar(el) {
        var destino = parseInt(el.getAttribute('data-contar'), 10);
        if (isNaN(destino) || destino <= 0 || reducir) { return; }

        var duracion = 900;
        var inicio = null;

        function paso(t) {
            if (!inicio) { inicio = t; }
            var avance = Math.min((t - inicio) / duracion, 1);
            var suavizado = 1 - Math.pow(1 - avance, 3);
            el.textContent = Math.round(destino * suavizado);
            if (avance < 1) { requestAnimationFrame(paso); }
        }

        el.textContent = '0';
        requestAnimationFrame(paso);
    }

    // ---------- Anillo de progreso ----------
    function prepararAnillo(circulo) {
        var radio = parseFloat(circulo.getAttribute('r'));
        var largo = 2 * Math.PI * radio;
        circulo.style.strokeDasharray = largo;
        circulo.style.strokeDashoffset = largo;   // arranca vacío
        circulo._largo = largo;
    }

    function llenarAnillo(circulo) {
        var valor = Math.max(0, Math.min(1, parseFloat(circulo.getAttribute('data-anillo')) || 0));
        circulo.style.strokeDashoffset = circulo._largo * (1 - valor);
    }

    // ---------- Auto-scroll al listado en celular tras buscar o paginar ----------
    function autoScrollListadoCelular() {
        if (window.innerWidth >= 768 || window.location.hash) { return; }
        var params = new URLSearchParams(window.location.search);
        var destino = null;

        if (params.has('page_historial')) {
            destino = document.getElementById('seccion-historial');
        } else if (params.has('q') || params.has('ver') || params.has('page') || params.has('page_vigentes')) {
            destino = document.getElementById('seccion-listado') || document.getElementById('seccion-vigentes');
        }

        if (destino) {
            setTimeout(function () {
                destino.scrollIntoView({ behavior: reducir ? 'auto' : 'smooth', block: 'start' });
            }, 100);
        }
    }

    // ---------- Arranque ----------
    function iniciar() {
        iniciarConfirmaciones();
        autoScrollListadoCelular();

        var revelar = document.querySelectorAll('.pd-reveal');
        var contadores = document.querySelectorAll('[data-contar]');
        var anillos = document.querySelectorAll('[data-anillo]');

        anillos.forEach(prepararAnillo);

        // Sin soporte o con movimiento reducido: mostramos todo directamente
        if (reducir || !hayObserver) {
            revelar.forEach(function (el) { el.classList.add('pd-visible'); });
            anillos.forEach(llenarAnillo);
            return;
        }

        var observer = new IntersectionObserver(function (entradas) {
            entradas.forEach(function (entrada) {
                if (!entrada.isIntersecting) { return; }
                var el = entrada.target;

                el.classList.add('pd-visible');
                el.querySelectorAll('[data-contar]').forEach(contar);
                el.querySelectorAll('[data-anillo]').forEach(function (c) {
                    setTimeout(function () { llenarAnillo(c); }, 150);
                });

                observer.unobserve(el);
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        revelar.forEach(function (el) { observer.observe(el); });

        // Contadores y anillos que no estén dentro de un .pd-reveal
        contadores.forEach(function (el) { if (!el.closest('.pd-reveal')) { contar(el); } });
        anillos.forEach(function (c) { if (!c.closest('.pd-reveal')) { llenarAnillo(c); } });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', iniciar);
    } else {
        iniciar();
    }
})();

