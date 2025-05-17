<?php session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_nombre'])) {
    // Si no está logueado, redirigir a la página de login
    header("Location: ./index.php");
    exit;
}?>

<?php
include 'includes/config.php'; // tu archivo de conexión

// Total de equipos
$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM equipos");
$totalEquipos = $totalQuery->fetch_assoc()['total'];

// Equipos por tipo
$pcQuery = $conn->query("SELECT COUNT(*) AS total FROM equipos WHERE tipo = 'pc'");
$totalPC = $pcQuery->fetch_assoc()['total'];

$laptopQuery = $conn->query("SELECT COUNT(*) AS total FROM equipos WHERE tipo = 'laptop'");
$totalLaptops = $laptopQuery->fetch_assoc()['total'];

$consolaQuery = $conn->query("SELECT COUNT(*) AS total FROM equipos WHERE tipo = 'consola'");
$totalConsolas = $consolaQuery->fetch_assoc()['total'];

$telefonoQuery = $conn->query("SELECT COUNT(*) AS total FROM equipos WHERE tipo = 'telefono'");
$totalTelefonos = $telefonoQuery->fetch_assoc()['total'];
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
                    <a href="./gestion_equipos.php" class="nav-link active">
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

                    <!-- Total Equipos -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="stat-title">Equipos Totales</h6>
                                    <h3 class="stat-number"><?php echo $totalEquipos; ?></h3>
                                </div>
                                <div class="stat-icon bg-violet-light text-white rounded-circle">
                                    <i class="fa-solid fa-layer-group fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PCs de Escritorio -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="stat-title">PCs de Escritorio</h6>
                                    <h3 class="stat-number"><?php echo $totalPC; ?></h3>
                                </div>
                                <div class="stat-icon bg-primary text-white rounded-circle">
                                    <i class="fa-solid fa-desktop fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Laptops / Netbooks -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="stat-title">Laptops / Netbooks</h6>
                                    <h3 class="stat-number"><?php echo $totalLaptops; ?></h3>
                                </div>
                                <div class="stat-icon bg-success text-white rounded-circle">
                                    <i class="fa-solid fa-laptop fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Consolas -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="stat-title">Consolas</h6>
                                    <h3 class="stat-number"><?php echo $totalConsolas; ?></h3>
                                </div>
                                <div class="stat-icon bg-warning text-white rounded-circle">
                                    <i class="fa-solid fa-gamepad fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Teléfonos -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="stat-title">Teléfonos</h6>
                                    <h3 class="stat-number"><?php echo $totalTelefonos; ?></h3>
                                </div>
                                <div class="stat-icon bg-danger text-white rounded-circle">
                                    <i class="fa-solid fa-mobile-screen fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Tabla de Equipos -->
                    <div class="col-12">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="fw-semibold mb-0">Listado de Equipos</h6>
                                    <button class="btn btn-violet btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalEquipo">
                                        <i class="fa-solid fa-plus me-1"></i> Nuevo Equipo
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle text-center">
                                        <thead class="table-violet text-white">
                                            <tr>
                                                <th>Cliente</th>
                                                <th>Tipo</th>
                                                <th>Marca</th>
                                                <th>Modelo</th>
                                                <th>N° Serie</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            require './includes/config.php';

                                            $sql = "SELECT 
                                            e.id,
                                            c.nombre AS nombre_cliente,
                                            e.tipo,
                                            e.marca,
                                            e.modelo,
                                            e.numero_serie,
                                            e.fecha_ingreso,
                                            e.observaciones
                                        FROM equipos e
                                        INNER JOIN clientes c ON e.cliente_id = c.id";


                                            $result = $conn->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row['nombre_cliente']) . "</td>";
                                                    echo "<td>" . ucfirst($row['tipo']) . "</td>";
                                                    echo "<td>" . $row['marca'] . "</td>";
                                                    echo "<td>" . $row['modelo'] . "</td>";
                                                    echo "<td>" . $row['numero_serie'] . "</td>";
                                                    echo "<td>
<button 
    class='btn btn-sm btn-outline-info me-1 btn-ver-equipo' 
    data-bs-toggle='modal' 
    data-bs-target='#modalVerEquipo'
    data-id='" . $row['id'] . "'
    data-cliente='" . htmlspecialchars($row['nombre_cliente']) . "'
    data-tipo='" . htmlspecialchars($row['tipo']) . "'
    data-marca='" . htmlspecialchars($row['marca']) . "'
    data-modelo='" . htmlspecialchars($row['modelo']) . "'
    data-serie='" . htmlspecialchars($row['numero_serie']) . "'
    data-fecha='" . $row['fecha_ingreso'] . "'
    data-obs='" . htmlspecialchars($row['observaciones']) . "'
>
    <i class='fa-solid fa-eye'></i>
</button>
<button 
    class='btn btn-sm btn-outline-danger btn-eliminar-equipo'
    data-id='" . $row['id'] . "'
>
    <i class='fa-solid fa-trash'></i>
</button>

</td>";


                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='6'>No se encontraron equipos</td></tr>";
                                            }

                                            $conn->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                    </div>

                </div>
            </main>

            <div class="modal fade" id="modalEquipo" tabindex="-1" aria-labelledby="modalEquipoLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form class="modal-content" id="formEquipo" action="./includes/equipos.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEquipoLabel">Nuevo Equipo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">

                            <!-- 🟦 Comunes a todos -->
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label">Cliente</label>
                                <select class="form-select" id="cliente_id" name="cliente_id" required>
                                    <option value="">Seleccionar...</option>
                                    <?php
                                    // Conexión a la base de datos
                                    require './includes/config.php'; // conexión a la base de datos
                                    
                                    // Consulta SQL para obtener los clientes
                                    $query = "SELECT id, nombre FROM clientes";  // Cambié 'cliente_id' por 'id' y 'nombre_cliente' por 'nombre'
                                    $result = $conn->query($query);

                                    // Verificar si hay resultados y generar las opciones
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";  // Cambié 'cliente_id' por 'id'
                                        }
                                    } else {
                                        echo "<option value=''>No hay clientes disponibles</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de equipo</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="pc">PC de escritorio</option>
                                    <option value="laptop">Laptop / Notebook</option>
                                    <option value="telefono">Teléfono</option>
                                    <option value="consola">Consola</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="marca" class="form-label">Marca</label>
                                <input type="text" class="form-control" id="marca" name="marca" required>
                            </div>
                            <div class="mb-3">
                                <label for="modelo" class="form-label">Modelo</label>
                                <input type="text" class="form-control" id="modelo" name="modelo" required>
                            </div>
                            <div class="mb-3">
                                <label for="numero_serie" class="form-label">Número de serie</label>
                                <input type="text" class="form-control" id="serie" name="numero_serie" required>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de ingreso</label>
                                <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones"
                                    rows="2"></textarea>
                            </div>

                            <!-- 🖥️ PC -->
                            <div id="fields-pc" class="specific-fields d-none">
                                <h6 class="mt-3">Especificaciones - PC</h6>
                                <input class="form-control mb-2" placeholder="Procesador" id="pc_procesador"
                                    name="pc_procesador">
                                <input class="form-control mb-2" placeholder="Memoria RAM" id="pc_ram" name="pc_ram">
                                <input class="form-control mb-2" placeholder="Almacenamiento" id="pc_almacenamiento"
                                    name="pc_almacenamiento">
                                <input class="form-control mb-2" placeholder="Placa gráfica" id="pc_gpu" name="pc_gpu">
                                <input class="form-control mb-2" placeholder="Placa madre" id="pc_mother"
                                    name="pc_mother">
                                <input class="form-control mb-2" placeholder="Sistema operativo" id="pc_os"
                                    name="pc_os">
                                <input class="form-control mb-2" placeholder="Puertos disponibles" id="pc_puertos"
                                    name="pc_puertos">
                            </div>

                            <!-- 💻 Laptop -->
                            <div id="fields-laptop" class="specific-fields d-none">
                                <h6 class="mt-3">Especificaciones - Laptop</h6>
                                <input class="form-control mb-2" placeholder="Procesador" id="lap_procesador"
                                    name="lap_procesador">
                                <input class="form-control mb-2" placeholder="Memoria RAM" id="lap_ram" name="lap_ram">
                                <input class="form-control mb-2" placeholder="Almacenamiento" id="lap_almacenamiento"
                                    name="lap_almacenamiento">
                                <input class="form-control mb-2" placeholder="Pantalla" id="lap_pantalla"
                                    name="lap_pantalla">
                                <input class="form-control mb-2" placeholder="Placa gráfica" id="lap_gpu"
                                    name="lap_gpu">
                                <input class="form-control mb-2" placeholder="Placa madre" id="lap_mother"
                                    name="lap_mother">
                                <input class="form-control mb-2" placeholder="Batería" id="lap_bateria"
                                    name="lap_bateria">
                                <input class="form-control mb-2" placeholder="Sistema operativo" id="lap_os"
                                    name="lap_os">
                            </div>

                            <!-- 📱 Teléfono -->
                            <div id="fields-telefono" class="specific-fields d-none">
                                <h6 class="mt-3">Especificaciones - Teléfono</h6>
                                <input class="form-control mb-2" placeholder="Sistema operativo" id="tel_os"
                                    name="tel_os">
                                <input class="form-control mb-2" placeholder="Pantalla" id="tel_pantalla"
                                    name="tel_pantalla">
                                <input class="form-control mb-2" placeholder="Cámara principal" id="tel_camara"
                                    name="tel_camara">
                                <input class="form-control mb-2" placeholder="Procesador" id="tel_procesador"
                                    name="tel_procesador">
                                <input class="form-control mb-2" placeholder="Memoria RAM" id="tel_ram" name="tel_ram">
                                <input class="form-control mb-2" placeholder="Almacenamiento interno"
                                    id="tel_almacenamiento" name="tel_almacenamiento">
                                <input class="form-control mb-2" placeholder="Batería" id="tel_bateria"
                                    name="tel_bateria">
                                <input class="form-control mb-2" placeholder="Red (4G/5G)" id="tel_red" name="tel_red">
                            </div>

                            <!-- 🎮 Consola -->
                            <div id="fields-consola" class="specific-fields d-none">
                                <h6 class="mt-3">Especificaciones - Consola</h6>
                                <input class="form-control mb-2" placeholder="Sistema operativo" id="con_os"
                                    name="con_os">
                                <input class="form-control mb-2" placeholder="Almacenamiento" id="con_almacenamiento"
                                    name="con_almacenamiento">
                                <input class="form-control mb-2" placeholder="Puertos" id="con_puertos"
                                    name="con_puertos">
                                <input class="form-control mb-2" placeholder="Conectividad (Wi-Fi/Bluetooth)"
                                    id="con_conectividad" name="con_conectividad">
                                <input class="form-control mb-2" placeholder="Mandos incluidos" id="con_mandos"
                                    name="con_mandos">
                                <input class="form-control mb-2" placeholder="Red (Ethernet/Wi-Fi)" id="con_red"
                                    name="con_red">
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


            <div class="modal fade" id="modalVerEquipo" tabindex="-1" aria-labelledby="modalVerEquipoLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-violet text-white">
                            <h5 class="modal-title" id="modalVerEquipoLabel">Detalles del Equipo</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">

                            <!-- Datos comunes -->
                            <div class="mb-3">
                                <p><strong>Cliente:</strong> <span data-field="cliente"></span></p>
                                <p><strong>Tipo de equipo:</strong> <span data-field="tipo"></span></p>
                                <p><strong>Marca:</strong> <span data-field="marca"></span></p>
                                <p><strong>Modelo:</strong> <span data-field="modelo"></span></p>
                                <p><strong>N° Serie:</strong> <span data-field="serie"></span></p>
                                <p><strong>Fecha de ingreso:</strong> <span data-field="fecha"></span></p>
                                <p><strong>Observaciones:</strong> <span data-field="obs"></span></p>
                            </div>



                            <!-- Datos específicos (ejemplo para Laptop) -->
                            <!-- Contenedor para cargar dinámicamente -->
                            <hr>
                            <h6 class="mb-2">Especificaciones Técnicas</h6>
                            <div id="detalles-especificos"></div>


                            <!-- Si es consola, teléfono, etc., se puede modificar dinámicamente vía JS o backend -->

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
    <script>
        const tipoSelect = document.getElementById('tipo');
        const bloques = ['pc', 'laptop', 'telefono', 'consola'];

        tipoSelect.addEventListener('change', function () {
            bloques.forEach(tipo => {
                const div = document.getElementById(`fields-${tipo}`);
                if (tipo === this.value) {
                    div.classList.remove('d-none');
                } else {
                    div.classList.add('d-none');
                }
            });
        });

        document.getElementById('modalEquipo').addEventListener('show.bs.modal', () => {
            document.getElementById('formEquipo').reset();
            tipoSelect.dispatchEvent(new Event('change'));
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modal = document.getElementById('modalVerEquipo');
            const botones = document.querySelectorAll('.btn-ver-equipo');

            botones.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const cliente = this.getAttribute('data-cliente');
                    const tipo = this.getAttribute('data-tipo');
                    const marca = this.getAttribute('data-marca');
                    const modelo = this.getAttribute('data-modelo');
                    const serie = this.getAttribute('data-serie');
                    const fecha = this.getAttribute('data-fecha');
                    const obs = this.getAttribute('data-obs');

                    modal.querySelector('[data-field="fecha"]').textContent = fecha;
                    modal.querySelector('[data-field="obs"]').textContent = obs;


                    modal.querySelector('[data-field="cliente"]').textContent = cliente;
                    modal.querySelector('[data-field="tipo"]').textContent = tipo;
                    modal.querySelector('[data-field="marca"]').textContent = marca;
                    modal.querySelector('[data-field="modelo"]').textContent = modelo;
                    modal.querySelector('[data-field="serie"]').textContent = serie;

                    // Llamar vía AJAX al archivo PHP que trae detalles técnicos
                    fetch(`includes/detalles_equipo.php?id=${id}&tipo=${tipo}`)
                        .then(res => res.json())
                        .then(data => {
                            const container = modal.querySelector('#detalles-especificos');
                            container.innerHTML = '';

                            Object.entries(data).forEach(([key, value]) => {
                                container.innerHTML += `<p><strong>${key}:</strong> ${value}</p>`;
                            });
                        });
                });
            });
        });
    </script>
    <?php if (isset($_GET['status']) || isset($_GET['msg'])): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const urlParams = new URLSearchParams(window.location.search);
                const status = urlParams.get('status');
                const mensaje = urlParams.get('mensaje');
                const msg = urlParams.get('msg');

                if (status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: mensaje,
                        timer: 3000,
                        willClose: () => {
                            history.replaceState(null, '', window.location.pathname);
                        }
                    });
                }

                if (status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: mensaje,
                        timer: 3000,
                        willClose: () => {
                            history.replaceState(null, '', window.location.pathname);
                        }
                    });
                }

                if (msg === 'eliminado') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Equipo eliminado',
                        text: 'El equipo se eliminó correctamente.',
                        confirmButtonColor: '#7367f0',
                        willClose: () => {
                            history.replaceState(null, '', window.location.pathname);
                        }
                    });
                }

                if (msg === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo eliminar el equipo.',
                        confirmButtonColor: '#d33',
                        willClose: () => {
                            history.replaceState(null, '', window.location.pathname);
                        }
                    });
                }
            });
        </script>
    <?php endif; ?>


</body>

</html>