<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit;
}

// 1) Recoger y validar datos
$cliente_id             = intval($_POST['cliente_id'] ?? 0);
$reparacion_id          = intval($_POST['reparacion_id'] ?? 0);
$fecha_entrega          = $_POST['fecha_entrega'] ?? '';
$metodo_entrega         = $_POST['metodo_entrega'] ?? '';
$ubicacion_entrega      = trim($_POST['ubicacion_entrega'] ?? '');
$observaciones          = trim($_POST['observaciones_generales'] ?? '');
$comentarios_tecnicos   = trim($_POST['comentarios_tecnicos'] ?? '');
$estado_pago_entrega    = $_POST['estado_pago_entrega'] ?? '';

if (!$cliente_id || !$reparacion_id || !$fecha_entrega || !$metodo_entrega) {
    die('Faltan datos obligatorios.');
}

// 2) Comprobar que la orden existe y está finalizada
$stmt = $conn->prepare("
    SELECT id 
    FROM ordenes_reparacion 
    WHERE id = ? 
      AND cliente_id = ? 
      AND estado_reparacion = 'finalizado'
");
$stmt->bind_param('ii', $reparacion_id, $cliente_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    die('Orden inválida o no finalizada.');
}

// 3) Insertar en `entregas`
$stmt = $conn->prepare("
    INSERT INTO entregas (
        orden_reparacion_id,
        fecha_entrega,
        metodo_entrega,
        ubicacion_entrega,
        observaciones_generales,
        comentarios_tecnicos,
        estado_pago_entrega
    ) VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param(
    'issssss',
    $reparacion_id,
    $fecha_entrega,
    $metodo_entrega,
    $ubicacion_entrega,
    $observaciones,
    $comentarios_tecnicos,
    $estado_pago_entrega
);

if ($stmt->execute()) {
    // (Opcional) cambiar estado de la órdenes, p.ej. 'entregado'
    // $conn->query("UPDATE ordenes_reparacion 
    //               SET estado_reparacion = 'entregado' 
    //               WHERE id = $reparacion_id");

    header('Location: ../gestion_entrega.php?entrega=ok');
    exit;
} else {
    die('Error al guardar entrega: ' . $conn->error);
}
