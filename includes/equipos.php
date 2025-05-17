<?php
require './config.php'; // conexión mysqli
// Validar que los campos del sistema operativo no estén vacíos
// Validar sistema operativo dependiendo del tipo
if (($tipo == 'pc' && empty($_POST['pc_os'])) ||
    ($tipo == 'laptop' && empty($_POST['lap_os'])) ||
    ($tipo == 'telefono' && empty($_POST['tel_os'])) ||
    ($tipo == 'consola' && empty($_POST['con_os']))) {
    header("Location: ../gestion_equipos.php?status=error&mensaje=El campo de sistema operativo es obligatorio para el tipo seleccionado");
    exit;
}


// Datos comunes
$cliente_id = $_POST['cliente_id'];
$tipo = $_POST['tipo'];
$marca = $_POST['marca'];
$modelo = $_POST['modelo'];
$numero_serie = $_POST['numero_serie'];
$fecha_ingreso = $_POST['fecha_ingreso'];
$observaciones = $_POST['observaciones'];

// Insertar en tabla principal "equipos"
$stmt = $conn->prepare("INSERT INTO equipos (cliente_id, tipo, marca, modelo, numero_serie, fecha_ingreso, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssss", $cliente_id, $tipo, $marca, $modelo, $numero_serie, $fecha_ingreso, $observaciones);

if (!$stmt->execute()) {
    header("Location: ../gestion_equipos.php?status=error&mensaje=Error al insertar el equipo principal");
    exit;
}

$equipo_id = $conn->insert_id; // ID del nuevo equipo

// Insertar en tabla específica según tipo
switch ($tipo) {
    case 'pc':
        $stmt = $conn->prepare("INSERT INTO equipos_pc (equipo_id, procesador, ram, almacenamiento, gpu, mother, os, puertos) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $equipo_id, $_POST['pc_procesador'], $_POST['pc_ram'], $_POST['pc_almacenamiento'], $_POST['pc_gpu'], $_POST['pc_mother'], $_POST['pc_os'], $_POST['pc_puertos']);
        break;
    case 'laptop':
        $stmt = $conn->prepare("INSERT INTO equipos_laptop (equipo_id, procesador, ram, almacenamiento, pantalla, gpu, mother, bateria, os) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $equipo_id, $_POST['lap_procesador'], $_POST['lap_ram'], $_POST['lap_almacenamiento'], $_POST['lap_pantalla'], $_POST['lap_gpu'], $_POST['lap_mother'], $_POST['lap_bateria'], $_POST['lap_os']);
        break;
    case 'telefono':
        $stmt = $conn->prepare("INSERT INTO equipos_telefono (equipo_id, os, pantalla, camara, procesador, ram, almacenamiento, bateria, red) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssss", $equipo_id, $_POST['tel_os'], $_POST['tel_pantalla'], $_POST['tel_camara'], $_POST['tel_procesador'], $_POST['tel_ram'], $_POST['tel_almacenamiento'], $_POST['tel_bateria'], $_POST['tel_red']);
        break;
    case 'consola':
        $stmt = $conn->prepare("INSERT INTO equipos_consola (equipo_id, os, almacenamiento, puertos, conectividad, mandos, red) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $equipo_id, $_POST['con_os'], $_POST['con_almacenamiento'], $_POST['con_puertos'], $_POST['con_conectividad'], $_POST['con_mandos'], $_POST['con_red']);
        break;
    default:
        header("Location: ../gestion_equipos.php?status=error&mensaje=Tipo de equipo no reconocido");
        exit;
}

if (!$stmt->execute()) {
    header("Location: ../gestion_equipos.php?status=error&mensaje=Error al insertar las especificaciones del equipo");
    exit;
}

header("Location: ../gestion_equipos.php?status=success&mensaje=Equipo guardado");
exit;

?>
