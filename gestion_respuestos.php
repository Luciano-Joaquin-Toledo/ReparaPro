<?php session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_nombre'])) {
    // Si no está logueado, redirigir a la página de login
    header("Location: ./index.php");
    exit;
}?>

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
                    <a href="./gestion_reparaciones.php" class="nav-link">
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
                    <a href="./gestion_respuestos.php" class="nav-link active">
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

                    <!-- Registro de Inventario -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Gestión de Inventario</h5>
                                <button class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#modalRegistrarArticulo">Registrar Nuevo Artículo</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped" id="tablaInventario">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Proveedor</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include 'includes/config.php'; // tu archivo de conexión a la BD
                                        
                                        $sql = "SELECT id, nombre, categoria, cantidad, precio, proveedor FROM articulos";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0):
                                            while ($row = $result->fetch_assoc()):
                                                ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                                                    <td><?= htmlspecialchars($row['categoria']) ?></td>
                                                    <td><?= $row['cantidad'] ?></td>
                                                    <td>$<?= number_format($row['precio'], 2, '.', ',') ?></td>
                                                    <td><?= htmlspecialchars($row['proveedor']) ?></td>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm"
                                                            onclick="eliminarArticulo(<?= $row['id'] ?>)">Eliminar</button>
                                                    </td>
                                                </tr>
                                                <?php
                                            endwhile;
                                        else:
                                            ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No hay artículos registrados.</td>
                                            </tr>
                                        <?php endif; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal de Registro de Artículo -->
                <div class="modal fade" id="modalRegistrarArticulo" tabindex="-1"
                    aria-labelledby="modalRegistrarArticuloLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalRegistrarArticuloLabel">Registrar Nuevo Artículo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <form id="formRegistrarArticulo" method="POST" action="includes/registrar_articulo.php">
                                    <div class="mb-3">
                                        <label for="nombre_articulo" class="form-label">Nombre del Artículo</label>
                                        <input type="text" class="form-control" id="nombre_articulo"
                                            name="nombre_articulo" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="categoria_articulo" class="form-label">Categoría</label>
                                        <input type="text" class="form-control" id="categoria_articulo"
                                            name="categoria_articulo" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cantidad_articulo" class="form-label">Cantidad Disponible</label>
                                        <input type="number" class="form-control" id="cantidad_articulo"
                                            name="cantidad_articulo" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="precio_articulo" class="form-label">Precio de Adquisición</label>
                                        <input type="number" step="0.01" class="form-control" id="precio_articulo"
                                            name="precio_articulo" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="proveedor_articulo" class="form-label">Proveedor</label>
                                        <input type="text" class="form-control" id="proveedor_articulo"
                                            name="proveedor_articulo" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ubicacion_articulo" class="form-label">Ubicación en
                                            Inventario</label>
                                        <input type="text" class="form-control" id="ubicacion_articulo"
                                            name="ubicacion_articulo" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha_ingreso_articulo" class="form-label">Fecha de Ingreso</label>
                                        <input type="date" class="form-control" id="fecha_ingreso_articulo"
                                            name="fecha_ingreso_articulo" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Registrar</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>


                <!-- Reportes de Inventario -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Reportes de Inventario</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-info" onclick="generarReporteStock()">Generar Reporte de Stock</button>
                        <button class="btn btn-info" onclick="generarReporteMovimientos()">Generar Reporte de
                            Movimientos</button>
                        <button class="btn btn-info" onclick="generarReporteCostos()">Generar Reporte de Costos</button>
                    </div>
                </div>

            </main>

            <script>
                // Función para registrar un nuevo artículo
                document.getElementById("formRegistrarArticulo").addEventListener("submit", function (event) {
                    event.preventDefault();
                    // Aquí iría la lógica para registrar el artículo en el backend
                    alert("Artículo registrado con éxito.");
                    $('#modalRegistrarArticulo').modal('hide');
                });

                // Función para editar un artículo
                function editarArticulo(id) {
                    // Aquí iría la lógica para cargar la información del artículo
                    document.getElementById("articulo_id").value = id;
                    // Llenar los campos con la información del artículo
                }

                // Función para eliminar un artículo
                function eliminarArticulo(id) {
                    // Aquí iría la lógica para eliminar el artículo
                    $('#modalEliminarArticulo').modal('show');
                }

                // Confirmar eliminación de artículo
                function confirmarEliminarArticulo() {
                    // Lógica para eliminar el artículo
                    alert("Artículo eliminado con éxito.");
                    $('#modalEliminarArticulo').modal('hide');
                }

                // Generar reportes
                function generarReporteStock() {
                    alert("Generando Reporte de Stock...");
                    // Aquí iría la lógica para generar el reporte de stock
                }

                function generarReporteMovimientos() {
                    alert("Generando Reporte de Movimientos...");
                    // Aquí iría la lógica para generar el reporte de movimientos
                }

                function generarReporteCostos() {
                    alert("Generando Reporte de Costos...");
                    // Aquí iría la lógica para generar el reporte de costos
                }
            </script>

        </div>

    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById("formRegistrarArticulo").addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData();
            formData.append('nombre', document.getElementById("nombre_articulo").value);
            formData.append('categoria', document.getElementById("categoria_articulo").value);
            formData.append('cantidad', document.getElementById("cantidad_articulo").value);
            formData.append('precio', document.getElementById("precio_articulo").value);
            formData.append('proveedor', document.getElementById("proveedor_articulo").value);
            formData.append('ubicacion', document.getElementById("ubicacion_articulo").value);
            formData.append('fecha_ingreso', document.getElementById("fecha_ingreso_articulo").value);

            fetch("includes/registrar_articulo.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    if (data.includes("Artículo registrado con éxito")) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Registrado',
                            text: 'Artículo registrado con éxito',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data
                        });
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de red',
                        text: 'No se pudo registrar el artículo.'
                    });
                });
        });
    </script>

</body>

</html>