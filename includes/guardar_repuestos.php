<?php
require_once './config.php';  // tu conexión mysqli en $conn

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Acceso no válido');
}

// 1) Recuperar y validar reparacion_id
$reparacion_id = isset($_POST['reparacion_id']) 
    ? intval($_POST['reparacion_id']) 
    : 0;
if ($reparacion_id <= 0) {
    exit('Error: ID de reparación no válido.');
}

// Verificar que la reparación exista
$chk = $conn->prepare("SELECT id FROM ordenes_reparacion WHERE id = ?");
$chk->bind_param("i", $reparacion_id);
$chk->execute();
$resChk = $chk->get_result();
if ($resChk->num_rows === 0) {
    exit('Error: La orden de reparación no existe.');
}

// 2) Recuperar repuestos y cantidad
$repuestos = isset($_POST['repuestos']) ? $_POST['repuestos'] : [];
$cantidad  = isset($_POST['cantidad'])   ? intval($_POST['cantidad']) : 0;

// 3) Iniciar transacción
$conn->begin_transaction();

try {
    $totalCostoRepuestos = 0.0;

    if (!empty($repuestos) && $cantidad > 0) {
        // Sólo insertar y descontar inventario si hay repuestos válidos
        $stmtArt    = $conn->prepare("SELECT nombre, precio, cantidad FROM articulos WHERE id = ?");
        $stmtIns    = $conn->prepare(
            "INSERT INTO ordenes_repuestos
                (orden_reparacion_id, repuesto_nombre, cantidad, costo)
             VALUES (?, ?, ?, ?)"
        );
        $stmtUpdArt = $conn->prepare("UPDATE articulos SET cantidad = ? WHERE id = ?");

        foreach ($repuestos as $repId) {
            $repId = intval($repId);

            // 4) Cargar datos del artículo
            $stmtArt->bind_param("i", $repId);
            $stmtArt->execute();
            $art = $stmtArt->get_result()->fetch_assoc();
            if (!$art) {
                throw new Exception("Artículo no encontrado (ID: $repId).");
            }

            $stockActual = intval($art['cantidad']);
            $precioUnit  = floatval($art['precio']);
            $nombreRep   = $art['nombre'];

            // 5) Comprobar stock
            if ($stockActual < $cantidad) {
                throw new Exception("Stock insuficiente para '{$nombreRep}' (disponible: {$stockActual}).");
            }

            // 6) Insertar en ordenes_repuestos
            $costoTotal = $precioUnit * $cantidad;
            $stmtIns->bind_param("isid", $reparacion_id, $nombreRep, $cantidad, $costoTotal);
            $stmtIns->execute();

            // 7) Restar inventario
            $nuevoStock = $stockActual - $cantidad;
            $stmtUpdArt->bind_param("ii", $nuevoStock, $repId);
            $stmtUpdArt->execute();

            $totalCostoRepuestos += $costoTotal;
        }
    }

    // 8) Actualizar estado de la reparación a 'finalizado'
    //    y sumar totales (si $totalCostoRepuestos==0, no cambia nada)
    $stmtUpdRep = $conn->prepare(
        "UPDATE ordenes_reparacion
         SET estado_reparacion = 'finalizado',
             total_repuestos    = total_repuestos + ?,
             precio_total       = precio_total + ?
         WHERE id = ?"
    );
    $stmtUpdRep->bind_param("ddi", $totalCostoRepuestos, $totalCostoRepuestos, $reparacion_id);
    $stmtUpdRep->execute();

    // 9) Commit
    $conn->commit();

    // Redirigir con un mensaje de éxito
    header('Location: ../gestion_reparaciones.php?status=success&message=Reparación finalizada con éxito');
    exit();
} catch (Exception $e) {
    $conn->rollback();

    // Redirigir con un mensaje de error
    header('Location: ../gestion_reparaciones.php?status=error&message=' . urlencode($e->getMessage()));
    exit();
}
