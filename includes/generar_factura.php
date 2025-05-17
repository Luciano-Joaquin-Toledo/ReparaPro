<?php
include 'config.php';

$orden_id = $_POST['orden_reparacion'];  // Asegúrate de que esto sea el ID correcto de la orden de reparación
$precio_total = $_POST['precio_total'];
$metodo_pago = $_POST['metodo_pago'];
$monto_pagado = $_POST['monto_pagado'];
$estado_pago = $_POST['estado_pago'];
$fecha = date('Y-m-d H:i:s');

// Insert en tabla facturas
$sql = "INSERT INTO facturas (orden_reparacion_id, total, metodo_pago, monto_pagado, estado_pago, fecha)
        VALUES ($orden_id, $precio_total, '$metodo_pago', $monto_pagado, '$estado_pago', '$fecha')";

if ($conn->query($sql)) {
    header("Location: ../gestion_facturacion.php?ok=1");
} else {
    echo "Error al guardar: " . $conn->error;
}
?>
