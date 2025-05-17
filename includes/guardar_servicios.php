<?php
require './config.php';

$data = json_decode(file_get_contents('php://input'), true);

$orden_id = $data['orden_id'];
$servicios = $data['servicios'];
$total = 0;

foreach ($servicios as $servicio) {
    $nombre = $conn->real_escape_string($servicio['nombre']);
    $precio = floatval($servicio['precio']);
    $total += $precio;

    $conn->query("INSERT INTO ordenes_servicios (orden_reparacion_id, servicio_nombre, precio) 
                  VALUES ($orden_id, '$nombre', $precio)");
}

// Actualizar estado y total
$conn->query("UPDATE ordenes_reparacion 
              SET estado_reparacion = 'en_reparacion', total_servicios = $total, precio_total = total_repuestos + $total 
              WHERE id = $orden_id");

echo json_encode(['success' => true]);
