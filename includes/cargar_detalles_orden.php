<?php
include 'config.php';

header('Content-Type: application/json');

if (!isset($_GET['orden_id'])) {
    echo json_encode(['error' => 'No se especificó una orden']);
    exit;
}

$orden_id = intval($_GET['orden_id']);

// Obtener servicios
$servicios = [];
$total_servicios = 0;

$sql_servicios = "SELECT servicio_nombre, precio 
                  FROM ordenes_servicios 
                  WHERE orden_reparacion_id = $orden_id";
$result = $conn->query($sql_servicios);
while ($row = $result->fetch_assoc()) {
    $servicios[] = $row;
    $total_servicios += floatval($row['precio']);
}

// Obtener total repuestos
$total_repuestos = 0;
$sql_repuestos = "SELECT SUM(costo * cantidad) AS total_repuestos 
                  FROM ordenes_repuestos 
                  WHERE orden_reparacion_id = $orden_id";
$result_repuestos = $conn->query($sql_repuestos);
$row_repuestos = $result_repuestos->fetch_assoc();
$total_repuestos = floatval($row_repuestos['total_repuestos'] ?? 0);

// Total final
$precio_total = $total_servicios + $total_repuestos;

echo json_encode([
    'servicios' => $servicios,
    'total_servicios' => $total_servicios,
    'total_repuestos' => $total_repuestos,
    'precio_total' => $precio_total
]);
