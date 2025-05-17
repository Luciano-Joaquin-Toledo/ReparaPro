<?php
require_once('./config.php');

// Verificar si se enviaron los datos por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente = $_POST['cliente'];  // Asegúrate de capturar el cliente
    $equipo = $_POST['equipo'];
    $tecnico = $_POST['tecnico'];
    $descripcion = $_POST['descripcion'];
    $estado_reparacion = $_POST['estado_reparacion'];
    $fecha_ingreso = $_POST['fecha_ingreso'];

    // Verifica que el cliente exista
    $sqlClienteExistente = "SELECT id FROM clientes WHERE id = '$cliente'";
    $resultClienteExistente = $conn->query($sqlClienteExistente);

    if ($resultClienteExistente->num_rows > 0) {
        // Insertar la orden de reparación en la base de datos
        $sql = "INSERT INTO ordenes_reparacion (cliente_id, equipo_id, tecnico_id, descripcion, estado_reparacion, fecha_ingreso) 
                VALUES ('$cliente', '$equipo', '$tecnico', '$descripcion', '$estado_reparacion', '$fecha_ingreso')";

        if ($conn->query($sql) === TRUE) {
            echo "Reparación registrada correctamente";
            header("Location: ../gestion_reparaciones.php");  // Redirigir
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: El cliente no existe.";
    }
}

// Cerrar la conexión
$conn->close();
?>
