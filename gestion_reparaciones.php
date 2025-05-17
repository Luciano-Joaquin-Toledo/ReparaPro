<?php session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_nombre'])) {
    // Si no está logueado, redirigir a la página de login
    header("Location: ./index.php");
    exit;
}?>

<?php
require_once("./includes/config.php");
// Consulta para obtener los equipos
$sqlEquipos = "SELECT id, tipo, marca, modelo FROM equipos";
$resultEquipos = $conn->query($sqlEquipos);

// Consulta para obtener los técnicos
$sqlTecnicos = "SELECT id, nombre FROM tecnicos WHERE estado = 'activo'";
$resultTecnicos = $conn->query($sqlTecnicos);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel - Sistema de Reparaciones</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

    <div class="d-flex" id="layout-wrapper">

        <!-- Sidebar Moderno Fijo con Violeta #2c0a4d -->
        <aside id="sidebar" class="sidebar d-flex flex-column p-3">
            <!-- Marca / Logo -->
            <a href="#" class="sidebar-brand mb-4 text-decoration-none d-flex align-items-center">
                <i class="fa-solid fa-screwdriver-wrench fa-2x me-2"></i>
                <span class="fs-4 fw-bold text-white">Reparaciones</span>
            </a>

            <!-- Menú de navegación -->
             <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <i class="fa-solid fa-gauge-high me-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./gestion_clientes.php" class="nav-link">
                        <i class="fa-solid fa-user me-2"></i>
                        Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./gestion_equipos.php" class="nav-link">
                        <i class="fa-solid fa-laptop-code me-2"></i>
                        Equipos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./gestion_reparaciones.php" class="nav-link active">
                        <i class="fa-solid fa-screwdriver-wrench me-2"></i>
                        Reparaciones
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./gestion_facturacion.php" class="nav-link">
                        <i class="fa-solid fa-file-invoice-dollar me-2"></i>
                        Facturaciones
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./gestion_entrega.php" class="nav-link">
                        <i class="fa-solid fa-truck-ramp-box me-2"></i>
                        Entregas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./gestion_respuestos.php" class="nav-link">
                        <i class="fa-solid fa-warehouse me-2"></i>
                        Inventario
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./gestion_tecnicos.php" class="nav-link">
                        <i class="fa-solid fa-user-gear me-2"></i>
                        Técnicos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./gestion_reportes.php" class="nav-link">
                        <i class="fa-solid fa-chart-column me-2"></i>
                        Reportes
                    </a>
                </li>
            </ul>
        </aside>


        <!-- Main Content -->
        <div class="flex-grow-1" style="margin-left: 250px;">

            <!-- Navbar Superior -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
                <div class="container-fluid">
                    <div class="ms-auto d-flex align-items-center">
                        <!-- Notificaciones -->
                        <button class="btn btn-light position-relative me-3">
                            <i class="fa-solid fa-bell"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">4</span>
                        </button>
                        <!-- Usuario -->
                         <div class="dropdown">
                            <a class="btn btn-light dropdown-toggle" href="#" id="userMenu" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-circle-user me-1"></i>
                                <?= isset($_SESSION['usuario_nombre']) ? htmlspecialchars($_SESSION['usuario_nombre']) : 'Invitado'; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">

                                <li><a class="dropdown-item text-danger" href="./logout.php">Cerrar sesión</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="container-fluid py-4">
                <div class="row g-4">

                    <!-- Card Total Reparaciones -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="stat-title">Reparaciones Totales</h6>
                                    <h3 class="stat-number">150</h3>
                                </div>
                                <div class="stat-icon bg-violet-light text-white rounded-circle">
                                    <i class="fa-solid fa-wrench fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Reparaciones -->
                    <?php
                    require_once('includes/config.php');

                    // Consulta para obtener las reparaciones
                    $sqlReparaciones = "SELECT r.id, c.nombre AS cliente, e.marca, e.modelo, r.estado_reparacion 
                    FROM ordenes_reparacion r 
                    JOIN clientes c ON r.cliente_id = c.id 
                    JOIN equipos e ON r.equipo_id = e.id
                    ORDER BY r.fecha_ingreso DESC";  // Puedes modificar el orden si es necesario
                    $resultReparaciones = $conn->query($sqlReparaciones);
                    ?>

                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-semibold mb-0">Listado de Reparaciones</h6>
                                    <button class="btn btn-violet" data-bs-toggle="modal"
                                        data-bs-target="#modalRegistrarReparacion">
                                        <i class="fa-solid fa-plus"></i> Crear Reparación
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle text-center"
                                        id="tabla-reparaciones">
                                        <thead class="table-violet text-white">
                                            <tr>
                                                <th>Cliente</th>
                                                <th>Equipo</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tbody>
                                            <?php
                                            if ($resultReparaciones->num_rows > 0) {
                                                while ($reparacion = $resultReparaciones->fetch_assoc()) {
                                                    $estado = $reparacion['estado_reparacion'];
                                                    $btnTexto = '';
                                                    $btnClase = '';

                                                    // Construyo fila
                                                    echo "<tr>
                <td>{$reparacion['cliente']}</td>
                <td>{$reparacion['marca']} {$reparacion['modelo']}</td>
                <td><span class='badge bg-" .
                                                        ($estado === 'pendiente' ? 'secondary' :
                                                            ($estado === 'en_reparacion' ? 'warning' : 'success')
                                                        ) . "'>" . ucfirst($estado) . "</span></td>
                <td>";

                                                    // Botones según estado
                                                    if ($estado === 'pendiente') {
                                                        $btnTexto = 'Iniciar Reparación';
                                                        $btnClase = 'btn-warning';
                                                        echo "<button
                    type='button'
                    class='btn {$btnClase} btn-sm'
                    data-bs-toggle='modal'
                    data-bs-target='#modalServicios'
                    onclick='abrirModalServicios(" . intval($reparacion['id']) . ")'>
                    {$btnTexto}
                  </button>";
                                                    } elseif ($estado === 'en_reparacion') {
                                                        $btnTexto = 'Finalizar Reparación';
                                                        $btnClase = 'btn-success';
                                                        echo "<button
                    type='button'
                    class='btn {$btnClase} btn-sm'
                    data-bs-toggle='modal'
                    data-bs-target='#modalRepuestos'
                    onclick='abrirModalRepuestos(" . intval($reparacion['id']) . ")'>
                    {$btnTexto}
                  </button>";
                                                    } else {
                                                        // finalizado
                                                        echo "<button
                    class='btn btn-outline-secondary btn-sm'
                    data-bs-toggle='modal'
                    data-bs-target='#modalVerReparacion'
                    data-id='" . intval($reparacion['id']) . "'>
                    Ver Detalles
                  </button>";
                                                    }

                                                    echo "</td>
             </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='4'>No hay reparaciones registradas.</td></tr>";
                                            }
                                            ?>
                                        </tbody>



                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>




                </div>
            </main>
            <!-- Modal Final de Informe -->
            <div class="modal fade" id="modalVerReparacion" tabindex="-1" aria-labelledby="modalVerReparacionLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-violet text-white">
                            <h5 class="modal-title" id="modalVerReparacionLabel">Detalles de la Reparación</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div id="informe">
                                <!-- Aquí inyectaremos con JS el HTML del informe -->
                                <p class="text-center">Cargando...</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById('modalVerReparacion')
                    .addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;
                        const id = button.getAttribute('data-id');
                        const informe = this.querySelector('#informe');

                        // Mostrar un spinner o mensaje
                        informe.innerHTML = '<p class="text-center">Cargando...</p>';

                        // Llamada AJAX
                        fetch(`includes/ajax_detalle_reparacion.php?id=${id}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.error) {
                                    informe.innerHTML = `<p class="text-danger">${data.error}</p>`;
                                    return;
                                }
                                const r = data.reparacion;
                                let html = `
            <h5>Detalles de la Reparación</h5>
            <p><strong>Descripción:</strong> ${r.descripcion}</p>
            <p><strong>Estado:</strong> ${r.estado_reparacion}</p>
            <p><strong>Fecha de Ingreso:</strong> ${r.fecha_ingreso}</p>
          `;
                                if (r.fecha_finalizacion) {
                                    html += `<p><strong>Fecha de Finalización:</strong> ${r.fecha_finalizacion}</p>`;
                                }
                                html += `
            <p><strong>Total Servicios:</strong> $${parseFloat(r.total_servicios).toFixed(2)}</p>
            <p><strong>Total Repuestos:</strong> $${parseFloat(r.total_repuestos).toFixed(2)}</p>
            <p><strong>Precio Total:</strong> $${parseFloat(r.precio_total).toFixed(2)}</p>
            <h6>Servicios:</h6>
          `;
                                data.servicios.forEach(s => {
                                    html += `<p>- ${s.nombre} $${parseFloat(s.precio).toFixed(2)}</p>`;
                                });
                                html += `<h6>Repuestos:</h6>`;
                                data.repuestos.forEach(p => {
                                    html += `<p>- ${p.nombre} x ${p.cantidad} $${parseFloat(p.costo).toFixed(2)}</p>`;
                                });
                                informe.innerHTML = html;
                            })
                            .catch(err => {
                                informe.innerHTML = `<p class="text-danger">Error al cargar datos.</p>`;
                                console.error(err);
                            });
                    });
            </script>

            <!-- Modal para Seleccionar Repuestos (Paso: En Reparación → Finalizar) -->
            <div class="modal fade" id="modalRepuestos" tabindex="-1" aria-labelledby="modalRepuestosLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form class="modal-content" id="formRepuestos" method="POST"
                        action="./includes/guardar_repuestos.php">
                        <input type="hidden" id="reparacion_id_repuestos" name="reparacion_id" value="">

                        <div class="modal-header">
                            <h5 class="modal-title" id="modalRepuestosLabel">Seleccionar Repuestos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Repuestos Disponibles cargados desde la base -->
                            <div class="mb-3">
                                <label for="repuestos" class="form-label">Repuestos Disponibles</label>
                                <select class="form-select" id="repuestos" name="repuestos[]" multiple>
                                    <?php
                                    // Conexión a la base de datos (asegúrate de tener la conexión)
                                    // require_once '../config.php';
                                    
                                    $sqlArticulos = "SELECT id, nombre, precio, cantidad FROM articulos WHERE cantidad > 0";
                                    $resArticulos = $conn->query($sqlArticulos);
                                    if ($resArticulos && $resArticulos->num_rows > 0) {
                                        while ($art = $resArticulos->fetch_assoc()) {
                                            // Sanitize output
                                            $nombre = htmlspecialchars($art['nombre']);
                                            $precio = number_format($art['precio'], 2, '.', '');
                                            $cantidad = $art['cantidad']; // Añadir la cantidad disponible
                                    
                                            // Añadir la cantidad disponible al atributo data-quantity
                                            echo "<option value=\"{$art['id']}\" data-precio=\"{$precio}\" data-cantidad=\"{$cantidad}\">{$nombre} — \${$precio}</option>";
                                        }
                                    } else {
                                        echo '<option value="">No hay repuestos disponibles</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="cantidad" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" min="0"
                                    value="0">
                            </div>

                            <div class="mb-3">
                                <label for="costo_repuesto" class="form-label">Costo Total</label>
                                <input type="text" class="form-control" id="costo_repuesto" name="costo_repuesto"
                                    readonly>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-violet">Agregar Repuesto</button>
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                // JavaScript para actualizar el máximo de cantidad según el repuesto seleccionado
                document.getElementById('repuestos').addEventListener('change', function () {
                    // Obtener la opción seleccionada
                    const selectedOption = this.options[this.selectedIndex];

                    // Obtener la cantidad disponible de la opción seleccionada
                    const availableQuantity = selectedOption.getAttribute('data-cantidad');

                    // Actualizar el valor máximo en el input de cantidad
                    const quantityInput = document.getElementById('cantidad');
                    quantityInput.setAttribute('max', availableQuantity); // Establecer el máximo
                    quantityInput.value = 1; // Reiniciar el valor a 1 (mínimo permitido)
                });
            </script>


            <script>
                // Calcular costo total cuando cambian los repuestos o la cantidad
                function actualizarCostoRepuesto() {
                    const selected = Array.from(document.getElementById('repuestos').selectedOptions);
                    const cantidad = parseInt(document.getElementById('cantidad').value) || 0;
                    let total = 0;
                    selected.forEach(opt => {
                        total += parseFloat(opt.dataset.precio) * cantidad;
                    });
                    document.getElementById('costo_repuesto').value = '$' + total.toLocaleString(undefined, { minimumFractionDigits: 2 });
                }

                // Eventos
                document.getElementById('repuestos').addEventListener('change', actualizarCostoRepuesto);
                document.getElementById('cantidad').addEventListener('input', actualizarCostoRepuesto);
            </script>



            <!-- Modal para Registrar Orden de Reparación -->
            <div class="modal fade" id="modalRegistrarReparacion" tabindex="-1"
                aria-labelledby="modalRegistrarReparacionLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form class="modal-content" id="formReparacion" method="POST"
                        action="./includes/subir_reparacion.php">
                        <input type="hidden" id="reparacion_id_repuestos" name="reparacion_id" value="">

                        <div class="modal-header">
                            <h5 class="modal-title" id="modalRegistrarReparacionLabel">Registrar Orden de Reparación
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Datos de la Orden de Reparación -->
                            <div class="mb-3">
                                <label for="cliente" class="form-label">Cliente</label>
                                <select class="form-select" id="cliente" name="cliente" required>
                                    <option value="">Seleccionar cliente...</option>
                                    <?php
                                    // Consulta para obtener los clientes
                                    $sqlClientes = "SELECT id, nombre FROM clientes";
                                    $resultClientes = $conn->query($sqlClientes);
                                    while ($cliente = $resultClientes->fetch_assoc()) {
                                        echo "<option value='" . $cliente['id'] . "'>" . $cliente['nombre'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="equipo" class="form-label">Equipo</label>
                                <select class="form-select" id="equipo" name="equipo" required>
                                    <option value="">Seleccionar equipo...</option>
                                    <?php
                                    // Verificar si hay equipos disponibles
                                    if ($resultEquipos->num_rows > 0) {
                                        // Recorrer los equipos y agregar las opciones al select
                                        while ($equipo = $resultEquipos->fetch_assoc()) {
                                            echo "<option value='" . $equipo['id'] . "'>" . $equipo['marca'] . " " . $equipo['modelo'] . " (" . $equipo['tipo'] . ")</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No hay equipos disponibles</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tecnico" class="form-label">Técnico</label>
                                <select class="form-select" id="tecnico" name="tecnico" required>
                                    <option value="">Seleccionar técnico...</option>
                                    <?php
                                    // Verificar si hay técnicos disponibles
                                    if ($resultTecnicos->num_rows > 0) {
                                        // Recorrer los técnicos y agregar las opciones al select
                                        while ($tecnico = $resultTecnicos->fetch_assoc()) {
                                            echo "<option value='" . $tecnico['id'] . "'>" . $tecnico['nombre'] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No hay técnicos disponibles</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción del Problema</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="estado_reparacion" class="form-label">Estado de la Reparación</label>
                                <select class="form-select" id="estado_reparacion" name="estado_reparacion" required>
                                    <option value="pendiente">Pendiente</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-violet">Guardar</button>
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function abrirModalServicios(ordenId) {
                    document.getElementById('orden_id').value = ordenId;
                }
            </script>

            <!-- Modal para Seleccionar Servicios (Paso: Pendiente → En Reparación) -->
            <div class="modal fade" id="modalServicios" tabindex="-1" aria-labelledby="modalServiciosLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form class="modal-content" id="formServicios">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalServiciosLabel">Seleccionar Servicios</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Servicios Disponibles -->
                            <!-- Aquí va tu formulario -->
                            <div class="mb-3">
                                <label for="servicios" class="form-label">Servicios Disponibles</label>
                                <select class="form-select" id="servicios" multiple required>
                                    <!-- Opciones de servicio (incluyendo las que hemos agregado antes) -->
                                    <option value="Diagnóstico/revisión PC-Notebook-AIO" data-precio="17375">
                                        Diagnóstico/revisión PC-Notebook-AIO</option>
                                    <option value="Formateo e instalación SO sin BackUp" data-precio="46332">Formateo e
                                        instalación SO sin BackUp</option>
                                    <option value="Limpieza + Formateo + BackUp + SSD" data-precio="63707">Limpieza +
                                        Formateo + BackUp + SSD</option>
                                    <option value="BackUp de Datos 100GB" data-precio="23166">BackUp de Datos 100GB
                                    </option>
                                    <option value="Instalación Drivers" data-precio="23166">Instalación de Drivers
                                    </option>
                                    <option value="Eliminación de malware" data-precio="23166">Eliminación de malware
                                    </option>
                                    <option value="Clonado sin errores SMART" data-precio="34749">Clonado sin errores
                                        SMART</option>
                                    <option value="Clonado con errores SMART" data-precio="52124">Clonado con errores
                                        SMART</option>
                                    <option value="Reparación inicio Windows" data-precio="40804">Reparación inicio
                                        Windows</option>
                                    <option value="Cambio de componentes hardware" data-precio="23166">Cambio de
                                        componentes hardware</option>
                                    <option value="Limpieza + Pasta térmica PC" data-precio="28958">Limpieza + Pasta
                                        térmica PC</option>
                                    <option value="Limpieza + Pasta térmica NB/AIO/Consolas" data-precio="40541">
                                        Limpieza + Pasta térmica NB/AIO/Consolas</option>
                                    <option value="Cambio de Flex Notebook" data-precio="40541">Cambio de Flex Notebook
                                    </option>
                                    <option value="Cambio de pantalla Notebook" data-precio="38224">Cambio de pantalla
                                        Notebook</option>
                                    <option value="Cambio de teclado Notebook" data-precio="34749">Cambio de teclado
                                        Notebook</option>
                                    <option value="Despiece y cambio internos NB/AIO/Consolas" data-precio="69499">
                                        Despiece y cambio internos NB/AIO/Consolas</option>
                                    <option value="Reflow Hardware-Mother" data-precio="98456">Reflow Hardware-Mother
                                    </option>
                                    <option value="Cambio de pin de carga NB/AIO" data-precio="57915">Cambio de pin de
                                        carga NB/AIO</option>

                                    <!-- Servicios para celulares -->
                                    <option value="Hard Reset + Configuración móviles/tablets" data-precio="34749">Hard
                                        Reset + Configuración móviles/tablets</option>
                                    <option value="Cambio de batería celular" data-precio="23166">Cambio de batería
                                        celular</option>
                                    <option value="Cambio de pantalla celular" data-precio="38224">Cambio de pantalla
                                        celular</option>
                                    <option value="Cambio de flex celular" data-precio="40541">Cambio de flex celular
                                    </option>
                                    <option value="Cambio de cargador celular" data-precio="28958">Cambio de cargador
                                        celular</option>
                                    <option value="Reparación de botón de inicio celular" data-precio="23166">Reparación
                                        de botón de inicio celular</option>
                                    <option value="Reparación de cámara celular" data-precio="34749">Reparación de
                                        cámara celular</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="precio_total" class="form-label">Precio Total</label>
                                <input type="text" class="form-control" id="precio_total" disabled>
                                <input type="hidden" name="orden_id" id="orden_id">

                            </div>
                            <script>
                                document.getElementById('formServicios').addEventListener('submit', function (e) {
                                    e.preventDefault();

                                    const ordenId = document.getElementById('orden_id').value;
                                    const servicios = Array.from(document.getElementById('servicios').selectedOptions).map(opt => ({
                                        nombre: opt.value,
                                        precio: parseFloat(opt.getAttribute('data-precio'))
                                    }));

                                    fetch('./includes/guardar_servicios.php', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json' },
                                        body: JSON.stringify({ orden_id: ordenId, servicios })
                                    })
                                        .then(res => res.json())
                                        .then(res => {
                                            if (res.success) {
                                                location.reload();
                                            } else {
                                                alert('Error al guardar servicios');
                                            }
                                        });
                                });
                            </script>

                            <!-- Script JavaScript -->
                            <script>
                                document.getElementById('servicios').addEventListener('change', function () {
                                    // Obtener todas las opciones seleccionadas
                                    var selectedOptions = Array.from(this.selectedOptions);
                                    var totalPrice = 0;

                                    // Recorrer las opciones seleccionadas y sumar los precios
                                    selectedOptions.forEach(function (option) {
                                        totalPrice += parseInt(option.getAttribute('data-precio'));
                                    });

                                    // Mostrar el precio total en el campo de texto
                                    document.getElementById('precio_total').value = '$' + totalPrice.toLocaleString();
                                });
                            </script>


                            <div class="modal-footer">
                                <button type="submit" class="btn btn-violet">Agregar Servicios</button>
                                <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                            </div>
                    </form>
                </div>
            </div>

            <!-- Bootstrap Bundle -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                function iniciarFlujoReparacion(button) {
                    const reparacionId = button.getAttribute('data-id');

                    // Guardar el ID donde lo necesites (input hidden, variable global, etc.)
                    document.querySelector('#formServicios').dataset.reparacionId = reparacionId;

                    // Mostrar el primer modal
                    const modalServicios = new bootstrap.Modal(document.getElementById('modalServicios'));
                    modalServicios.show();

                    // Cuando se envía el form de servicios
                    document.querySelector('#formServicios').onsubmit = function (e) {
                        e.preventDefault(); // Previene submit por ahora (si hacés AJAX, lo dejás así)
                        modalServicios.hide();

                        // Mostrar siguiente modal
                        const modalRepuestos = new bootstrap.Modal(document.getElementById('modalRepuestos'));
                        modalRepuestos.show();
                    };

                    // Cuando se envía el form de repuestos
                    document.querySelector('#formRepuestos').onsubmit = function (e) {
                        e.preventDefault(); // Previene submit por ahora
                        const modalRepuestos = bootstrap.Modal.getInstance(document.getElementById('modalRepuestos'));
                        modalRepuestos.hide();

                        // Mostrar modal final
                        const modalFinal = new bootstrap.Modal(document.getElementById('modalVerReparacion'));
                        modalFinal.show();

                        // Acá podrías cargar el informe de reparación usando AJAX si querés
                        // fetchInformeFinal(reparacionId);
                    };
                }
            </script>
            <script>
                function abrirModalRepuestos(id) {
                    // Poner el ID en el hidden
                    document.getElementById('reparacion_id_repuestos').value = id;
                    // (Opcional) log para verificar
                    console.log('Reparación seleccionada:', id);
                }
            </script>
<?php
if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = $_GET['status'];
    $message = urldecode($_GET['message']);
} else {
    $status = null;
    $message = null;
}
?>

<!-- Mostrar el SweetAlert si hay un mensaje -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php if ($status && $message): ?>
        Swal.fire({
            title: <?php echo $status === 'success' ? "'¡Éxito!'" : "'Error'"; ?>,
            text: "<?php echo $message; ?>",
            icon: <?php echo $status === 'success' ? "'success'" : "'error'"; ?>,
            confirmButtonText: 'Aceptar'
        });
    <?php endif; ?>
</script>

</body>

</html>