<?php
include('config.php');  // Asegúrate de tener la conexión a la base de datos

if (isset($_GET['cliente_id'])) {
    $cliente_id = $_GET['cliente_id'];

    // Consulta para obtener las reparaciones finalizadas con factura
    $query = "
        SELECT r.id, r.descripcion 
        FROM ordenes_reparacion r
        JOIN facturas f ON r.id = f.orden_reparacion_id
        WHERE r.cliente_id = ? AND r.estado_reparacion = 'finalizado'";

    // Preparamos y ejecutamos la consulta
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Obtener las reparaciones en un array
    $reparaciones = [];
    while ($row = $result->fetch_assoc()) {
        $reparaciones[] = $row;
    }

    // Devolver los datos en formato JSON
    echo json_encode(['reparaciones' => $reparaciones]);
}
?>
