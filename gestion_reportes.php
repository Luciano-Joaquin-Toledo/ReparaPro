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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                    <a href="./gestion_reportes.php" class="nav-link active">
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
            <?php
            require_once("includes/config.php");
            // Consultar reparaciones
            $sql_reparaciones = "SELECT estado_reparacion, COUNT(*) as cantidad FROM ordenes_reparacion GROUP BY estado_reparacion";
            $result_reparaciones = $conn->query($sql_reparaciones);
            $reparaciones = [];
            if ($result_reparaciones->num_rows > 0) {
                while ($row = $result_reparaciones->fetch_assoc()) {
                    $reparaciones[] = $row;
                }
            }

            // Consultar entregas
            $sql_entregas = "SELECT metodo_entrega, COUNT(*) as cantidad FROM entregas GROUP BY metodo_entrega";
            $result_entregas = $conn->query($sql_entregas);
            $entregas = [];
            if ($result_entregas->num_rows > 0) {
                while ($row = $result_entregas->fetch_assoc()) {
                    $entregas[] = $row;
                }
            }

            // Consultar equipos
            $sql_equipos = "SELECT tipo, COUNT(*) as cantidad FROM equipos GROUP BY tipo";
            $result_equipos = $conn->query($sql_equipos);
            $equipos = [];
            if ($result_equipos->num_rows > 0) {
                while ($row = $result_equipos->fetch_assoc()) {
                    $equipos[] = $row;
                }
            }
            ?>

            <!-- Main Content -->
            <main class="container-fluid py-4">
                <div class="row g-4">
                    <!-- Reparaciones por Estado -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Reparaciones por Estado</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle text-center">
                                        <thead class="table-violet text-white">
                                            <tr>
                                                <th>Estado de Reparación</th>
                                                <th>Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($reparaciones as $reparacion): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($reparacion['estado_reparacion']) ?></td>
                                                    <td><?= htmlspecialchars($reparacion['cantidad']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Gráfico de Reparaciones por Estado -->
                                <canvas id="graficoReparaciones" width="400" height="200"></canvas>
                                <script>
                                    var ctx = document.getElementById('graficoReparaciones').getContext('2d');
                                    var graficoReparaciones = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: <?php echo json_encode(array_column($reparaciones, 'estado_reparacion')); ?>,
                                            datasets: [{
                                                label: 'Cantidad de Reparaciones',
                                                data: <?php echo json_encode(array_column($reparaciones, 'cantidad')); ?>,
                                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                borderColor: 'rgba(75, 192, 192, 1)',
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>

                    <!-- Entregas por Método -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Entregas por Método</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle text-center">
                                        <thead class="table-violet text-white">
                                            <tr>
                                                <th>Método de Entrega</th>
                                                <th>Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($entregas as $entrega): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($entrega['metodo_entrega']) ?></td>
                                                    <td><?= htmlspecialchars($entrega['cantidad']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Gráfico de Entregas por Método -->
                                <canvas id="graficoEntregas" width="400" height="200"></canvas>
                                <script>
                                    var ctx = document.getElementById('graficoEntregas').getContext('2d');
                                    var graficoEntregas = new Chart(ctx, {
                                        type: 'pie',
                                        data: {
                                            labels: <?php echo json_encode(array_column($entregas, 'metodo_entrega')); ?>,
                                            datasets: [{
                                                label: 'Método de Entrega',
                                                data: <?php echo json_encode(array_column($entregas, 'cantidad')); ?>,
                                                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)'],
                                                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            responsive: true
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>

                    <!-- Equipos por Tipo -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Equipos por Tipo</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle text-center">
                                        <thead class="table-violet text-white">
                                            <tr>
                                                <th>Tipo de Equipo</th>
                                                <th>Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($equipos as $equipo): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($equipo['tipo']) ?></td>
                                                    <td><?= htmlspecialchars($equipo['cantidad']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Gráfico de Equipos por Tipo -->
                                <canvas id="graficoEquipos" width="400" height="200"></canvas>
                                <script>
                                    var ctx = document.getElementById('graficoEquipos').getContext('2d');
                                    var graficoEquipos = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: <?php echo json_encode(array_column($equipos, 'tipo')); ?>,
                                            datasets: [{
                                                label: 'Cantidad de Equipos',
                                                data: <?php echo json_encode(array_column($equipos, 'cantidad')); ?>,
                                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                                borderColor: 'rgba(153, 102, 255, 1)',
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>

                </div>
            </main>


</body>

</html>

<?php
// Cerrar la conexión
$conn->close();
?>



</div>

</div>

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>