<?php
require_once 'config.php';

if (!isset($_GET['id']) || !($id = intval($_GET['id']))) {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

// 1) Datos generales
$stmt = $conn->prepare("
  SELECT descripcion, estado_reparacion, fecha_ingreso, fecha_finalizacion,
         total_servicios, total_repuestos, precio_total
    FROM ordenes_reparacion
   WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$rep = $stmt->get_result()->fetch_assoc();
if (!$rep) {
    http_response_code(404);
    echo json_encode(['error' => 'Reparación no encontrada']);
    exit;
}

// 2) Servicios
$stmtS = $conn->prepare("
  SELECT servicio_nombre AS nombre, precio 
    FROM ordenes_servicios 
   WHERE orden_reparacion_id = ?
");
$stmtS->bind_param("i", $id);
$stmtS->execute();
$servicios = $stmtS->get_result()->fetch_all(MYSQLI_ASSOC);

// 3) Repuestos
$stmtR = $conn->prepare("
  SELECT repuesto_nombre AS nombre, cantidad, costo 
    FROM ordenes_repuestos 
   WHERE orden_reparacion_id = ?
");
$stmtR->bind_param("i", $id);
$stmtR->execute();
$repuestos = $stmtR->get_result()->fetch_all(MYSQLI_ASSOC);

// Devolver todo como JSON
header('Content-Type: application/json');
echo json_encode([
  'reparacion' => $rep,
  'servicios'  => $servicios,
  'repuestos'  => $repuestos
]);
