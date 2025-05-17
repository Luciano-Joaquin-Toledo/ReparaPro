<?php
include './config.php'; // Asegúrate de que este archivo esté correctamente configurado para la conexión a la base de datos

// Verificamos si el parámetro 'id' está presente en la URL
if (isset($_GET['id'])) {
    $id_cliente = intval($_GET['id']); // Obtenemos el ID del cliente

    // Preparamos la consulta SQL para obtener los datos del cliente
    $sql = "SELECT id, nombre, telefono, email, direccion, dni, tipo_cliente, notas FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql); // Preparamos la consulta
    $stmt->bind_param("i", $id_cliente); // Enlazamos el ID a la consulta

    // Ejecutamos la consulta
    if ($stmt->execute()) {
        $result = $stmt->get_result(); // Obtenemos el resultado de la consulta

        // Verificamos si se encontró un cliente con ese ID
        if ($result->num_rows === 1) {
            // Si se encontró, devolvemos los datos del cliente en formato JSON
            echo json_encode($result->fetch_assoc());
        } else {
            // Si no se encontró el cliente, devolvemos un error 404
            http_response_code(404);
            echo json_encode(["error" => "Cliente no encontrado."]);
        }
    } else {
        // Si hubo un error al ejecutar la consulta, devolvemos un error 500
        http_response_code(500);
        echo json_encode(["error" => "Error al ejecutar consulta."]);
    }

    // Cerramos la consulta y la conexión
    $stmt->close();
    $conn->close();
} else {
    // Si no se pasa el parámetro 'id', devolvemos un error 400
    http_response_code(400);
    echo json_encode(["error" => "ID inválido."]);
}
?>
