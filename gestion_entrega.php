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
                    <a href="./gestion_entrega.php" class="nav-link active">
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
            require_once("includes/config.php");
            ?>
            <!-- Main Content -->
            <main class="container-fluid py-4">
                <div class="row g-4">
                    <!-- Sección de Entregas -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Módulo de Entrega</h5>
                                <button class="btn btn-outline-secondary ms-2" data-bs-toggle="modal"
                                    data-bs-target="#modalRegistrarEntrega">
                                    Registrar Entrega
                                </button>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped align-middle text-center" id="tabla-entregas">
                                    <thead class="table-violet text-white">
                                        <tr>
                                            <th>ID Reparación</th>
                                            <th>Cliente</th>
                                            <th>Fecha de Entrega</th>
                                            <th>Estado de Pago</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // suponiendo que ya tenés $conn
                                        $sql = "
                SELECT
                  e.id,
                  e.orden_reparacion_id,
                  c.nombre AS cliente,
                  e.fecha_entrega,
                  e.estado_pago_entrega
                FROM entregas e
                JOIN ordenes_reparacion o ON e.orden_reparacion_id = o.id
                JOIN clientes c ON o.cliente_id = c.id
                ORDER BY e.creado_en DESC
              ";
                                        $res = $conn->query($sql);
                                        if ($res && $res->num_rows) {
                                            while ($ent = $res->fetch_assoc()):
                                                ?>
                                                <tr>
                                                    <td><?= $ent['orden_reparacion_id'] ?></td>
                                                    <td><?= htmlspecialchars($ent['cliente']) ?></td>
                                                    <td><?= $ent['fecha_entrega'] ?></td>
                                                    <td>
                                                        <span class="badge bg-<?=
                                                            $ent['estado_pago_entrega'] === 'pagada' ? 'success' :
                                                            ($ent['estado_pago_entrega'] === 'parcial' ? 'warning' : 'secondary')
                                                            ?>">
                                                            <?= ucfirst($ent['estado_pago_entrega']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                    <td>
                                                        <a href="includes/generar_boleta_entrega.php?entrega_id=<?= $ent['id'] ?>"
                                                            class="btn btn-info btn-sm" target="_blank">
                                                            Descargar
                                                        </a>
                                                    </td>

                                                    </td>
                                                </tr>
                                                <?php
                                            endwhile;
                                        } else {
                                            echo "<tr><td colspan='5'>No hay entregas registradas.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>


            <!-- Modal Registrar Entrega -->
            <?php

            // Consulta para obtener los clientes con reparaciones finalizadas
            $queryClientes = "
    SELECT DISTINCT c.id, c.nombre
    FROM clientes c
    JOIN ordenes_reparacion o ON c.id = o.cliente_id
    WHERE o.estado_reparacion = 'finalizado'";

            // Ejecutamos la consulta
            $resultClientes = $conn->query($queryClientes);
            $clientes = $resultClientes->fetch_all(MYSQLI_ASSOC);
            ?>

            <!-- Modal para Registrar Entrega -->
            <!-- Modal para Registrar Entrega -->
            <div class="modal fade" id="modalRegistrarEntrega" tabindex="-1"
                aria-labelledby="modalRegistrarEntregaLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form method="POST" action="includes/guardar_entrega.php" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalRegistrarEntregaLabel">Registrar Entrega</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Cliente -->
                            <div class="mb-3">
                                <label for="cliente" class="form-label">Cliente</label>
                                <select class="form-select" id="cliente" name="cliente_id" required>
                                    <option value="">Seleccione un Cliente</option>
                                    <?php foreach ($clientes as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Reparación finalizada -->
                            <div class="mb-3" id="reparacion_section" style="display:none;">
                                <label for="reparacion" class="form-label">Reparación Finalizada</label>
                                <select class="form-select" id="reparacion" name="reparacion_id" required>
                                    <option value="">Seleccione una Reparación</option>
                                </select>
                            </div>

                            <!-- Fecha de Entrega -->
                            <div class="mb-3">
                                <label for="fecha_entrega" class="form-label">Fecha de Entrega Real</label>
                                <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega"
                                    required>
                            </div>

                            <!-- Método de Entrega -->
                            <div class="mb-3">
                                <label for="metodo_entrega" class="form-label">Método de Entrega</label>
                                <select class="form-select" id="metodo_entrega" name="metodo_entrega" required>
                                    <option value="recogido_cliente">Recogido por el cliente</option>
                                    <option value="entregado_empresa">Entregado por la empresa</option>
                                    <option value="envio_domicilio">Envío a domicilio</option>
                                </select>
                            </div>

                            <!-- Ubicación de Entrega -->
                            <div class="mb-3" id="ubicacion_entrega_section">
                                <label for="ubicacion_entrega" class="form-label">Ubicación de Entrega</label>
                                <input type="text" class="form-control" id="ubicacion_entrega" name="ubicacion_entrega">
                            </div>

                            <!-- Observaciones Generales -->
                            <div class="mb-3">
                                <label for="observaciones_generales" class="form-label">Observaciones Generales</label>
                                <textarea class="form-control" id="observaciones_generales"
                                    name="observaciones_generales"></textarea>
                            </div>

                            <!-- Comentarios Técnicos -->
                            <div class="mb-3">
                                <label for="comentarios_tecnicos" class="form-label">Comentarios Técnicos</label>
                                <textarea class="form-control" id="comentarios_tecnicos"
                                    name="comentarios_tecnicos"></textarea>
                            </div>

                            <!-- Estado de Pago -->
                            <div class="mb-3">
                                <label for="estado_pago_entrega" class="form-label">Estado de Pago</label>
                                <select class="form-select" id="estado_pago_entrega" name="estado_pago_entrega"
                                    required>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="parcial">Parcial</option>
                                    <option value="pagada">Pagada</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-violet">Registrar Entrega</button>
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Script para cargar reparaciones al seleccionar un cliente -->
            <script>
                document.getElementById('cliente').addEventListener('change', function () {
                    const clienteId = this.value;
                    const reparacionSelect = document.getElementById('reparacion');
                    const reparacionSection = document.getElementById('reparacion_section');

                    if (clienteId) {
                        // Mostrar el select de reparaciones
                        reparacionSection.style.display = 'block';

                        // Hacer la solicitud para cargar las reparaciones finalizadas
                        fetch(`includes/cargar_reparaciones.php?cliente_id=${clienteId}`)
                            .then(res => res.json())
                            .then(data => {
                                // Limpiar las opciones anteriores
                                reparacionSelect.innerHTML = '<option value="">Seleccione una Reparación</option>';

                                // Cargar nuevas reparaciones
                                if (data.reparaciones.length > 0) {
                                    data.reparaciones.forEach(reparacion => {
                                        const option = document.createElement('option');
                                        option.value = reparacion.id;
                                        option.textContent = `Reparación #${reparacion.id} - ${reparacion.descripcion}`;
                                        reparacionSelect.appendChild(option);
                                    });
                                } else {
                                    // Si no hay reparaciones, agregar un mensaje
                                    const noReparacionOption = document.createElement('option');
                                    noReparacionOption.textContent = "No hay reparaciones finalizadas para este cliente";
                                    reparacionSelect.appendChild(noReparacionOption);
                                }
                            })
                            .catch(err => console.error('Error al cargar reparaciones:', err));
                    } else {
                        // Si no hay cliente seleccionado, ocultar el select de reparaciones
                        reparacionSection.style.display = 'none';
                    }
                });
            </script>


        </div>

    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>