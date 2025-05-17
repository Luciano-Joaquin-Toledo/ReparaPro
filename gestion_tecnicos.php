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

    <!-- Incluir SweetAlert CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Incluir SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>

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
                    <a href="./gestion_tecnicos.php" class="nav-link active">
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

                    <!-- Lista de Técnicos -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Lista de Técnicos</h5>
                                <button class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#modalRegistrarTecnico">Registrar Nuevo Técnico</button>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped" id="tablaTecnicos">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Especialidad</th>
                                            <th>Contacto</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include('./includes/config.php');  // Incluir la conexión a la base de datos
                                        // Consulta SQL para obtener todos los técnicos
                                        $query = "SELECT * FROM tecnicos";
                                        $result = $conn->query($query);  // Ejecutar la consulta
                                        
                                        // Verificar si hay resultados
                                        if ($result->num_rows > 0) {
                                            // Mostrar los datos de los técnicos en la tabla
                                            while ($tecnico = $result->fetch_assoc()) {
                                                echo '<tr>';
                                                echo '<td>' . htmlspecialchars($tecnico['nombre']) . '</td>';
                                                echo '<td>' . htmlspecialchars($tecnico['especialidad']) . '</td>';
                                                echo '<td>' . htmlspecialchars($tecnico['contacto']) . '</td>';
                                                echo '<td><span class="badge bg-' . ($tecnico['estado'] == 'activo' ? 'success' : 'danger') . '">' . ucfirst($tecnico['estado']) . '</span></td>';
                                                echo '<td>
                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarTecnico" onclick="editarTecnico(' . $tecnico['id'] . ')">Editar</button>
                <button class="btn btn-danger btn-sm" onclick="eliminarTecnico(' . $tecnico['id'] . ')">Eliminar</button>
              </td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No hay técnicos registrados</td></tr>";
                                        }

                                        // Cerrar la conexión
                                        $conn->close();
                                        ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Modal de Registro de Técnico -->
                <!-- Modal de Registro de Técnico -->
                <div class="modal fade" id="modalRegistrarTecnico" tabindex="-1"
                    aria-labelledby="modalRegistrarTecnicoLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalRegistrarTecnicoLabel">Registrar Nuevo Técnico</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <form action="includes/registrar_tecnico.php" method="POST" id="formRegistrarTecnico">
                                    <div class="mb-3">
                                        <label for="nombre_tecnico" class="form-label">Nombre Completo</label>
                                        <input type="text" class="form-control" id="nombre_tecnico" name="nombre"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="especialidad" class="form-label">Especialidad</label>
                                        <input type="text" class="form-control" id="especialidad" name="especialidad"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contacto_tecnico" class="form-label">Contacto</label>
                                        <input type="text" class="form-control" id="contacto_tecnico" name="contacto"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="estado_tecnico" class="form-label">Estado</label>
                                        <select class="form-select" id="estado_tecnico" name="estado" required>
                                            <option value="activo">Activo</option>
                                            <option value="inactivo">Inactivo</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contraseña_tecnico" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" id="contraseña_tecnico"
                                            name="contraseña" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Registrar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal de Edición de Técnico -->
                <div class="modal fade" id="modalEditarTecnico" tabindex="-1" aria-labelledby="modalEditarTecnicoLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditarTecnicoLabel">Editar Técnico</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <form id="formEditarTecnico">
                                    <input type="hidden" id="tecnico_id">
                                    <div class="mb-3">
                                        <label for="nombre_tecnico_edit" class="form-label">Nombre Completo</label>
                                        <input type="text" class="form-control" id="nombre_tecnico_edit" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="especialidad_edit" class="form-label">Especialidad</label>
                                        <input type="text" class="form-control" id="especialidad_edit" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contacto_tecnico_edit" class="form-label">Contacto</label>
                                        <input type="text" class="form-control" id="contacto_tecnico_edit" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="estado_tecnico_edit" class="form-label">Estado</label>
                                        <select class="form-select" id="estado_tecnico_edit" required>
                                            <option value="activo">Activo</option>
                                            <option value="inactivo">Inactivo</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="contraseña_tecnico_edit" class="form-label">Contraseña
                                            (opcional)</label>
                                        <input type="password" class="form-control" id="contraseña_tecnico_edit">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </main>

            <script>
                // Verificar si la URL contiene el parámetro 'mensaje=registro_exitoso'
                window.onload = function () {
                    const urlParams = new URLSearchParams(window.location.search);
                    const mensaje = urlParams.get('mensaje');

                    if (mensaje === 'registro_exitoso') {
                        // Mostrar la alerta de SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: '¡Técnico registrado!',
                            text: 'El nuevo técnico ha sido registrado con éxito.',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                };

            </script>








        </div>

    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.14/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.14/dist/sweetalert2.min.js"></script>
    <script>
        function eliminarTecnico(tecnicoId) {
            // Usar SweetAlert para confirmar la eliminación
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminarlo',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si se confirma la eliminación, hacer la solicitud AJAX para eliminar
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'includes/eliminar_tecnico.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            // Si la eliminación es exitosa, mostrar un mensaje y redirigir
                            Swal.fire(
                                'Eliminado!',
                                'El técnico ha sido eliminado.',
                                'success'
                            ).then(() => {
                                // Redirigir a la página de eliminación (includes/eliminar_tecnico.php)
                                window.location.href = 'includes/eliminar_tecnico.php'; // Redirige a la página de eliminación
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Hubo un problema al eliminar el técnico.',
                                'error'
                            );
                        }
                    };
                    // Enviar el ID del técnico para eliminar
                    xhr.send('id=' + tecnicoId);
                } else {
                    // Si se cancela la eliminación
                    Swal.fire(
                        'Cancelado',
                        'El técnico no ha sido eliminado',
                        'info'
                    );
                }
            });
        }
    </script>

</body>

</html>