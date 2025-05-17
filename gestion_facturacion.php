<?php session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_nombre'])) {
    // Si no está logueado, redirigir a la página de login
    header("Location: ./index.php");
    exit;
} ?>

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
                    <a href="dashboard.php" class="nav-link ">
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
                    <a href="./gestion_reparaciones.php" class="nav-link">
                        <i class="fa-solid fa-screwdriver-wrench me-2"></i>
                        Reparaciones
                    </a>
                </li>
                <li class="nav-item">
                    <a href="./gestion_facturacion.php" class="nav-link active">
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
            <?php
            // Conexión a la base de datos
            include 'includes/config.php';

            // Consulta para obtener las facturas
            $query = "SELECT f.id, c.nombre AS cliente, f.total, f.estado_pago FROM facturas f
          JOIN ordenes_reparacion o ON f.orden_reparacion_id = o.id
          JOIN clientes c ON o.cliente_id = c.id";
            $result = $conn->query($query);

            ?>
            <main class="container-fluid py-4">
                <div class="row g-4">
                    <!-- Sección para Mostrar Facturas -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Facturas</h5>
                                <div>
                                    <button class="btn btn-violet" data-bs-toggle="modal"
                                        data-bs-target="#modalGenerarFactura">Generar Factura</button>
                                    <button class="btn btn-outline-secondary ms-2" data-bs-toggle="modal"
                                        data-bs-target="#modalFiltrosFactura">Filtrar Facturas</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Tabla de Facturas -->
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Factura ID</th>
                                            <th>Cliente</th>
                                            <th>Total</th>
                                            <th>Estado de Pago</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Verificar si hay resultados
                                        if ($result->num_rows > 0) {
                                            // Mostrar las facturas
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['cliente']}</td>
                                        <td>\${$row['total']}</td>
                                        <td>{$row['estado_pago']}</td>
                                        <td>
                                            <a href='includes/generar_pdf_factura.php?id={$row['id']}' class='btn btn-info' target='_blank'>Descargar</a>
                                        </td>
                                    </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5' class='text-center'>No hay facturas disponibles</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <?php
            // Cerrar la conexión a la base de datos
            $conn->close();
            ?>


            <?php
            include 'includes/config.php';

            // Cargar órdenes finalizadas
            $ordenes = [];
            $sql = "SELECT r.id, CONCAT('Orden #', r.id, ' - ', c.nombre) AS texto 
        FROM ordenes_reparacion r 
        JOIN clientes c ON r.cliente_id = c.id 
        WHERE r.estado_reparacion = 'finalizado'";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                $ordenes[] = $row;
            }
            ?>

            <?php
            // Aquí realizamos la consulta para obtener todas las órdenes de reparación que no tienen factura
            $ordenesQuery = "
    SELECT o.id, CONCAT('ORD-', o.id) AS texto
    FROM ordenes_reparacion o
    LEFT JOIN facturas f ON o.id = f.orden_reparacion_id
    WHERE f.id IS NULL";  // Nos aseguramos de que no tenga una factura asociada
            
            $ordenes = $conn->query($ordenesQuery)->fetch_all(MYSQLI_ASSOC);
            ?>

            <!-- Modal para Generar Factura -->
            <div class="modal fade" id="modalGenerarFactura" tabindex="-1" aria-labelledby="modalGenerarFacturaLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="includes/generar_factura.php" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalGenerarFacturaLabel">Generar Factura</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Selección de Orden -->
                            <div class="mb-3">
                                <label for="orden_reparacion" class="form-label">Orden de Reparación</label>
                                <select class="form-select" name="orden_reparacion" id="orden_reparacion" required>
                                    <option value="">Seleccione una orden</option>
                                    <?php foreach ($ordenes as $orden): ?>
                                        <option value="<?= $orden['id'] ?>"><?= $orden['texto'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Servicios Realizados -->
                            <div class="mb-3">
                                <label for="servicios" class="form-label">Servicios Realizados</label>
                                <select class="form-select" name="servicios[]" id="servicios" multiple required>
                                    <!-- Se cargarán dinámicamente por JS -->
                                </select>
                            </div>

                            <!-- Precio Total -->
                            <div class="mb-3">
                                <label for="precio_total" class="form-label">Precio Total</label>
                                <input type="text" class="form-control" name="precio_total" id="precio_total" readonly>
                            </div>

                            <!-- Método de Pago -->
                            <div class="mb-3">
                                <label for="metodo_pago" class="form-label">Método de Pago</label>
                                <select class="form-select" name="metodo_pago" id="metodo_pago" required>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia</option>
                                    <option value="tarjeta">Tarjeta</option>
                                </select>
                            </div>

                            <!-- Monto Pagado -->
                            <div class="mb-3">
                                <label for="monto_pagado" class="form-label">Monto Pagado</label>
                                <input type="number" class="form-control" name="monto_pagado" id="monto_pagado"
                                    required>
                            </div>

                            <!-- Estado de Pago -->
                            <div class="mb-3">
                                <label for="estado_pago" class="form-label">Estado de Pago</label>
                                <select class="form-select" name="estado_pago" id="estado_pago" required>
                                    <option value="pagada">Pagada</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-violet">Generar Factura</button>
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- JS para cargar servicios y total -->
            <script>
                document.getElementById("orden_reparacion").addEventListener("change", function () {
                    const ordenId = this.value;

                    if (!ordenId) return;

                    // Hacer la solicitud para cargar los detalles de la orden
                    fetch(`cargar_detalles_orden.php?orden_id=${ordenId}`)
                        .then(res => res.json())
                        .then(data => {
                            const serviciosSelect = document.getElementById("servicios");
                            const precioInput = document.getElementById("precio_total");

                            // Limpiar las opciones anteriores
                            serviciosSelect.innerHTML = "";

                            // Cargar nuevos servicios
                            if (data.servicios.length > 0) {
                                data.servicios.forEach(servicio => {
                                    const opt = document.createElement("option");
                                    opt.value = servicio.servicio_nombre;
                                    opt.selected = true;
                                    opt.textContent = `${servicio.servicio_nombre} ($${parseFloat(servicio.precio).toFixed(2)})`;
                                    serviciosSelect.appendChild(opt);
                                });
                            } else {
                                // Si no hay servicios, agregar un mensaje
                                const noServiciosOption = document.createElement("option");
                                noServiciosOption.textContent = "No hay servicios registrados para esta orden";
                                serviciosSelect.appendChild(noServiciosOption);
                            }

                            // Actualizar el precio total
                            precioInput.value = data.precio_total.toFixed(2);
                        })
                        .catch(err => console.error('Error al cargar detalles:', err));
                });
            </script>


        </div>

    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("orden_reparacion").addEventListener("change", function () {
            const ordenId = this.value;

            if (!ordenId) return; // Si no hay orden seleccionada, salimos

            fetch(`includes/cargar_detalles_orden.php?orden_id=${ordenId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        console.error(data.error); // En caso de error en el servidor
                        return;
                    }

                    const serviciosSelect = document.getElementById("servicios");
                    const precioInput = document.getElementById("precio_total");

                    // Limpiar las opciones previas del select de servicios
                    serviciosSelect.innerHTML = "";

                    // Añadir un "placeholder" para los servicios si no hay
                    if (data.servicios.length === 0) {
                        const noServiciosOption = document.createElement("option");
                        noServiciosOption.textContent = "No hay servicios registrados";
                        serviciosSelect.appendChild(noServiciosOption);
                    }

                    // Cargar nuevos servicios
                    data.servicios.forEach(servicio => {
                        const opt = document.createElement("option");
                        opt.value = servicio.servicio_nombre;
                        opt.selected = true;
                        opt.textContent = `${servicio.servicio_nombre} ($${parseFloat(servicio.precio).toFixed(2)})`;
                        serviciosSelect.appendChild(opt);
                    });

                    // Actualizar precio total
                    precioInput.value = parseFloat(data.precio_total).toFixed(2);
                })
                .catch(err => {
                    console.error('Error al cargar detalles:', err); // Manejo de errores
                });
        });
    </script>



</body>

</html>