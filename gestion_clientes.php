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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    <a href="./gestion_clientes.php" class="nav-link active">
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
            <?php
            include("./includes/config.php");

            // Contadores rápidos
            $total_clientes = 0;
            $total_empresas = 0;
            $total_clientes_verdaderos = 0;

            $queryContadores = "SELECT 
    COUNT(*) AS total,
    SUM(tipo_cliente = 'Empresa') AS empresas,
    SUM(tipo_cliente = 'Cliente') AS verdaderos
    FROM clientes";
            $resContadores = $conn->query($queryContadores);

            if ($resContadores && $resContadores->num_rows > 0) {
                $row = $resContadores->fetch_assoc();
                $total_clientes = $row['total'];
                $total_empresas = $row['empresas'];
                $total_clientes_verdaderos = $row['verdaderos'];
            }
            ?>

            <main class="container-fluid py-4">
                <div class="row g-4">

                    <!-- Card Total Clientes -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="stat-title">Clientes</h6>
                                    <h3 class="stat-number"><?= $total_clientes ?></h3>
                                </div>
                                <div class="stat-icon bg-success-light text-white rounded-circle">
                                    <i class="fa-solid fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Clientes Verdaderos -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="stat-title">Clientes Verdaderos</h6>
                                    <h3 class="stat-number"><?= $total_clientes_verdaderos ?></h3>
                                </div>
                                <div class="stat-icon bg-violet-light text-white rounded-circle">
                                    <i class="fa-solid fa-user-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Total Empresas -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="stat-title">Empresas</h6>
                                    <h3 class="stat-number"><?= $total_empresas ?></h3>
                                </div>
                                <div class="stat-icon bg-info-light text-white rounded-circle">
                                    <i class="fa-solid fa-building fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Clientes -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-semibold mb-0">Listado de Clientes</h6>
                                    <button class="btn btn-violet btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalCliente">
                                        <i class="fa-solid fa-user-plus me-1"></i> Agregar Cliente
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle text-center">
                                        <thead class="table-violet text-white">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Email</th>
                                                <th>Teléfono</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM clientes ORDER BY id DESC";
                                            $res = $conn->query($query);
                                            if ($res && $res->num_rows > 0) {
                                                while ($cliente = $res->fetch_assoc()) {
                                                    ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                                                        <td><?= htmlspecialchars($cliente['email']) ?></td>
                                                        <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-info me-1"
                                                                data-bs-toggle="modal" data-bs-target="#modalVerCliente"
                                                                onclick='verCliente(<?= $cliente["id"] ?>)'>
                                                                <i class="fa-solid fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-primary me-1"
                                                                data-bs-toggle="modal" data-bs-target="#modalClienteEditar"
                                                                data-id="<?= $cliente['id'] ?>">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>

                                                            <form method="POST" action="./includes/clientes.php"
                                                                class="d-inline" id="formEliminarCliente<?= $cliente['id'] ?>">
                                                                <input type="hidden" name="id" value="<?= $cliente['id'] ?>">
                                                                <button type="submit" name="eliminar_cliente"
                                                                    class="btn btn-sm btn-outline-danger">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                            </form>

                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="4">No hay clientes registrados.</td></tr>';
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
            <div class="modal fade" id="modalClienteEditar" tabindex="-1" aria-labelledby="modalClienteLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form class="modal-content" method="POST" action="./includes/clientes.php">
                        <div class="modal-header bg-violet text-white">
                            <h5 class="modal-title" id="modalClienteLabel">Editar Cliente</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Campo oculto con el ID del cliente -->
                            <input type="hidden" name="id_cliente" id="id_cliente"
                                value="<?= isset($cliente) ? $cliente['id'] : '' ?>">

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    value="<?= isset($cliente) ? $cliente['nombre'] : '' ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono"
                                    value="<?= isset($cliente) ? $cliente['telefono'] : '' ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?= isset($cliente) ? $cliente['email'] : '' ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion"
                                    value="<?= isset($cliente) ? $cliente['direccion'] : '' ?>">
                            </div>

                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI / CUIT</label>
                                <input type="text" class="form-control" id="dni" name="dni"
                                    value="<?= isset($cliente) ? $cliente['dni'] : '' ?>">
                            </div>

                            <div class="mb-3">
                                <label for="tipo_cliente" class="form-label">Tipo de Cliente</label>
                                <select class="form-select" id="tipo_cliente" name="tipo_cliente" required>
                                    <option value="Cliente" <?= isset($cliente) && $cliente['tipo_cliente'] == 'Cliente' ? 'selected' : '' ?>>Cliente</option>
                                    <option value="Empresa" <?= isset($cliente) && $cliente['tipo_cliente'] == 'Empresa' ? 'selected' : '' ?>>Empresa</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="notas" class="form-label">Notas internas</label>
                                <textarea class="form-control" id="notas" name="notas"
                                    rows="3"><?= isset($cliente) ? $cliente['notas'] : '' ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-violet" name="guardar_cliente">Guardar</button>
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalClienteLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form class="modal-content" method="POST" action="./includes/clientes.php">
                        <div class="modal-header bg-violet text-white">
                            <h5 class="modal-title" id="modalClienteLabel">Nuevo Cliente</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_cliente" id="id_cliente">

                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>

                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion">
                            </div>

                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI / CUIT</label>
                                <input type="text" class="form-control" id="dni" name="dni">
                            </div>

                            <div class="mb-3">
                                <label for="tipo_cliente" class="form-label">Tipo de Cliente</label>
                                <select class="form-select" id="tipo_cliente" name="tipo_cliente" required>
                                    <option value="Cliente">Cliente</option>
                                    <option value="Empresa">Empresa</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="notas" class="form-label">Notas internas</label>
                                <textarea class="form-control" id="notas" name="notas" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-violet" name="guardar_cliente">Guardar</button>
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="modalVerCliente" tabindex="-1" aria-labelledby="modalVerClienteLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-violet text-white">
                            <h5 class="modal-title" id="modalVerClienteLabel">Detalles del Cliente</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Nombre:</strong> <span id="detalle_nombre"></span></p>
                            <p><strong>Teléfono:</strong> <span id="detalle_telefono"></span></p>
                            <p><strong>Email:</strong> <span id="detalle_email"></span></p>
                            <p><strong>Dirección:</strong> <span id="detalle_direccion"></span></p>
                            <p><strong>DNI / CUIT:</strong> <span id="detalle_dni"></span></p>
                            <p><strong>Tipo de Cliente:</strong> <span id="detalle_tipo_cliente"></span></p>
                            <p><strong>Notas Internas:</strong> <span id="detalle_notas"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php
    // Verifica si hay un parámetro 'status' en la URL
    if (isset($_GET['status'])) {
        // Guardar el valor del parámetro 'status' para usarlo en la alerta
        $status = $_GET['status'];

        if ($status === 'deleted') {
            echo "<script>
                Swal.fire({
                    title: '¡Eliminado!',
                    text: 'El cliente ha sido eliminado correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    // Redirigir a la misma página sin el parámetro 'status' en la URL
                    window.history.replaceState(null, null, 'gestion_clientes.php');
                });
              </script>";
        } elseif ($status === 'success') {
            echo "<script>
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Cliente guardado correctamente.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    // Redirigir a la misma página sin el parámetro 'status' en la URL
                    window.history.replaceState(null, null, 'gestion_clientes.php');
                });
              </script>";
        } elseif ($status === 'error_execute') {
            echo "<script>
                Swal.fire({
                    title: '¡Error!',
                    text: 'Hubo un error al ejecutar la operación.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    // Redirigir a la misma página sin el parámetro 'status' en la URL
                    window.history.replaceState(null, null, 'gestion_clientes.php');
                });
              </script>";
        } elseif ($status === 'error_prepare') {
            echo "<script>
                Swal.fire({
                    title: '¡Error!',
                    text: 'Hubo un error al preparar la consulta.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    // Redirigir a la misma página sin el parámetro 'status' en la URL
                    window.history.replaceState(null, null, 'gestion_clientes.php');
                });
              </script>";
        }
    }
    ?>



    <script>
        function verCliente(id) {
            fetch(`./includes/get_cliente.php?id=${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire("Error", data.error, "error");
                        return;
                    }

                    document.getElementById('detalle_nombre').textContent = data.nombre;
                    document.getElementById('detalle_telefono').textContent = data.telefono;
                    document.getElementById('detalle_email').textContent = data.email;
                    document.getElementById('detalle_direccion').textContent = data.direccion || '—';
                    document.getElementById('detalle_dni').textContent = data.dni || '—';
                    document.getElementById('detalle_tipo_cliente').textContent = data.tipo_cliente;
                    document.getElementById('detalle_notas').textContent = data.notas || '—';

                    // Modal se abre automáticamente con data-bs-toggle
                })
                .catch(err => {
                    Swal.fire("Error", "No se pudo obtener los datos del cliente.", "error");
                    console.error(err);
                });
        }
    </script>

    <script>
        // Función que se ejecuta cuando el modal se va a abrir
        const modalClienteEditar = document.getElementById('modalClienteEditar');
        modalClienteEditar.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Botón que abrió el modal
            const clientId = button.getAttribute('data-id'); // Obtener el ID del cliente desde el atributo 'data-id'

            // Hacer una llamada AJAX para obtener los datos del cliente
            fetch(`./includes/get_cliente.php?id=${clientId}`)
                .then(res => res.json())
                .then(data => {
                    // Verificar si se obtuvieron datos
                    if (data.error) {
                        Swal.fire("Error", data.error, "error");
                        return;
                    }

                    // Rellenar los campos del formulario con los datos obtenidos
                    document.getElementById('id_cliente').value = data.id;
                    document.getElementById('nombre').value = data.nombre;
                    document.getElementById('telefono').value = data.telefono;
                    document.getElementById('email').value = data.email;
                    document.getElementById('direccion').value = data.direccion || '';
                    document.getElementById('dni').value = data.dni || '';
                    document.getElementById('tipo_cliente').value = data.tipo_cliente;
                    document.getElementById('notas').value = data.notas || '';
                })
                .catch(err => {
                    Swal.fire("Error", "No se pudo obtener los datos del cliente.", "error");
                    console.error(err);
                });
        });
    </script>
    <script>
        function confirmarEliminacion(clienteId) {
            // Usamos SweetAlert para mostrar un mensaje de confirmación
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma la eliminación, enviamos el formulario
                    document.getElementById('formEliminarCliente' + clienteId).submit();
                }
            });
        }
    </script>
</body>

</html>