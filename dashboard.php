<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_nombre'])) {
    // Si no está logueado, redirigir a la página de login
    header("Location: ./index.php");
    exit;
}


require_once("includes/config.php");
// Obtener el total de órdenes
$query_orders = "SELECT COUNT(*) AS total_orders FROM ordenes_reparacion";
$result_orders = mysqli_query($conn, $query_orders);
$data_orders = mysqli_fetch_assoc($result_orders);
$total_orders = $data_orders['total_orders'];

// Obtener el total de clientes
$query_clients = "SELECT COUNT(*) AS total_clients FROM clientes";
$result_clients = mysqli_query($conn, $query_clients);
$data_clients = mysqli_fetch_assoc($result_clients);
$total_clients = $data_clients['total_clients'];

// Obtener el total de reparaciones pendientes
$query_pending = "SELECT COUNT(*) AS total_pending FROM ordenes_reparacion WHERE estado_reparacion = 'pendiente'";
$result_pending = mysqli_query($conn, $query_pending);
$data_pending = mysqli_fetch_assoc($result_pending);
$total_pending = $data_pending['total_pending'];

// Obtener el total de urgencias
$query_urgencies = "SELECT COUNT(*) AS total_urgencies FROM ordenes_reparacion WHERE estado_reparacion = 'urgencia'";
$result_urgencies = mysqli_query($conn, $query_urgencies);
$data_urgencies = mysqli_fetch_assoc($result_urgencies);
$total_urgencies = $data_urgencies['total_urgencies'];
// Obtener el total de equipos registrados
$query_equipment = "SELECT COUNT(*) AS total_equipment FROM equipos";
$result_equipment = mysqli_query($conn, $query_equipment);
$data_equipment = mysqli_fetch_assoc($result_equipment);
$total_equipment = $data_equipment['total_equipment'];

// Obtener el total de facturas
$query_invoices = "SELECT COUNT(*) AS total_invoices FROM facturas";
$result_invoices = mysqli_query($conn, $query_invoices);
$data_invoices = mysqli_fetch_assoc($result_invoices);
$total_invoices = $data_invoices['total_invoices'];

// Obtener el total de técnicos activos
$query_technicians = "SELECT COUNT(*) AS total_technicians FROM tecnicos WHERE estado = 'activo'";
$result_technicians = mysqli_query($conn, $query_technicians);
$data_technicians = mysqli_fetch_assoc($result_technicians);
$total_technicians = $data_technicians['total_technicians'];

// Obtener el total de repuestos
$query_parts = "SELECT COUNT(*) AS total_parts FROM ordenes_repuestos";
$result_parts = mysqli_query($conn, $query_parts);
$data_parts = mysqli_fetch_assoc($result_parts);
$total_parts = $data_parts['total_parts'];


$query_reparaciones = "SELECT r.id, c.nombre AS cliente, e.modelo AS equipo, t.nombre AS tecnico, r.estado_reparacion, r.fecha_ingreso 
                       FROM ordenes_reparacion r 
                       JOIN clientes c ON r.cliente_id = c.id 
                       JOIN equipos e ON r.equipo_id = e.id 
                       JOIN tecnicos t ON r.tecnico_id = t.id
                       ORDER BY r.fecha_ingreso DESC
                       LIMIT 10"; // Puedes ajustar el límite según lo necesites
$result_reparaciones = mysqli_query($conn, $query_reparaciones);

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
                    <a href="dashboard.php" class="nav-link active">
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

                    <!-- Card Órdenes -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="stat-title">Órdenes</h6>
                                        <h3 class="stat-number"><?php echo $total_orders; ?></h3>
                                        <!-- Mostrar el total de órdenes -->
                                    </div>
                                    <div class="stat-icon bg-violet-light text-white rounded-circle">
                                        <i class="fa-solid fa-file-invoice fa-2x"></i>
                                    </div>
                                </div>
                                <a href="#" class="stat-link">Ver más <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Card Clientes -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="stat-title">Clientes</h6>
                                        <h3 class="stat-number"><?php echo $total_clients; ?></h3>
                                        <!-- Mostrar el total de clientes -->
                                    </div>
                                    <div class="stat-icon bg-success-light text-white rounded-circle">
                                        <i class="fa-solid fa-users fa-2x"></i>
                                    </div>
                                </div>
                                <a href="#" class="stat-link">Ver más <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Card Pendientes -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="stat-title">Pendientes</h6>
                                        <h3 class="stat-number"><?php echo $total_pending; ?></h3>
                                        <!-- Mostrar el total de pendientes -->
                                    </div>
                                    <div class="stat-icon bg-warning-light text-white rounded-circle">
                                        <i class="fa-solid fa-clock fa-2x"></i>
                                    </div>
                                </div>
                                <a href="#" class="stat-link">Ver más <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Card Urgencias -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="stat-title">Urgencias</h6>
                                        <h3 class="stat-number"><?php echo $total_urgencies; ?></h3>
                                        <!-- Mostrar el total de urgencias -->
                                    </div>
                                    <div class="stat-icon bg-danger-light text-white rounded-circle">
                                        <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
                                    </div>
                                </div>
                                <a href="#" class="stat-link">Ver más <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="stat-title">Equipos Registrados</h6>
                                        <h3 class="stat-number"><?php echo $total_equipment; ?></h3>
                                        <!-- Mostrar el total de equipos registrados -->
                                    </div>
                                    <div class="stat-icon bg-primary-light text-white rounded-circle">
                                        <i class="fa-solid fa-desktop fa-2x"></i>
                                    </div>
                                </div>
                                <a href="#" class="stat-link">Ver más <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="stat-title">Facturas Emitidas</h6>
                                        <h3 class="stat-number"><?php echo $total_invoices; ?></h3>
                                        <!-- Mostrar el total de facturas emitidas -->
                                    </div>
                                    <div class="stat-icon bg-info-light text-white rounded-circle">
                                        <i class="fa-solid fa-file-invoice-dollar fa-2x"></i>
                                    </div>
                                </div>
                                <a href="#" class="stat-link">Ver más <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="stat-title">Técnicos Activos</h6>
                                        <h3 class="stat-number"><?php echo $total_technicians; ?></h3>
                                        <!-- Mostrar el total de técnicos activos -->
                                    </div>
                                    <div class="stat-icon bg-warning-light text-white rounded-circle">
                                        <i class="fa-solid fa-users-cog fa-2x"></i>
                                    </div>
                                </div>
                                <a href="#" class="stat-link">Ver más <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="stat-title">Repuestos Utilizados</h6>
                                        <h3 class="stat-number"><?php echo $total_parts; ?></h3>
                                        <!-- Mostrar el total de repuestos utilizados -->
                                    </div>
                                    <div class="stat-icon bg-danger-light text-white rounded-circle">
                                        <i class="fa-solid fa-wrench fa-2x"></i>
                                    </div>
                                </div>
                                <a href="#" class="stat-link">Ver más <i class="fa-solid fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cliente</th>
                                    <th>Equipo</th>
                                    <th>Técnico</th>
                                    <th>Estado</th>
                                    <th>Fecha de Ingreso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Ejecutar la consulta SQL para obtener las últimas reparaciones
                                while ($row = mysqli_fetch_assoc($result_reparaciones)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['cliente'] . "</td>";
                                    echo "<td>" . $row['equipo'] . "</td>";
                                    echo "<td>" . $row['tecnico'] . "</td>";
                                    echo "<td>" . ucfirst($row['estado_reparacion']) . "</td>"; // ucfirst para poner la primera letra en mayúscula
                                    echo "<td>" . $row['fecha_ingreso'] . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </main>


        </div>

    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>