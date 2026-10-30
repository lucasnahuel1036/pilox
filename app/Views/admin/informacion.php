<?php
// ---- Utilidades de formato para esta vista ----
$dinero = fn($n) => '$' . number_format((float) $n, 0, ',', '.');
$entero = fn($n) => number_format((int) $n, 0, ',', '.');
$porc   = fn($n) => number_format((float) $n, 1, ',', '.') . '%';
$fechaCorta = fn($f) => $f ? date('d/m/Y', strtotime($f)) : '—';

$valorKpi = fn($valor, $tipo) => $tipo === 'dinero' ? $dinero($valor) : ($tipo === 'porcentaje' ? $porc($valor) : $entero($valor));

// Textos del filtro que se muestran en cada sección
$periodoTexto   = $fechaCorta($desde) . ' al ' . $fechaCorta($hasta);
$nombreSucursal = 'Todas las sucursales';
foreach ($sucursales as $suc) {
    if ($suc['id_sucursal'] == $idSucursal) {
        $nombreSucursal = $suc['nombre'];
    }
}

// Color de la barra según el porcentaje (ocupación y asistencia)
$colorBarra = function ($pct) {
    if ($pct >= 70) { return 'barra-alta'; }
    if ($pct >= 40) { return 'barra-media'; }
    return 'barra-baja';
};

// Encabezado de cada sección: icono, título, la pregunta que responde y qué filtros usa
$encabezado = function ($icono, $titulo, $pregunta, $alcance) {
    ?>
    <div class="seccion-encabezado">
        <div class="d-flex align-items-center gap-3">
            <div class="seccion-icono"><?= $icono ?></div>
            <div>
                <h4 class="mb-0 text-primary-dark fw-bold"><?= esc($titulo) ?></h4>
                <div class="text-muted small"><?= esc($pregunta) ?></div>
            </div>
        </div>
        <span class="badge-alcance"><?= esc($alcance) ?></span>
    </div>
    <?php
};

// Tarjeta principal de la sección: grande y en verde (los números se animan al cargar)
$kpiHero = function ($etiqueta, $valor, $tipo, $detalle = '', $pct = null) use ($valorKpi) {
    $decimales = $tipo === 'porcentaje' ? 1 : 0;
    ?>
    <div class="col-lg-5">
        <div class="kpi-hero pd-reveal">
            <div class="kpi-hero-etiqueta"><?= esc($etiqueta) ?></div>
            <div class="kpi-hero-valor">
                <span class="contador" data-valor="<?= (float) $valor ?>" data-tipo="<?= $tipo ?>" data-decimales="<?= $decimales ?>"><?= $valorKpi($valor, $tipo) ?></span>
            </div>
            <?php if ($pct !== null): ?>
                <div class="progress kpi-hero-barra">
                    <div class="progress-bar" role="progressbar" style="width: <?= min(100, (float) $pct) ?>%"></div>
                </div>
            <?php endif; ?>
            <?php if ($detalle !== ''): ?>
                <div class="kpi-hero-detalle"><?= esc($detalle) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php
};

// Tarjeta secundaria: blanca y más chica
$kpiMini = function ($etiqueta, $valor, $tipo, $detalle = '', $clase = '') use ($valorKpi) {
    $decimales = $tipo === 'porcentaje' ? 1 : 0;
    ?>
    <div class="col-12 col-sm-4">
        <div class="kpi-mini pd-reveal <?= $clase ?>" style="--pd-delay: .06s">
            <div class="kpi-mini-etiqueta"><?= esc($etiqueta) ?></div>
            <div class="kpi-mini-valor">
                <span class="contador" data-valor="<?= (float) $valor ?>" data-tipo="<?= $tipo ?>" data-decimales="<?= $decimales ?>"><?= $valorKpi($valor, $tipo) ?></span>
            </div>
            <?php if ($detalle !== ''): ?>
                <div class="kpi-mini-detalle"><?= esc($detalle) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php
};

// Barra de progreso con el porcentaje al costado
$barra = function ($pct) use ($colorBarra, $porc) {
    ?>
    <div class="d-flex align-items-center gap-2">
        <div class="progress barra-metrica flex-grow-1">
            <div class="progress-bar <?= $colorBarra($pct) ?>" role="progressbar"
                 style="width: <?= min(100, (float) $pct) ?>%"
                 aria-valuenow="<?= (float) $pct ?>" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <span class="small fw-bold barra-valor"><?= $porc($pct) ?></span>
    </div>
    <?php
};

// Tarjeta con tabla de ocupación (actividad, sucursal o franja horaria)
$tablaOcupacion = function ($titulo, $columna, $filas) use ($barra, $entero) {
    ?>
    <div class="card border-0 shadow-sm rounded-4 h-100 pd-reveal">
        <div class="card-body p-4">
            <h6 class="fw-bold text-primary-dark mb-3"><?= esc($titulo) ?></h6>
            <?php if (empty($filas)): ?>
                <p class="text-muted small mb-0">No hay turnos en el período elegido.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle tabla-informe mb-0">
                        <thead>
                            <tr>
                                <th><?= esc($columna) ?></th>
                                <th class="text-center">Turnos</th>
                                <th class="text-center">Cupos usados</th>
                                <th style="min-width: 150px;">Ocupación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filas as $fila): ?>
                                <tr>
                                    <td class="fw-bold text-primary-dark"><?= esc($fila['etiqueta']) ?></td>
                                    <td class="text-center"><?= $entero($fila['turnos']) ?></td>
                                    <td class="text-center small"><?= $entero($fila['ocupados']) ?> / <?= $entero($fila['capacidad']) ?></td>
                                    <td><?php $barra($fila['pct']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
};
?>
<link rel="stylesheet" href="<?= base_url('assets/css/informe.css') ?>">

<main class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-primary-dark fw-bold mb-0">Información General</h2>
            <p class="text-muted small mb-0">Métricas del centro para tomar decisiones</p>
        </div>
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Volver al Panel</a>
    </div>

    <!-- Filtros -->
    <div class="card border-0 shadow-sm rounded-4 pd-reveal" style="background-color: #F8F9F5;">
        <div class="card-body p-4">
            <form action="<?= base_url('admin/informacion') ?>" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="desde" class="form-label text-primary-dark small fw-bold">Desde</label>
                    <input type="date" class="form-control rounded-pill px-3" id="desde" name="desde" value="<?= esc($desde) ?>">
                </div>
                <div class="col-md-3">
                    <label for="hasta" class="form-label text-primary-dark small fw-bold">Hasta</label>
                    <input type="date" class="form-control rounded-pill px-3" id="hasta" name="hasta" value="<?= esc($hasta) ?>">
                </div>
                <div class="col-md-3">
                    <label for="id_sucursal" class="form-label text-primary-dark small fw-bold">Sucursal</label>
                    <select class="form-select rounded-pill px-3" id="id_sucursal" name="id_sucursal">
                        <option value="0">Todas</option>
                        <?php foreach ($sucursales as $suc): ?>
                            <option value="<?= $suc['id_sucursal'] ?>" <?= $idSucursal == $suc['id_sucursal'] ? 'selected' : '' ?>>
                                <?= esc($suc['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-custom-solid rounded-pill px-4">Aplicar</button>
                    <a href="<?= base_url('admin/informacion') ?>" class="btn btn-outline-secondary rounded-pill px-3">Mes actual</a>
                </div>
            </form>
            <div class="form-text mt-3">
                Cada sección indica arriba a la derecha qué filtros usa: algunas respetan el período y la sucursal,
                y otras muestran el total o el estado de hoy.
            </div>
        </div>
    </div>

    <!-- Índice de accesos rápidos -->
    <nav class="indice-informe" aria-label="Secciones de la página">
        <a href="#ingresos">💰 Ingresos</a>
        <a href="#ocupacion">🏋️ Ocupación</a>
        <a href="#asistencia">✅ Asistencia</a>
        <a href="#reservas">📅 Reservas</a>
        <a href="#alumnos">👥 Alumnos</a>
    </nav>

    <!-- ===================== INGRESOS ===================== -->
    <section id="ingresos" class="panel-seccion pd-reveal">
        <?php $encabezado('💰', 'Ingresos', '¿Cuánto entró y cuánto falta cobrar?', 'Período: ' . $periodoTexto . ' · Todas las sucursales'); ?>
        <p class="nota-seccion">
            ℹ️ Los abonos sirven en todas las sucursales, por eso lo cobrado no se separa por sucursal.
            Se cuenta por fecha de pago.
        </p>

        <div class="row g-3 mb-4">
            <?php $kpiHero('Cobrado en el período', $ingresos['total']['monto'], 'dinero', $entero($ingresos['total']['cantidad']) . ' abonos cobrados'); ?>
            <div class="col-lg-7">
                <div class="row g-3 h-100">
                    <?php $kpiMini('Abonos cobrados', $ingresos['total']['cantidad'], 'entero', 'En el período'); ?>
                    <?php $kpiMini('Deuda pendiente', $deuda['resumen']['monto'], 'dinero', 'Abonos sin pagar, a hoy', 'kpi-alerta'); ?>
                    <?php $kpiMini('Abonos con deuda', $deuda['resumen']['cantidad'], 'entero', 'A hoy', 'kpi-alerta'); ?>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary-dark mb-3">Cobrado en los últimos 6 meses</h6>
                        <div class="grafico-contenedor"><canvas id="graficoIngresos"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary-dark mb-3">Por tipo de abono (período elegido)</h6>
                        <?php if (empty($ingresos['por_tipo'])): ?>
                            <p class="text-muted small mb-0">No hay abonos cobrados en el período.</p>
                        <?php else: ?>
                            <table class="table table-sm align-middle tabla-informe mb-0">
                                <thead>
                                    <tr><th>Abono</th><th class="text-center">Vendidos</th><th class="text-end">Monto</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ingresos['por_tipo'] as $tipo): ?>
                                        <tr>
                                            <td class="fw-bold text-primary-dark"><?= $tipo['clases'] ?> clases</td>
                                            <td class="text-center"><?= $entero($tipo['cantidad']) ?></td>
                                            <td class="text-end"><?= $dinero($tipo['monto']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold text-primary-dark mb-3">Pagos pendientes</h6>
                <?php if (empty($deuda['lista'])): ?>
                    <p class="text-muted small mb-0">No hay abonos pendientes de pago.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle tabla-informe mb-0">
                            <thead>
                                <tr><th>Alumno</th><th>DNI</th><th>Abono</th><th>Desde</th><th class="text-end">Monto</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($deuda['lista'] as $d): ?>
                                    <tr>
                                        <td class="fw-bold text-primary-dark"><?= esc($d['apellido'] . ', ' . $d['nombre']) ?></td>
                                        <td><?= esc($d['dni']) ?></td>
                                        <td><?= $d['clases_totales'] ?> clases</td>
                                        <td><?= $fechaCorta($d['fecha_inicio']) ?></td>
                                        <td class="text-end"><?= $dinero($d['precio']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($deuda['resumen']['cantidad'] > count($deuda['lista'])): ?>
                        <p class="text-muted small mt-2 mb-0">
                            Se muestran los 10 más antiguos de <?= $entero($deuda['resumen']['cantidad']) ?>.
                            Podés regularizarlos desde Abonos.
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ===================== OCUPACIÓN ===================== -->
    <section id="ocupacion" class="panel-seccion pd-reveal">
        <?php $encabezado('🏋️', 'Ocupación de las clases', '¿Qué clases se llenan y cuáles quedan vacías?', 'Período: ' . $periodoTexto . ' · ' . $nombreSucursal); ?>

        <div class="row g-3 mb-4">
            <?php $kpiHero('Ocupación general', $ocupacion['pct'], 'porcentaje', $entero($ocupacion['ocupados']) . ' de ' . $entero($ocupacion['capacidad']) . ' cupos usados', $ocupacion['pct']); ?>
            <div class="col-lg-7">
                <div class="row g-3 h-100">
                    <?php $kpiMini('Turnos del período', $ocupacion['turnos'], 'entero'); ?>
                    <?php $kpiMini('Turnos llenos', $ocupacion['llenos'], 'entero', 'Sin cupos disponibles'); ?>
                    <?php $kpiMini('Turnos casi vacíos', $ocupacion['casi_vacios'], 'entero', 'Menos del ' . \App\Models\InformeModel::UMBRAL_CASI_VACIO . '% de ocupación', 'kpi-alerta'); ?>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary-dark mb-3">Ocupación por día de la semana</h6>
                        <?php if (empty($ocupacionDia)): ?>
                            <p class="text-muted small mb-0">No hay turnos en el período elegido.</p>
                        <?php else: ?>
                            <div class="grafico-contenedor"><canvas id="graficoOcupacionDia"></canvas></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <?php $tablaOcupacion('Ocupación por franja horaria', 'Franja', $ocupacionFranja); ?>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <?php $tablaOcupacion('Ocupación por actividad', 'Actividad', $ocupacionActividad); ?>
            </div>
            <div class="col-lg-6">
                <?php $tablaOcupacion('Ocupación por sucursal', 'Sucursal', $ocupacionSucursal); ?>
            </div>
        </div>
    </section>

    <!-- ===================== ASISTENCIA ===================== -->
    <section id="asistencia" class="panel-seccion pd-reveal">
        <?php $encabezado('✅', 'Asistencia', '¿Los alumnos vienen a lo que reservan?', 'Período: ' . $periodoTexto . ' · ' . $nombreSucursal); ?>
        <p class="nota-seccion">ℹ️ Solo cuenta las clases que ya se realizaron. El porcentaje se calcula sobre la asistencia ya registrada.</p>

        <div class="row g-3 mb-4">
            <?php $kpiHero('Asistencia', $asistencia['pct'], 'porcentaje', $entero($asistencia['presentes']) . ' presentes de ' . $entero($asistencia['presentes'] + $asistencia['ausentes']) . ' registrados', $asistencia['pct']); ?>
            <div class="col-lg-7">
                <div class="row g-3 h-100">
                    <?php $kpiMini('Presentes', $asistencia['presentes'], 'entero'); ?>
                    <?php $kpiMini('Ausentes', $asistencia['ausentes'], 'entero'); ?>
                    <?php $kpiMini('Sin registrar', $asistencia['sin_registrar'], 'entero', 'En clases ya realizadas', 'kpi-alerta'); ?>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary-dark mb-3">Asistencia por actividad</h6>
                        <?php if (empty($asistenciaActividad)): ?>
                            <p class="text-muted small mb-0">Todavía no hay clases realizadas en el período.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle tabla-informe mb-0">
                                    <thead>
                                        <tr>
                                            <th>Actividad</th>
                                            <th class="text-center">Presentes</th>
                                            <th class="text-center">Ausentes</th>
                                            <th style="min-width: 150px;">Asistencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($asistenciaActividad as $fila): ?>
                                            <tr>
                                                <td class="fw-bold text-primary-dark"><?= esc($fila['etiqueta']) ?></td>
                                                <td class="text-center"><?= $entero($fila['presentes']) ?></td>
                                                <td class="text-center"><?= $entero($fila['ausentes']) ?></td>
                                                <td><?php $barra($fila['pct']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary-dark mb-3">Clases con asistencia sin registrar</h6>
                        <?php if (empty($sinAsistencia)): ?>
                            <p class="text-muted small mb-0">Todas las clases realizadas tienen su asistencia registrada.</p>
                        <?php else: ?>
                            <table class="table table-sm align-middle tabla-informe mb-0">
                                <thead>
                                    <tr><th>Docente</th><th class="text-end">Clases pendientes</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sinAsistencia as $fila): ?>
                                        <tr>
                                            <td class="fw-bold text-primary-dark"><?= esc($fila['apellido'] . ', ' . $fila['nombre']) ?></td>
                                            <td class="text-end"><span class="badge bg-warning text-dark"><?= $entero($fila['clases']) ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== RESERVAS ===================== -->
    <section id="reservas" class="panel-seccion pd-reveal">
        <?php $encabezado('📅', 'Reservas', '¿Cuánto se reserva y cuánto se cancela?', 'Período: ' . $periodoTexto . ' · ' . $nombreSucursal); ?>

        <div class="row g-3">
            <?php $kpiHero('Reservas del período', $reservas['total'], 'entero', 'Sobre los turnos del período'); ?>
            <div class="col-lg-7">
                <div class="row g-3 h-100">
                    <?php $kpiMini('Confirmadas', $reservas['confirmadas'], 'entero'); ?>
                    <?php $kpiMini('Canceladas', $reservas['canceladas'], 'entero'); ?>
                    <?php $kpiMini('Cancelaciones', $reservas['pct_cancelacion'], 'porcentaje', 'Del total de reservas', 'kpi-alerta'); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== ALUMNOS ===================== -->
    <section id="alumnos" class="panel-seccion pd-reveal">
        <?php $encabezado('👥', 'Alumnos', '¿A quién hay que renovarle o cobrarle?', 'Al día de hoy · Todas las sucursales'); ?>

        <div class="row g-3 mb-4">
            <?php $kpiHero('Alumnos activos', $alumnos['activos'], 'entero', 'Con la cuenta habilitada'); ?>
            <div class="col-lg-7">
                <div class="row g-3 h-100">
                    <?php $kpiMini('Morosos', $alumnos['morosos'], 'entero', '', 'kpi-alerta'); ?>
                    <?php $kpiMini('Inactivos', $alumnos['inactivos'], 'entero'); ?>
                    <?php $kpiMini('Activos sin abono vigente', count($alumnos['sin_abono']), 'entero', 'Para ofrecerles renovar', 'kpi-alerta'); ?>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary-dark mb-3">Abonos que vencen en los próximos 7 días</h6>
                        <?php if (empty($alumnos['por_vencer'])): ?>
                            <p class="text-muted small mb-0">No hay abonos por vencer en la próxima semana.</p>
                        <?php else: ?>
                            <table class="table table-sm align-middle tabla-informe mb-0">
                                <thead>
                                    <tr><th>Alumno</th><th>Vence</th><th class="text-center">Clases sin usar</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alumnos['por_vencer'] as $a): ?>
                                        <tr>
                                            <td class="fw-bold text-primary-dark"><?= esc($a['apellido'] . ', ' . $a['nombre']) ?></td>
                                            <td><?= $fechaCorta($a['fecha_fin']) ?></td>
                                            <td class="text-center"><?= $a['clases_restantes'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-primary-dark mb-3">Alumnos activos sin abono vigente</h6>
                        <?php if (empty($alumnos['sin_abono'])): ?>
                            <p class="text-muted small mb-0">Todos los alumnos activos tienen un abono vigente.</p>
                        <?php else: ?>
                            <table class="table table-sm align-middle tabla-informe mb-0">
                                <thead>
                                    <tr><th>Alumno</th><th>DNI</th><th>Último vencimiento</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($alumnos['sin_abono'], 0, 10) as $a): ?>
                                        <tr>
                                            <td class="fw-bold text-primary-dark"><?= esc($a['apellido'] . ', ' . $a['nombre']) ?></td>
                                            <td><?= esc($a['dni']) ?></td>
                                            <td><?= $a['ultimo_vencimiento'] ? $fechaCorta($a['ultimo_vencimiento']) : 'Sin abonos' ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php if (count($alumnos['sin_abono']) > 10): ?>
                                <p class="text-muted small mt-2 mb-0">
                                    Se muestran 10 de <?= $entero(count($alumnos['sin_abono'])) ?>. Los demás se ven en Alumnos.
                                </p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Chart.js (misma CDN que Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.js"></script>
<script>
(function () {
    // Mismo formato que number_format de PHP: miles con punto y decimales con coma
    function formatear(n, decimales) {
        const partes = Number(n).toFixed(decimales).split('.');
        partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        return partes.join(',');
    }

    // ---- Contadores animados: el HTML ya trae el valor final, se anima solo si el navegador lo permite ----
    const reducirMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('.contador').forEach(function (el) {
        const destino   = parseFloat(el.dataset.valor);
        const decimales = parseInt(el.dataset.decimales, 10) || 0;
        const tipo      = el.dataset.tipo;

        if (reducirMovimiento || isNaN(destino) || destino === 0) { return; }

        function pintar(valor) {
            const texto = formatear(valor, decimales);
            el.textContent = tipo === 'dinero' ? '$' + texto : (tipo === 'porcentaje' ? texto + '%' : texto);
        }

        const inicio   = performance.now();
        const duracion = 900;

        function paso(ahora) {
            const avance = Math.min((ahora - inicio) / duracion, 1);
            pintar(destino * (1 - Math.pow(1 - avance, 3)));   // arranca rápido y frena al final
            if (avance < 1) { requestAnimationFrame(paso); } else { pintar(destino); }
        }

        pintar(0);
        requestAnimationFrame(paso);
    });

    // ---- Gráficos ----
    function sinGrafico(canvas) {
        canvas.parentElement.innerHTML = '<p class="text-muted small mb-0">No se pudo cargar el gráfico. Los datos están en las tarjetas y tablas.</p>';
    }

    const lienzoIngresos  = document.getElementById('graficoIngresos');
    const lienzoOcupacion = document.getElementById('graficoOcupacionDia');

    if (typeof Chart === 'undefined') {
        if (lienzoIngresos) { sinGrafico(lienzoIngresos); }
        if (lienzoOcupacion) { sinGrafico(lienzoOcupacion); }
        return;
    }

    Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
    Chart.defaults.color = '#5c6b66';

    // Ingresos de los últimos 6 meses
    const ingresosMes = <?= json_encode($ingresosMes, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;

    new Chart(lienzoIngresos, {
        type: 'bar',
        data: {
            labels: ingresosMes.etiquetas,
            datasets: [{ label: 'Cobrado', data: ingresosMes.valores, backgroundColor: '#1A3C34', borderRadius: 6, maxBarThickness: 52 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: function (ctx) { return ' $' + formatear(ctx.parsed.y, 0); } } }
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, ticks: { callback: function (v) { return '$' + formatear(v, 0); } } }
            }
        }
    });

    // Ocupación por día de la semana
    <?php if (!empty($ocupacionDia)): ?>
    const ocupacionDia = {
        etiquetas: <?= json_encode(array_column($ocupacionDia, 'etiqueta'), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>,
        pct:       <?= json_encode(array_map('floatval', array_column($ocupacionDia, 'pct'))) ?>,
        ocupados:  <?= json_encode(array_map('intval', array_column($ocupacionDia, 'ocupados'))) ?>,
        capacidad: <?= json_encode(array_map('intval', array_column($ocupacionDia, 'capacidad'))) ?>
    };

    new Chart(lienzoOcupacion, {
        type: 'bar',
        data: {
            labels: ocupacionDia.etiquetas,
            datasets: [{ label: 'Ocupación', data: ocupacionDia.pct, backgroundColor: '#C5E063', borderColor: '#1A3C34', borderWidth: 1.5, borderRadius: 6, maxBarThickness: 52 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            const i = ctx.dataIndex;
                            return ' ' + formatear(ctx.parsed.y, 1) + '% (' + ocupacionDia.ocupados[i] + ' de ' + ocupacionDia.capacidad[i] + ' cupos)';
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, max: 100, ticks: { callback: function (v) { return v + '%'; } } }
            }
        }
    });
    <?php endif; ?>
})();
</script>
